<?php

namespace HelloPlus\Modules\Forms\Registrars;

use HelloPlus\Core\Utils\Registrar;
use HelloPlus\Modules\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Basic form fields registration manager.
 */
class Form_Fields_Registrar extends Registrar {

	/**
	 * Form_Fields_Registrar constructor.
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
		$this->register( new Fields\Time() );
		$this->register( new Fields\Date() );
		$this->register( new Fields\Tel() );
		$this->register( new Fields\Number() );
		$this->register( new Fields\Acceptance() );

		/**
		 * Elementor Pro form fields registration.
		 *
		 * Fires when a new form field is registered. This hook allows developers to
		 * register new form fields.
		 *
		 * @since 3.5.0
		 *
		 * @param Form_Actions_Registrar $this An instance of form fields registration
		 *                                     manager.
		 */
		do_action( 'elementor_pro/forms/fields/register', $this );
	}
}
