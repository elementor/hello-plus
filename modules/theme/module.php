<?php

namespace HelloPlus\Modules\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Includes\Module_Base;

/**
 * Theme module
 *
 * @package HelloPlus
 * @subpackage HelloPlusModules
 */
class Module extends Module_Base {
	const HELLOPLUS_EDITOR_CATEGORY_SLUG = 'helloplus';

	/**
	 * @inheritDoc
	 */
	public static function get_name(): string {
		return 'theme';
	}

	/**
	 * @inheritDoc
	 */
	protected function get_component_ids(): array {
		return [
			'Theme_Overrides',
			'Theme_Dependency',
		];
	}

	public function register_styles(): void {
		wp_register_style(
			'helloplus-button',
			HELLOPLUS_STYLE_URL . 'helloplus-button.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
		);

		wp_register_style(
			'helloplus-image',
			HELLOPLUS_STYLE_URL . 'helloplus-image.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
		);

		wp_register_style(
			'helloplus-shapes',
			HELLOPLUS_STYLE_URL . 'helloplus-shapes.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
		);

		wp_register_style(
			'helloplus-column-structure',
			HELLOPLUS_STYLE_URL . 'helloplus-column-structure.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
		);
	}

	/**
	 * @param \Elementor\Elements_Manager $elements_manager
	 *
	 * @return void
	 */
	public function add_hello_plus_e_panel_categories( \Elementor\Elements_Manager $elements_manager ) {
		$categories = $elements_manager->get_categories();
		if ( ! empty( $categories[ self::HELLOPLUS_EDITOR_CATEGORY_SLUG ] ) ) {
			return;
		}

		$elements_manager->add_category(
			self::HELLOPLUS_EDITOR_CATEGORY_SLUG,
			[
				'title' => esc_html__( 'Hello+', 'hello-plus' ),
				'icon' => 'fa fa-plug',
			]
		);
	}

	/**
	 * @inheritDoc
	 */
	protected function register_hooks(): void {
		parent::register_hooks();
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_hello_plus_e_panel_categories' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
	}
}
