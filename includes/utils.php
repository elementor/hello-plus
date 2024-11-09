<?php
namespace HelloPlus\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * class Utils
 **/
class Utils {

	private static $elementor_installed = null;

	private static $elementor_active = null;

	public static function elementor(): \Elementor\Plugin {
		return \Elementor\Plugin::$instance;
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

	public static function get_site_domain() {
		return str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	}

	public static function get_current_post_id() {
		if ( isset( self::elementor()->documents ) && self::elementor()->documents->get_current() ) {
			return self::elementor()->documents->get_current()->get_main_id();
		}

		return get_the_ID();
	}

	public static function _unstable_get_super_global_value( $super_global, $key ) {
		if ( ! isset( $super_global[ $key ] ) ) {
			return null;
		}

		if ( $_FILES === $super_global ) {
			return isset( $super_global[ $key ]['name'] ) ?
				static::sanitize_file_name( $super_global[ $key ] ) :
				static::sanitize_multi_upload( $super_global[ $key ] );
		}

		return wp_kses_post_deep( wp_unslash( $super_global[ $key ] ) );
	}

	private static function sanitize_multi_upload( $fields ) {
		return array_map( function( $field ) {
			return array_map( [ __CLASS__, 'sanitize_file_name' ], $field );
		}, $fields );
	}

	private static function sanitize_file_name( $file ) {
		$file['name'] = sanitize_file_name( $file['name'] );

		return $file;
	}
}
