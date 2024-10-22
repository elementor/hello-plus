<?php

namespace HelloPlus\Modules\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Includes\Module_Base;
use HelloPlus\Includes\Utils;

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
			'Hero',
			'CTA',
		];
	}

	public function enqueue(): void {
		wp_enqueue_style(
			'hello-plus-zigzag',
			HELLO_PLUS_STYLE_URL . 'hello-plus-zigzag.css',
			[],
			HELLO_PLUS_VERSION
		);

		wp_enqueue_style(
			'hello-plus-hero',
			HELLO_PLUS_STYLE_URL . 'hello-plus-hero.css',
			[],
			HELLO_PLUS_VERSION
		);

		wp_enqueue_style(
			'hello-plus-cta',
			HELLO_PLUS_STYLE_URL . 'hello-plus-cta.css',
			[],
			HELLO_PLUS_VERSION
		);
	}

	/**
	 * Load the Module only if Elementor is active.
	 *
	 * @return bool
	 */
	public static function is_active(): bool {
		return Utils::is_elementor_active();
	}

	protected function register_hooks(): void {
		parent::register_hooks();
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
	}
}
