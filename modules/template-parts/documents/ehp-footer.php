<?php

namespace HelloPlus\Modules\TemplateParts\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * class Footer
 **/
class Ehp_Footer extends Ehp_Document_Base {
	const LOCATION = 'footer';

	public static function get_template_hook(): string {
		return 'get_footer';
	}

	public static function get_type(): string {
		return 'ehp-footer';
	}

	public static function get_title(): string {
		return esc_html__( 'Hello+ Footer', 'hello-plus' );
	}

	public static function get_plural_title(): string {
		return esc_html__( 'Hello+ Footers', 'hello-plus' );
	}

	public static function get_template( $name, $args ): void {
		require static::get_templates_path() . 'footer.php';

		$templates = [];
		$name = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "footer-{$name}.php";
		}

		$templates[] = 'footer.php';

		// Avoid running wp_footer hooks again
		remove_all_actions( 'wp_footer' );
		ob_start();
		// It causes a `require_once` so, in the hook itself it will not be required again.
		locate_template( $templates, true );
		ob_get_clean();
	}
}
