<?php

namespace HelloPlus\Modules\TemplateParts\Documents;

use HelloPlus\Includes\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * class Header
 **/
class Ehp_Header extends Ehp_Document_Base {
	const LOCATION = 'header';

	public static function get_template_hook(): string {
		return 'get_header';
	}

	public static function get_type(): string {
		return 'ehp-header';
	}

	public static function get_title(): string {
		return esc_html__( 'Hello+ Header', 'hello-plus' );
	}

	public static function get_plural_title(): string {
		return esc_html__( 'Hello+ Headers', 'hello-plus' );
	}

	public function get_widget_object( array $elements_data = [] ): ?\HelloPlus\Modules\TemplateParts\Widgets\Ehp_Header {

		if ( empty( $elements_data ) ) {
			$elements_data = $this->get_elements_data();
		}

		foreach ( $elements_data as $element ) {
			if ( isset( $element['widgetType'] ) ) {
				$widget_type = $element['widgetType'];
				if ( static::get_type() === $widget_type ) {
					return Utils::elementor()->elements_manager->create_element_instance( $element );
				}
			}

			if ( ! empty( $element['elements'] ) ) {
				return $this->get_widget_object( $element['elements'] );
			}
		}

		throw new \Exception( esc_html__( 'No header widget found', 'hello-plus' ) );
	}

	protected static function get_site_editor_icon(): string {
		return 'eicon-header';
	}

	public static function get_template( $name, $args ): void {
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
