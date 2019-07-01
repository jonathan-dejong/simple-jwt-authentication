<?php
/**
 * The user profile specific functionality of the plugin.
 *
 * @since 1.0
 */

class Simple_Jwt_Authentication_Profile {
	/**
	 * The plugin name
	 *
	 * @var string
	 */
	protected $plugin_name;

	/**
	 * The plugin version
	 *
	 * @var string
	 */
	protected $plugin_version;

	/**
	 * Contains the users data.
	 *
	 * @var object
	 */
	protected $user = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0
	 */
	public function __construct( $plugin_name, $plugin_version ) {
		$this->plugin_name    = $plugin_name;
		$this->plugin_version = $plugin_version;

		add_action( 'current_screen', array( $this, 'set_edited_user' ), 10 );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'edit_user_profile', array( $this, 'user_token_ui' ), 20 );
		add_action( 'show_user_profile', array( $this, 'user_token_ui' ), 20 );
		add_action( 'current_screen', array( $this, 'maybe_revoke_token' ), 15 );
		add_action( 'show_user_profile', array( $this, 'maybe_revoke_token' ) );
		add_action( 'current_screen', array( $this, 'maybe_revoke_all_tokens' ), 15 );
		add_action( 'show_user_profile', array( $this, 'maybe_revoke_all_tokens' ) );
		add_action( 'current_screen', array( $this, 'maybe_remove_expired_tokens' ), 15 );
		add_action( 'show_user_profile', array( $this, 'maybe_remove_expired_tokens' ) );

	}

	/**
	 * Maybe show admin notice to the user.
	 *
	 * @return void
	 */
	public function admin_notices() {
		// Nonce validation for security.
		if ( ! isset( $_GET['jwt_nonce'] ) || ! wp_verify_nonce( $_GET['jwt_nonce'], 'simple-jwt-ui-nonce' ) ) {
			return;
		}

		if ( empty( $_GET['jwtupdated'] ) ) {
			return;
		}

		$class = 'notice-success';

		if ( ! empty( $_GET['revoked'] ) && 'all' === $_GET['revoked'] ) {
			$message = __( 'All tokens have been revoked.', 'simple-jwt-authentication' );
		} elseif ( ! empty( $_GET['revoked'] ) ) {
			// TranslatorS: %s is a token ID.
			$message = sprintf( __( 'The token %s has been revoked.', 'simple-jwt-authentication' ), $_GET['revoked'] );
		} elseif ( ! empty( $_GET['removed'] ) ) {
			$message = __( 'All expired tokens have been removed.', 'simple-jwt-authentication' );
		}
		echo sprintf( '<div class="notice is-dismissible %1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );

	}

	/**
	 * Adds a token UI metabox to each user.
	 *
	 * @param object $user A WP_User object
	 * @since 1.0
	 */
	public function user_token_ui( $user ) {
		if ( current_user_can( 'edit_users' ) ) {
			$tokens = get_user_meta( $user->ID, 'jwt_data', true ) ?: false;
			include plugin_dir_path( __FILE__ ) . 'views/user-token-ui.php';

		}
	}

	/**
	 * Check if we should revoke a token.
	 *
	 * @since 1.0
	 */
	public function maybe_revoke_token() {
		// Nonce validation for security.
		if ( ! isset( $_GET['jwt_nonce'] ) || ! wp_verify_nonce( $_GET['jwt_nonce'], 'simple-jwt-ui-nonce' ) ) {
			return;
		}
		if ( $this->user && current_user_can( 'edit_users' ) && ! empty( $_GET['revoke_token'] ) ) {
			$tokens        = get_user_meta( $this->user->ID, 'jwt_data', true ) ?: false;
			$request_token = $_GET['revoke_token'];

			if ( $tokens ) {
				foreach ( $tokens as $key => $token ) {
					if ( $token['uuid'] === $_GET['revoke_token'] ) {
						unset( $tokens[ $key ] );
						update_user_meta( $this->user->ID, 'jwt_data', $tokens );
						break;
					}
				}
			}

			$current_url  = get_home_url() . $_SERVER['REQUEST_URI'];
			$redirect_url = add_query_arg(
				array(
					'jwtupdated' => 1,
					'revoked'    => $request_token,
				),
				remove_query_arg( array( 'revoke_token' ), $current_url )
			);
			wp_safe_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * Check if we should revoke a token.
	 *
	 * @since 1.0
	 */
	public function maybe_revoke_all_tokens() {
		// Nonce validation for security.
		if ( ! isset( $_GET['jwt_nonce'] ) || ! wp_verify_nonce( $_GET['jwt_nonce'], 'simple-jwt-ui-nonce' ) ) {
			return;
		}

		if ( $this->user && current_user_can( 'edit_users' ) && ! empty( $_GET['revoke_all_tokens'] ) ) {
			delete_user_meta( $this->user->ID, 'jwt_data' );
			$current_url = get_home_url() . $_SERVER['REQUEST_URI'];

			$redirect_url = add_query_arg(
				array(
					'jwtupdated' => 1,
					'revoked'    => 'all',
				),
				remove_query_arg( array( 'revoke_all_tokens' ), $current_url )
			);
			wp_safe_redirect( $redirect_url );
			exit;

		}

	}

	/**
	 * Check if we should revoke a token.
	 *
	 * @since 1.0
	 */
	public function maybe_remove_expired_tokens() {
		// Nonce validation for security.
		if ( ! isset( $_GET['jwt_nonce'] ) || ! wp_verify_nonce( $_GET['jwt_nonce'], 'simple-jwt-ui-nonce' ) ) {
			return;
		}

		if ( $this->user && current_user_can( 'edit_users' ) && ! empty( $_GET['remove_expired_tokens'] ) ) {

			$tokens = get_user_meta( $this->user->ID, 'jwt_data', true ) ?: false;
			if ( $tokens ) {
				foreach ( $tokens as $key => $token ) {
					if ( $token['expires'] < time() ) {
						unset( $tokens[ $key ] );
					}
				}
				update_user_meta( $this->user->ID, 'jwt_data', $tokens );
			}

			$current_url  = get_home_url() . $_SERVER['REQUEST_URI'];
			$redirect_url = add_query_arg(
				array(
					'jwtupdated' => 1,
					'removed'    => 'all',
				),
				remove_query_arg( array( 'remove_expired_tokens' ), $current_url )
			);
			wp_safe_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * check if is edit user page, and if user exist from GET param
	 *
	 * @since 1.1.1
	 */
	public function set_edited_user() {
		// Nonce validation for security.
		if ( ! isset( $_GET['jwt_nonce'] ) || ! wp_verify_nonce( $_GET['jwt_nonce'], 'simple-jwt-ui-nonce' ) ) {
			return;
		}

		$user_id = ( ! empty( $_GET['user_id'] ) ) ? intval( $_GET['user_id'] ) : false;

		// If user ID was not set by $_GET check if we should get it from the current user.
		if ( false === $user_id ) {
			$current_screen = get_current_screen();
			if ( 'profile' === $current_screen->id ) {
				$user_id = get_current_user_id();
			}
		}

		// Bail if no user id can be found.
		if ( false === $user_id ) {
			return;
		}

		// Get user data and save to our parameter.
		$user = get_userdata( (int) $user_id );
		if ( false !== $user ) {
			$user       = $user->data;
			$this->user = $user;
		}
	}
}
new Simple_Jwt_Authentication_Profile( $plugin_name, $plugin_version );
