<?php
namespace HelloPlus;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * class Utils
 **/
class Utils {
	/**
	 * @static
	 * @access public
	 *
	 * @return \Elementor\Plugin
	 */
	public static function elementor(): \Elementor\Plugin {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * @static
	 * @access public
	 *
	 * @return bool
	 */
	public static function is_elementor_active(): bool {
		return defined( 'ELEMENTOR_VERSION' );
	}
}
