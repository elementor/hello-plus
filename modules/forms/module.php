<?php
namespace HelloPlus\Modules\Forms;

use Elementor\Controls_Manager;
use Elementor\Settings;
use Elementor\Core\Admin\Admin_Notices;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;

use HelloPlus\Includes\Module_Base;

use Elementor\User;
use HelloPlus\Modules\Forms\Controls\Fields_Map;
use HelloPlus\Modules\Forms\Registrars\Form_Actions_Registrar;
use HelloPlus\Modules\Forms\Registrars\Form_Fields_Registrar;
use HelloPlus\Modules\Forms\Submissions\Component as Form_Submissions_Component;
use HelloPlus\Modules\Forms\Controls\Fields_Repeater;
use HelloPlus\Plugin;
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

	const ACTIVITY_LOG_LICENSE_FEATURE_NAME = 'activity-log';
	const CF7DB_LICENSE_FEATURE_NAME = 'cf7db';
	const AKISMET_LICENSE_FEATURE_NAME = 'akismet';

	const WIDGET_NAME_CLASS_NAME_MAP = [
		'form' => 'Form',
		'login' => 'Login',
	];

	public static function get_name(): string {
		return 'forms';
	}

	public function get_widgets() {
		return WIDGET_NAME_CLASS_NAME_MAP;
	}

	/**
	 * Get the base URL for assets.
	 *
	 * @return string
	 */
	public function get_assets_base_url(): string {
		return ELEMENTOR_PRO_URL;
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
		$widget_styles = $this->get_widgets_style_list();

	}

	private function get_widgets_style_list(): array {
		return [
			'widget-form',
			'widget-login',
		];
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

	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function forms_panel_action_data( array $data ) {
		$document = Utils::_unstable_get_document_for_edit( $data['editor_post_id'] );

		if ( empty( $data['service'] ) ) {
			throw new \Exception( 'Service required.' );
		}

		/** @var \HelloPlus\Modules\Forms\Classes\Integration_Base $integration */
		$integration = $this->actions_registrar->get( $data['service'] );

		if ( ! $integration ) {
			throw new \Exception( 'Action not found.' );
		}

		return $integration->handle_panel_request( $data );
	}

	/**
	 * @deprecated 3.5.0 Use `fields_registrar->register()` instead.
	 */
	public function add_form_field_type( $type, $instance ) {
		Plugin::elementor()->modules_manager->get_modules( 'dev-tools' )->deprecation->deprecated_function(
			__METHOD__,
			'3.5.0',
			'fields_registrar->register()'
		);

		$this->fields_registrar->register( $instance, $type );
	}

	/**
	 * @deprecated 3.5.0 Use `actions_registrar->register()` instead.
	 */
	public function add_form_action( $id, $instance ) {
		Plugin::elementor()->modules_manager->get_modules( 'dev-tools' )->deprecation->deprecated_function(
			__METHOD__,
			'3.5.0',
			'actions_registrar->register()'
		);

		$this->actions_registrar->register( $instance, $id );
	}

	/**
	 * @deprecated 3.5.0 Use `actions_registrar->get()` instead.
	 */
	public function get_form_actions( $id = null ) {
		Plugin::elementor()->modules_manager->get_modules( 'dev-tools' )->deprecation->deprecated_function(
			__METHOD__,
			'3.5.0',
			'actions_registrar->get()'
		);

		return $this->actions_registrar->get( $id );
	}

	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'pro_forms_panel_action_data', [ $this, 'forms_panel_action_data' ] );
	}

	/**
	 * Register submissions
	 */
	private function register_submissions_component() {
		$name = Form_Submissions_Component::NAME;

		if ( is_admin() ) {
			add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_submissions_admin_fields' ] );
		}

		if ( '1' === get_option( 'elementor_' . $name ) ) {
			return;
		}

		if ( current_user_can( 'manage_options' ) ) {
			add_action( 'admin_notices', [ $this, 'register_submissions_admin_notice' ] );
		}

		$this->add_component( $name, new Form_Submissions_Component() );
	}

	public function register_submissions_admin_fields( Settings $settings ) {
		$settings->add_field(
			Settings::TAB_ADVANCED,
			Settings::TAB_ADVANCED,
			Form_Submissions_Component::NAME,
			[
				'label' => esc_html__( 'Form Submissions', 'elementor-pro' ),
				'field_args' => [
					'type' => 'select',
					'std' => '',
					'options' => [
						'' => esc_html__( 'Enable', 'elementor-pro' ),
						'1' => esc_html__( 'Disable', 'elementor-pro' ),
					],
					'desc' => esc_html__( 'Never lose another submission! Using “Actions After Submit” you can now choose to save all submissions to an internal database.', 'elementor-pro' ),
				],
			],
		);
	}

	public function register_submissions_admin_notice() {
		$notice_id = 'elementor-pro-forms-submissions';
		if ( User::is_user_notice_viewed( $notice_id ) ) {
			return;
		}

		$is_new_site = Upgrade_Manager::install_compare( '3.25.0', '>=' );
		if ( $is_new_site ) {
			return;
		}

		/**
		 * @var Admin_Notices $admin_notices
		 */
		$admin_notices = Plugin::elementor()->admin->get_component( 'admin-notices' );

		$dismiss_url = add_query_arg( [
			'action' => 'elementor_set_admin_notice_viewed',
			'notice_id' => esc_attr( $notice_id ),
		], admin_url( 'admin-post.php' ) );

		$admin_notices->print_admin_notice( [
			'id' => $notice_id,
			'title' => esc_html__( 'Form Submissions now activated by default on all websites', 'elementor-pro' ),
			'description' => sprintf(
				esc_html__( 'The Form Submissions feature, previously located under the Features tab in Elementor, is now enabled by default on all websites. If you prefer to disable this feature, you can do so by navigating to %1$sSettings → Advanced%2$s.', 'elementor-pro' ),
				'<a href="' . esc_url( admin_url( 'admin.php?page=elementor-settings#tab-' . Settings::TAB_ADVANCED ) ) . '"><strong>',
				'</strong></a>'
			),
			'button' => [
				'text' => esc_html__( 'Got it! Keep it enabled', 'elementor-pro' ),
				'classes' => [ 'e-notice-dismiss' ],
				'url' => esc_url_raw( $dismiss_url ),
				'type' => 'cta',
			],
		] );
	}

	/**
	 * Module constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );

		$this->add_component( 'recaptcha', new Classes\Recaptcha_Handler() );
		$this->add_component( 'recaptcha_v3', new Classes\Recaptcha_V3_Handler() );

		// Initialize registrars.
		$this->actions_registrar = new Form_Actions_Registrar();
		$this->fields_registrar = new Form_Fields_Registrar();

		// Ajax Handler
		if ( Classes\Ajax_Handler::is_form_submitted() ) {
			$this->add_component( 'ajax_handler', new Classes\Ajax_Handler() );

			/**
			 * Elementor form submitted.
			 *
			 * Fires when the form is submitted. This hook allows developers
			 * to add functionality after form submission.
			 *
			 * @since 2.0.0
			 *
			 * @param Module $this An instance of the form module.
			 */
			do_action( 'elementor_pro/forms/form_submitted', $this );
		}
	}

	protected function get_component_ids(): array {
		return [];
	}
}