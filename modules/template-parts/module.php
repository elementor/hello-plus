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
			'Footer',
		];
	}

	public function enqueue(): void {
		wp_enqueue_style(
			'hello-plus-header',
			HELLO_PLUS_STYLE_URL . 'hello-plus-header.css',
			[],
			HELLO_PLUS_ELEMENTOR_VERSION
		);

		wp_enqueue_style(
			'hello-plus-footer',
			HELLO_PLUS_STYLE_URL . 'hello-plus-footer.css',
			[],
			HELLO_PLUS_ELEMENTOR_VERSION
		);

		wp_enqueue_script(
			'hello-plus-header',
			HELLO_PLUS_SCRIPTS_URL . 'hello-plus-header.js',
			[ 'jquery' ],
			HELLO_PLUS_ELEMENTOR_VERSION,
			true
		);
	}

	public function get_script_depends(): array {
		return [ 'hello-plus-header' ];
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
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
	}
}
