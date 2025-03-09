<?php

namespace HelloPlus\Modules\Forms\Registrars;

use HelloPlus\Includes\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Basic form actions registration manager.
 */
class Form_Actions_Registrar extends Registrar {

	const FEATURE_NAME_CLASS_NAME_MAP = [
		'email' => 'Email',
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
		add_action( 'elementor/init', function () {
			if (
				Utils::are_submissions_enabled()
			) {
				$this->register( new \ElementorPro\Modules\Forms\Submissions\Actions\Save_To_Database() );
			}

			foreach ( static::FEATURE_NAME_CLASS_NAME_MAP as $action ) {
				$class_name = 'HelloPlus\Modules\Forms\Actions\\' . $action;
				$this->register( new $class_name() );
			}
		} );
	}
}
