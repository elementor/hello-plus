<?php

namespace HelloPlus\Modules\TemplateParts\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * class Header
 **/
class Header extends Document_Base {
	public static function get_template_hook(): string {
		return 'get_header';
	}

	public static function get_type(): string {
		return 'ehp-header';
	}

	/**
	 * Get document title.
	 *
	 * Retrieve the document title.
	 *
	 * @access public
	 * @static
	 *
	 * @return string Document title.
	 */
	public static function get_title(): string {
		return esc_html__( 'Hello+ Header', 'hello-plus' );
	}

	public static function get_plural_title(): string {
		return esc_html__( 'Hello+ Headers', 'hello-plus' );
	}

	protected static function get_site_editor_icon(): string {
		return 'eicon-header';
	}

	public static function get_template( $name, $args ) {
		require static::get_templates_path() . 'header.php';

		$templates = [];
		$name = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "header-{$name}.php";
		}

		$templates[] = 'header.php';

		// Avoid running wp_head hooks again
		remove_all_actions( 'wp_head' );
		ob_start();
		// It causes a `require_once` so, in the get_header itself it will not be required again.
		locate_template( $templates, true );
		ob_get_clean();
	}
}
