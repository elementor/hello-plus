<?php

namespace HelloPlus\Modules\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use HelloPlus\Includes\Module_Base;
use HelloPlus\Includes\Utils;
use HelloPlus\Modules\Content\Classes\Control_Zig_Zag_Animation;

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
			'Flex_Hero',
		];
	}

	/**
	 * @return void
	 */
	public function register_scripts() {
		wp_register_script(
			'helloplus-content-fe',
			HELLOPLUS_SCRIPTS_URL . 'helloplus-content-fe.js',
			[ 'elementor-common', 'elementor-frontend-modules', 'elementor-frontend' ],
			HELLOPLUS_VERSION,
			true
		);
	}

	public function register_styles(): void {
		wp_register_style(
			'helloplus-zigzag',
			HELLOPLUS_STYLE_URL . 'helloplus-zigzag.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
		);

		wp_register_style(
			'helloplus-hero',
			HELLOPLUS_STYLE_URL . 'helloplus-hero.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
		);

		wp_register_style(
			'helloplus-cta',
			HELLOPLUS_STYLE_URL . 'helloplus-cta.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
		);

		wp_register_style(
			'helloplus-flex-hero',
			HELLOPLUS_STYLE_URL . 'helloplus-flex-hero.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
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

	public function register_controls( Controls_Manager $controls_manager ) {
		$controls_manager->register( new Control_Zig_Zag_Animation() );
	}

	protected function register_hooks(): void {
		parent::register_hooks();
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
	}
}
