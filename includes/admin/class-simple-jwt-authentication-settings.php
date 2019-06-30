<?php
/**
 * The user profile specific functionality of the plugin.
 *
 * @since 1.0
 */

class Simple_Jwt_Authentication_Settings {

	protected $plugin_name;
	protected $plugin_version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0
	 */
	public function __construct( $plugin_name, $plugin_version ) {
		$this->plugin_name    = $plugin_name;
		$this->plugin_version = $plugin_version;

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );

	}


	/**
	 * Adds the menu page to options.
	 *
	 * @since 1.0
	 */
	public function add_admin_menu() {
		add_options_page(
			'Simple JWT Authentication',
			'Simple JWT Authentication',
			'manage_options',
			'simple_jwt_authentication',
			array( $this, 'simple_jwt_authentication_options_page' )
		);

	}


	/**
	 * Initialize all settings.
	 *
	 * @since 1.0
	 */
	public function settings_init() {
		register_setting( 'simple_jwt_authentication', 'simple_jwt_authentication_settings' );

		add_settings_section(
			'simple_jwt_authentication_section',
			__( 'Basic configuration', 'simple-jwt-authentication' ),
			array( $this, 'settings_section_callback' ),
			'simple_jwt_authentication'
		);

		add_settings_field(
			'secret_key',
			__( 'Secret Key', 'simple-jwt-authentication' ),
			array( $this, 'settings_secret_callback' ),
			'simple_jwt_authentication',
			'simple_jwt_authentication_section'
		);

		add_settings_field(
			'enable_cors',
			// translators: %s is a link to CORS docs.
			sprintf( __( 'Enable %s', 'simple-jwt-authentication' ), '<a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS" target="_blank" rel="nofollow">CORS</a>' ),
			array( $this, 'settings_cors_callback' ),
			'simple_jwt_authentication',
			'simple_jwt_authentication_section'
		);

	}


	/**
	 * Secret key field callback.
	 *
	 * @since 1.0
	 */
	public function settings_secret_callback() {
		$secret_key = Simple_Jwt_Authentication_Api::get_key();
		$is_global  = Simple_Jwt_Authentication_Api::is_global( 'SIMPLE_JWT_AUTHENTICATION_SECRET_KEY' );
		include plugin_dir_path( __FILE__ ) . 'views/settings/secret-key.php';

	}


	/**
	 * Enable/disable cors field callback.
	 *
	 * @since 1.0
	 */
	public function settings_cors_callback() {
		$enable_cors = Simple_Jwt_Authentication_Api::get_cors();
		$is_global   = Simple_Jwt_Authentication_Api::is_global( 'SIMPLE_JWT_AUTHENTICATION_CORS_ENABLE' );
		include plugin_dir_path( __FILE__ ) . 'views/settings/enable-cors.php';

	}


	/**
	 * Section callback.
	 *
	 * @since 1.0
	 */
	public function settings_section_callback() {
		echo sprintf( __( 'This is all you need to start using JWT authentication.<br /> You can also specify these in wp-config.php instead using %1$s %2$s', 'simple-jwt-authentication' ), "<br /><br /><code>define( 'SIMPLE_JWT_AUTHENTICATION_SECRET_KEY', YOURKEY );</code>", "<br /><br /><code>define( 'SIMPLE_JWT_AUTHENTICATION_CORS_ENABLE', true );</code>" ); // phpcs:ignore

	}


	/**
	 * Settings form callback.
	 *
	 * @since 1.0
	 */
	public function simple_jwt_authentication_options_page() {
		include plugin_dir_path( __FILE__ ) . 'views/settings/page.php';

	}


}

new Simple_Jwt_Authentication_Settings( $plugin_name, $plugin_version );
