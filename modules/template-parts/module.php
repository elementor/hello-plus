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

	const USER_META_TIMES_EDITOR_OPENED = 'helloplus_editor_opened';

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
			'Checklist',
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
			'helloplus-header',
			HELLOPLUS_SCRIPTS_URL . 'helloplus-header.js',
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
			'helloplus-header',
			HELLOPLUS_STYLE_URL . 'helloplus-header.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
		);

		wp_register_style(
			'helloplus-footer',
			HELLOPLUS_STYLE_URL . 'helloplus-footer.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
		);
	}

	/**
	 * @return void
	 */
	public function enqueue_editor_scripts(): void {
		wp_enqueue_script(
			'helloplus-editor',
			HELLOPLUS_SCRIPTS_URL . 'helloplus-editor.js',
			[ 'elementor-editor' ],
			HELLOPLUS_VERSION,
			true
		);

		wp_localize_script(
			'helloplus-editor',
			'helloplusEditor',
			[
				'timesEditorOpened' => (int) get_user_meta(
					get_current_user_id(),
					self::USER_META_TIMES_EDITOR_OPENED,
					true
				),
			]
		);
	}

	/**
	 * @return void
	 */
	public function enqueue_editor_styles(): void {
		wp_enqueue_style(
			'helloplus-template-parts-editor',
			HELLOPLUS_STYLE_URL . 'helloplus-template-parts-editor.css',
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
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
	}
}
