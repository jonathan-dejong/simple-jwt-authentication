<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Simple_Jwt_Authentication_Api {

	/**
	 * Get current user IP.
	 *
	 * @since 1.0
	 * @return string
	 */
	public static function get_ip() {
		return ! empty( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : __( 'Unknown', 'simple-jwt-authentication' );
	}


	/**
	 * Check wether setting is defined globally in wp-config.php
	 *
	 * @since 1.0
	 * @param  string  $key settings key
	 * @return boolean
	 */
	public static function is_global( $key ) {
		return defined( $key );

	}


	/**
	 * Get plugin settings array.
	 *
	 * @since 1.0
	 * @return array
	 */
	public static function get_db_settings() {
		return get_option( 'simple_jwt_authentication_settings' );

	}


	/**
	 * Get the auth key.
	 *
	 * @since 1.0
	 * @return string
	 */
	public static function get_key() {
		if ( defined( 'SIMPLE_JWT_AUTHENTICATION_SECRET_KEY' ) ) {
			return SIMPLE_JWT_AUTHENTICATION_SECRET_KEY;
		} else {
			$settings = self::get_db_settings();
			if ( $settings ) {
				return $settings['secret_key'];
			}
		}
		return false;

	}

	/**
	 * Get CORS enabled/disabled
	 *
	 * @since 1.0
	 * @return string
	 */
	public static function get_cors() {
		if ( defined( 'SIMPLE_JWT_AUTHENTICATION_CORS_ENABLE' ) ) {
			return SIMPLE_JWT_AUTHENTICATION_CORS_ENABLE;
		} else {
			$settings = self::get_db_settings();
			if ( $settings ) {
				return $settings['enable_cors'];
			}
		}
		return false;

	}
}
