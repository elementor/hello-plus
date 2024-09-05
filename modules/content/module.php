<?php

namespace HelloPlus\Modules\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Includes\Module_Base;
use HelloPlus\Theme;

/**
 * class Module
 **/
class Module extends Module_Base {

	/**
	 * @inheritDoc
	 */
	public static function get_name(): string {
		return 'content';
	}

	/**
	 * @inheritDoc
	 */
	protected function get_component_ids(): array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	protected function get_widget_ids(): array {
		return [
			'Zig_Zag',
		];
	}

	public function enqueue(): void {
		wp_enqueue_script(
			'hello-plus-content',
			HELLO_PLUS_SCRIPTS_URL . 'hello-plus-content' . Theme::get_min_suffix() . '.js',
			[],
			HELLO_PLUS_ELEMENTOR_VERSION,
			true
		);

		wp_enqueue_style(
			'hello-plus-content',
			HELLO_PLUS_STYLE_URL . 'hello-plus-content' . Theme::get_min_suffix() . '.css',
			[],
			HELLO_PLUS_ELEMENTOR_VERSION
		);
	}


	protected function register_hooks():void {
		parent::register_hooks();
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
	}
}
