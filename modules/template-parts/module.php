<?php

namespace HelloPlus\Modules\TemplateParts;

use HelloPlus\Includes\Module_Base;
use HelloPlus\Includes\Utils;

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
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function get_widget_ids(): array {
		return [
			'Header',
		];
	}

	public function register_frontend_styles(): void {
		wp_register_style(
			'hello-plus-header',
			HELLO_PLUS_STYLE_URL . 'hello-plus-header.css',
			[ 'elementor-frontend' ],
			HELLO_PLUS_ELEMENTOR_VERSION
		);
	}

	public function enqueue(): void {
		wp_enqueue_script(
			'hello-plus-header',
			HELLO_PLUS_SCRIPTS_URL . 'hello-plus-header.js',
			[ 'jquery' ],
			HELLO_PLUS_ELEMENTOR_VERSION,
			true
		);
	}

	public function editor_enqueue(): void {
		wp_enqueue_script(
			'hello-plus-header-editor',
			HELLO_PLUS_SCRIPTS_URL . 'hello-plus-header-editor.js',
			[ 'elementor-editor' ],
			HELLO_PLUS_ELEMENTOR_VERSION,
			true
		);
	}

	/**
	 * @return bool
	 */
	public static function is_active(): bool {
		return Utils::is_elementor_active();
	}

	/**
	 * @inheritDoc
	 */
	protected function register_hooks(): void {
		parent::register_hooks();
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_frontend_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'editor_enqueue' ] );
	}
}
