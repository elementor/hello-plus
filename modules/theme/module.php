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
	const HELLO_PLUS_EDITOR_CATEGORY_SLUG = 'helloplus';

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
		return [];
	}

	/**
	 * @param \Elementor\Elements_Manager $elements_manager
	 *
	 * @return void
	 */
	public function add_hello_plus_e_panel_categories( \Elementor\Elements_Manager $elements_manager ) {
		$elements_manager->add_category(
			self::HELLO_PLUS_EDITOR_CATEGORY_SLUG,
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
		add_action( 'hello-plus-theme/header_footer', '__return_false' );
		add_action( 'hello-plus-theme/enqueue_theme_style', '__return_false' );
	}
}
