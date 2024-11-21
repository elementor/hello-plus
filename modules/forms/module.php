<?php

namespace HelloPlus\Modules\Forms;

use Elementor\Controls_Manager;

use HelloPlus\Includes\Module_Base;

use HelloPlus\Modules\Forms\Controls\Fields_Map;
use HelloPlus\Modules\Forms\Registrars\Form_Actions_Registrar;
use HelloPlus\Modules\Forms\Registrars\Form_Fields_Registrar;
use HelloPlus\Modules\Forms\Submissions\Component as Form_Submissions_Component;
use HelloPlus\Modules\Forms\Controls\Fields_Repeater;
use HelloPlus\Modules\Forms\Submissions\AdminMenuItems\Submissions_Promotion_Menu_Item;

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
			'Form',
		];
	}

	/**
	 * Get the base URL for assets.
	 *
	 * @return string
	 */
	public function get_assets_base_url(): string {
		return HELLO_PLUS_URL;
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
		wp_enqueue_style(
			'hello-plus-forms',
			HELLO_PLUS_STYLE_URL . 'hello-plus-forms.css',
			[ 'elementor-frontend' ],
			HELLO_PLUS_VERSION
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
			'hello-plus-forms-editor',
			HELLO_PLUS_SCRIPTS_URL . 'hello-plus-forms-editor.js',
			[ 'elementor-editor', 'wp-i18n' ],
			HELLO_PLUS_VERSION,
			true
		);
	}

	public function register_scripts() {
		wp_enqueue_script(
			'hello-plus-forms-editor-fe',
			HELLO_PLUS_SCRIPTS_URL . 'hello-plus-forms-editor-fe.js',
			[ 'elementor-common', 'elementor-frontend-modules', 'elementor-frontend' ],
			HELLO_PLUS_VERSION,
			true
		);
	}

	/**
	 * Module constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );

		// Initialize registrars.
		$this->actions_registrar = new Form_Actions_Registrar();
		$this->fields_registrar = new Form_Fields_Registrar();

		// Ajax Handler
		if ( Classes\Ajax_Handler::is_form_submitted() ) {
			$this->add_component( 'ajax_handler', new Classes\Ajax_Handler() );

			/**
			 * Hello+ form submitted.
			 *
			 * Fires when the form is submitted. This hook allows developers
			 * to add functionality after form submission.
			 *
			 * @param Module $this An instance of the form module.
			 *
			 * @since 1.0.0
			 *
			 */
			do_action( 'hello_plus/forms/form_submitted', $this );
		}
	}

	protected function get_component_ids(): array {
		return [];
	}
}
