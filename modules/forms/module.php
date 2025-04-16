<?php

namespace HelloPlus\Modules\Forms;

use Elementor\Controls_Manager;
use HelloPlus\Includes\Module_Base;
use HelloPlus\Modules\Forms\components\Ajax_Handler;
use HelloPlus\Modules\Forms\Controls\Fields_Map;
use HelloPlus\Modules\Forms\Controls\Fields_Repeater;
use HelloPlus\Modules\Forms\Registrars\Form_Actions_Registrar;
use HelloPlus\Modules\Forms\Registrars\Form_Fields_Registrar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends Module_Base {
	/**
	 * @var Form_Actions_Registrar
	 */
	public $actions_registrar;

	/**
	 * @var Form_Fields_Registrar
	 */
	public $fields_registrar;


	public static function get_name(): string {
		return 'forms';
	}

	protected function get_widget_ids(): array {
		return [
			'Ehp_Form',
		];
	}

	/**
	 * Get the base URL for assets.
	 *
	 * @return string
	 */
	public function get_assets_base_url(): string {
		return HELLOPLUS_URL;
	}

	/**
	 * Register styles.
	 *
	 * At build time, Elementor compiles `/modules/forms/assets/scss/frontend.scss`
	 * to `/assets/css/widget-forms.min.css`.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'helloplus-forms',
			HELLOPLUS_STYLE_URL . 'helloplus-forms.css',
			[ 'elementor-frontend' ],
			HELLOPLUS_VERSION
		);
	}

	public static function find_element_recursive( $elements, $form_id ) {
		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = self::find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	public function register_controls( Controls_Manager $controls_manager ) {
		$controls_manager->register( new Fields_Repeater() );
		$controls_manager->register( new Fields_Map() );
	}

	public function enqueue_editor_scripts() {
		wp_enqueue_script(
			'helloplus-forms-editor',
			HELLOPLUS_SCRIPTS_URL . 'helloplus-forms-editor.js',
			[ 'elementor-editor', 'wp-i18n' ],
			HELLOPLUS_VERSION,
			true
		);

		$promotion_data = [
			'title'        => __( 'Collect Submissions', 'hello-plus' ),
			'description'  => [ __( 'Unlock form submissions by upgrading to Elementor Pro on an eligible plan.', 'hello-plus' ) ],
			'upgrade_text' => __( 'Upgrade', 'hello-plus' ),
			'upgrade_url'  => 'https://go.elementor.com/biz-form-submissions',
			'image'        => HELLOPLUS_IMAGES_URL . 'collect-submission.jpg',
			'image_alt'    => __( 'Upgrade', 'hello-plus' ),
		];

		wp_localize_script(
			'helloplus-forms-editor',
			'ehpFormsPromotionData',
			$promotion_data
		);

		wp_set_script_translations( 'helloplus-forms-editor', 'hello-plus' );
	}

	public function register_scripts() {
		wp_register_script(
			'helloplus-forms-fe',
			HELLOPLUS_SCRIPTS_URL . 'helloplus-forms-fe.js',
			[ 'elementor-common', 'elementor-frontend-modules', 'elementor-frontend' ],
			HELLOPLUS_VERSION,
			true
		);

		wp_localize_script(
			'helloplus-forms-fe',
			'ehpFormsData',
			[
				'nonce' => wp_create_nonce( Ajax_Handler::NONCE_ACTION ),
			]
		);
	}

	protected function get_component_ids(): array {
		return [ 'Ajax_Handler' ];
	}

	public static function get_site_domain() {
		return str_ireplace( 'www.', '', wp_parse_url( home_url(), PHP_URL_HOST ) );
	}

	protected function register_hooks(): void {
		parent::register_hooks();

		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
	}

	/**
	 * Module constructor.
	 */
	public function __construct() {
		parent::__construct();

		// Initialize registrars.
		$this->actions_registrar = new Form_Actions_Registrar();
		$this->fields_registrar = new Form_Fields_Registrar();
	}
}
