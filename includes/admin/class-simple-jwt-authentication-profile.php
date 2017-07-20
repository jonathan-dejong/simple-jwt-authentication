<?php
/**
 * The user profile specific functionality of the plugin.
 *
 * @since 1.0
 */

class Simple_Jwt_Authentication_Profile {

	protected $plugin_name;
	protected $plugin_version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0
	 */
	public function __construct( $plugin_name, $plugin_version ) {
		$this->plugin_name = $plugin_name;
		$this->plugin_version = $plugin_version;

		add_action( 'edit_user_profile', array( $this, 'user_token_ui' ) );
		add_action( 'show_user_profile', array( $this, 'user_token_ui' ) );
		add_action( 'edit_user_profile', array( $this, 'maybe_revoke_token' ) );
		add_action( 'show_user_profile', array( $this, 'maybe_revoke_token' ) );
		add_action( 'edit_user_profile', array( $this, 'maybe_revoke_all_tokens' ) );
		add_action( 'show_user_profile', array( $this, 'maybe_revoke_all_tokens' ) );
		add_action( 'edit_user_profile', array( $this, 'maybe_remove_expired_tokens' ) );
		add_action( 'show_user_profile', array( $this, 'maybe_remove_expired_tokens' ) );

	}


	/**
	 * Adds a token UI metabox to each user.
	 *
	 * @param object $user A WP_User object
	 * @since 1.0
	 */
	public function user_token_ui( $user ) {
		if ( current_user_can( 'edit_user' ) ) {
			$tokens = get_user_meta( $user->ID, 'jwt_data', true ) ?: false;
			include plugin_dir_path( __FILE__ ) . 'views/user-token-ui.php';

		}

	}


	/**
	 * Check if we should revoke a token.
	 *
	 * @param object $user A WP_User object
	 * @since 1.0
	 */
	public function maybe_revoke_token( $user ) {
		if ( current_user_can( 'edit_user' ) && ! empty( $_GET['revoke_token'] ) ) {

			$tokens = get_user_meta( $user->ID, 'jwt_data', true ) ?: false;
			$request_token = $_GET['revoke_token'];

			if ( $tokens ) {
				foreach ( $tokens as $key => $token ) {
					if ( $token['uuid'] == $_GET['revoke_token'] ) {
						unset( $tokens[ $key ] );
						update_user_meta( $user->ID , 'jwt_data', $tokens );
						break;
					}
				}
			}

			$redirect_url = home_url() . remove_query_arg( array( 'revoke_token' ) );
			wp_safe_redirect( $redirect_url );
			exit;

		}

	}


	/**
	 * Check if we should revoke a token.
	 *
	 * @param object $user A WP_User object
	 * @since 1.0
	 */
	public function maybe_revoke_all_tokens( $user ) {
		if ( current_user_can( 'edit_user' ) && ! empty( $_GET['revoke_all_tokens'] ) ) {
			delete_user_meta( $user->ID, 'jwt_data' );

			$redirect_url = home_url() . remove_query_arg( array( 'revoke_all_tokens' ) );
			wp_safe_redirect( $redirect_url );
			exit;

		}

	}


	/**
	 * Check if we should revoke a token.
	 *
	 * @param object $user A WP_User object
	 * @since 1.0
	 */
	public function maybe_remove_expired_tokens( $user ) {
		if ( current_user_can( 'edit_user' ) && ! empty( $_GET['remove_expired_tokens'] ) ) {

			$tokens = get_user_meta( $user->ID, 'jwt_data', true ) ?: false;
			if ( $tokens ) {
				foreach ( $tokens as $key => $token ) {
					if ( $token['expires'] < time() ) {
						unset( $tokens[ $key ] );
					}
				}
				update_user_meta( $user->ID , 'jwt_data', $tokens );
			}

			$redirect_url = home_url() . remove_query_arg( array( 'remove_expired_tokens' ) );
			wp_safe_redirect( $redirect_url );
			exit;
		}
	}

}
new Simple_Jwt_Authentication_Profile( $plugin_name, $plugin_version );
