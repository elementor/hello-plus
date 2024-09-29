<?php

namespace HelloPlus\Modules\Admin\Classes\Elementor;

class Elementor_Helper {

	private static $elementor_installed = null;

	private static $elementor_active = null;

	public static function is_elementor_installed() {
		if ( null === self::$elementor_installed ) {
			self::$elementor_installed = file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' );
		}

		return self::$elementor_installed;
	}

	public static function is_elementor_active() {
		if ( null === self::$elementor_active ) {
			self::$elementor_active = defined( 'ELEMENTOR_VERSION' );
		}

		return self::$elementor_active;
	}
}
