<?php

namespace HelloPlus\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Utils as ElementorUtils;

/**
 * class Utils
 **/
class Utils {

	private static $elementor_installed = null;

	private static $elementor_active = null;

	public static function elementor(): \Elementor\Plugin {
		return \Elementor\Plugin::$instance;
	}

	public static function has_pro() {
		return defined( 'ELEMENTOR_PRO_VERSION' );
	}

	public static function is_elementor_active(): bool {
		if ( null === self::$elementor_active ) {
			self::$elementor_active = defined( 'ELEMENTOR_VERSION' );
		}

		return self::$elementor_active;
	}

	public static function is_elementor_installed() {
		if ( null === self::$elementor_installed ) {
			self::$elementor_installed = file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' );
		}

		return self::$elementor_installed;
	}

	public static function get_current_post_id() {
		if ( isset( self::elementor()->documents ) && self::elementor()->documents->get_current() ) {
			return self::elementor()->documents->get_current()->get_main_id();
		}

		return get_the_ID();
	}

	public static function get_client_ip() {
		$server_ip_keys = [
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		];

		foreach ( $server_ip_keys as $key ) {
			$value = ElementorUtils::get_super_global_value( $_SERVER, $key );
			if ( $value && filter_var( $value, FILTER_VALIDATE_IP ) ) {
				return $value;
			}
		}

		return '127.0.0.1';
	}

	public static function ends_with( $full_string, $end_string ) {
		$len = strlen( $end_string );
		if ( 0 === $len ) {
			return true;
		}

		return ( substr( $full_string, - $len ) === $end_string );
	}

	public static function get_theme_slug(): string {
		if ( defined( 'EHP_THEME_SLUG' ) ) {
			return EHP_THEME_SLUG;
		}
		return 'hello-plus';
	}
}
