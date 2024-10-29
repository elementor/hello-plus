<?php

namespace HelloPlus\Modules\Forms\Registrars;

use HelloPlus\Core\Utils\Registrar;
use HelloPlus\Modules\Forms\Actions;
use HelloPlus\Plugin;
use HelloPlus\License\API;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Basic form actions registration manager.
 */
class Form_Actions_Registrar extends Registrar {

	const FEATURE_NAME_CLASS_NAME_MAP = [
		'email' => 'Email',
		'email2' => 'Email2',
		'redirect' => 'Redirect',
	];

	/**
	 * Form_Actions_Registrar constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->init();
	}

	/**
	 * Initialize the default fields.
	 *
	 * @return void
	 */
	public function init() {
		// Register the actions handlers using a hook since some actions need to be registered before those actions (e.g: save-to-database).
		add_action( 'elementor_pro/forms/actions/register', function ( Form_Actions_Registrar $actions_registrar ) {
			$form_actions = API::filter_active_features( static::FEATURE_NAME_CLASS_NAME_MAP );

			foreach ( $form_actions as $action ) {
				$class_name = 'HelloPlus\Modules\Forms\Actions\\' . $action;
				$actions_registrar->register( new $class_name() );
			}
		} );

		/**
		 * Deprecated actions registration hook.
		 *
		 * @deprecated 3.5.0 Use `elementor_pro/forms/actions/register` instead.
		 */
		Plugin::elementor()->modules_manager->get_modules( 'dev-tools' )->deprecation->do_deprecated_action(
			'elementor_pro/forms/register_action',
			[ $this ],
			'3.5.0',
			'elementor_pro/forms/actions/register'
		);

		/**
		 * Elementor Pro form actions registration.
		 *
		 * Fires when a new form action is registered. This hook allows developers to
		 * register new form actions.
		 *
		 * @since 3.5.0
		 *
		 * @param Form_Actions_Registrar $this An instance of form actions registration
		 *                                     manager.
		 */
		do_action( 'elementor_pro/forms/actions/register', $this );

		// MailPoet
		if ( class_exists( '\WYSIJA' ) ) {
			$this->register( new Actions\Mailpoet() );
		}

		// MailPoet
		if ( class_exists( '\MailPoet\API\API' ) ) {
			$this->register( new Actions\Mailpoet3() );
		}
	}
}
