<?php

namespace HelloPlus\Modules\TemplateParts;

use Elementor\Controls_Manager;
use HelloPlus\Includes\Module_Base;
use HelloPlus\Includes\Utils;
use HelloPlus\Modules\TemplateParts\Classes\Control_Media_Preview;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * class Module
 **/
class Module extends Module_Base {

	/**
	 * @inheritDoc
	 */
	public static function get_name(): string {
		return 'template_parts';
	}

	/**
	 * @inheritDoc
	 */
	protected function get_component_ids(): array {
		return [
			'Document',
			'Import_Export',
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function get_widget_ids(): array {
		return [
			'Ehp_Header',
			'Ehp_Footer',
		];
	}


	/**
	 * @return void
	 */
	public function register_scripts(): void {
		wp_register_script(
			'hello-plus-header',
			HELLOPLUS_SCRIPTS_URL . 'hello-plus-header.js',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION,
			true
		);
	}

	/**
	 * @return void
	 */
	public function register_styles(): void {
		wp_register_style(
			'hello-plus-header',
			HELLOPLUS_STYLE_URL . 'hello-plus-header.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
		);

		wp_register_style(
			'hello-plus-footer',
			HELLOPLUS_STYLE_URL . 'hello-plus-footer.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
		);
	}


	/**
	 * @return void
	 */
	public function enqueue_editor_scripts(): void {
		wp_enqueue_script(
			'hello-plus-editor',
			HELLOPLUS_SCRIPTS_URL . 'hello-plus-editor.js',
			[ 'elementor-editor' ],
			HELLOPLUS_VERSION,
			true
		);
	}

	/**
	 * @return void
	 */
	public function enqueue_editor_styles(): void {
		wp_enqueue_style(
			'hello-plus-template-parts-preview',
			HELLOPLUS_STYLE_URL . 'hello-plus-template-parts-preview.css',
			[],
			HELLOPLUS_VERSION
		);
	}

	/**
	 * @return bool
	 */
	public static function is_active(): bool {
		return Utils::is_elementor_active();
	}

	public function register_controls( Controls_Manager $controls_manager ) {
		$controls_manager->register( new Control_Media_Preview() );
	}

	/**
	 * @inheritDoc
	 */
	protected function register_hooks(): void {
		parent::register_hooks();
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
	}
}
