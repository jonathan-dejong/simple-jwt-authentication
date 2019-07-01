<?php
/**
 * Plugin Name: Simple JWT Authentication
 * Plugin URI:  http://github.com/jonathan-dejong/simple-jwt-authentication
 * Description: Extends the WP REST API using JSON Web Tokens Authentication as an authentication method.
 * Version:     1.4.0
 * Author:      Jonathan de Jong
 * Author URI:  http://github.com/jonathan-dejong
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: simple-jwt-authentication
 * Domain Path: /languages
 *
 * @since 1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Simple_Jwt_Authentication {

	protected $plugin_name;
	protected $plugin_version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->plugin_name    = 'simple-jwt-authentication';
		$this->plugin_version = '1.4.0';

		// Load all dependency files.
		$this->load_dependencies();

		// Activation hook
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		// Deactivation hook
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// Localization
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

	}

	/**
	 * Loads all dependencies in our plugin.
	 *
	 * @since 1.0
	 */
	public function load_dependencies() {

		// Load all Composer dependencies
		$this->include_file( 'vendor/autoload.php' );
		$this->include_file( 'class-simple-jwt-authentication-api.php' );

		// Admin specific includes
		if ( is_admin() ) {
			$this->include_file( 'admin/class-simple-jwt-authentication-settings.php' );
			$this->include_file( 'admin/class-simple-jwt-authentication-profile.php' );
		}

		$this->include_file( 'class-simple-jwt-authentication-rest.php' );

	}

	/**
	 * Includes a single file located inside /includes.
	 *
	 * @param string $path relative path to /includes
	 * @since 1.0
	 */
	private function include_file( $path ) {
		$plugin_name    = $this->plugin_name;
		$plugin_version = $this->plugin_version;

		$includes_dir = trailingslashit( plugin_dir_path( __FILE__ ) . 'includes' );
		if ( file_exists( $includes_dir . $path ) ) {
			include_once $includes_dir . $path;
		}
	}

	/**
	 * The code that runs during plugin activation.
	 *
	 * @since    1.0
	 */
	public function activate() {

	}

	/**
	 * The code that runs during plugin deactivation.
	 *
	 * @since    1.0
	 */
	public function deactivate() {

	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0
	 */
	public function load_textdomain() {

		load_plugin_textdomain(
			'simple-jwt-authentication',
			false,
			basename( dirname( __FILE__ ) ) . '/languages/'
		);

	}

}

/**
 * Begins execution of the plugin.
 *
 * @since    1.0
 */
new Simple_Jwt_Authentication();
