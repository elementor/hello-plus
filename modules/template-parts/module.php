<?php

namespace HelloPlus\Modules\TemplateParts;

use Elementor\Controls_Manager;
use Hamcrest\Util;
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
			'helloplus-header-fe',
			HELLOPLUS_SCRIPTS_URL . 'helloplus-header-fe.js',
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
		add_action( 'admin_init', function () {
			$action = sanitize_key( filter_input( INPUT_GET, 'action' ) );

			switch ( $action ) {
				case 'hello_plus_set_as_entire_site':
					$post = filter_input( INPUT_GET, 'post', FILTER_VALIDATE_INT );
					check_admin_referer( 'hello_plus_set_as_entire_site_' . $post );

					$redirect_to = filter_input( INPUT_GET, 'redirect_to', FILTER_SANITIZE_URL );

					$document = Utils::elementor()->documents->get( $post );
					$class_name = get_class( $document );
					$post_ids = $class_name::get_all_document_posts( [ 'posts_per_page' => -1 ] );

					foreach ( $post_ids as $post_id ) {
						wp_update_post( [
							'ID' => $post_id,
							'post_status' => 'draft',
						] );
					}

					wp_update_post( [
						'ID' => $post,
						'post_status' => 'publish',
					] );

					wp_safe_redirect( $redirect_to );

					exit;
				default:
					break;
			}
		} );
	}
}
