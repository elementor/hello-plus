<?php

namespace HelloPlus\Modules\Admin\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Modules\Admin\Classes\Ajax\Setup_Wizard;
use HelloPlus\Modules\Admin\Classes\Rest\Onboarding_Settings;


class Api_Controller {

	protected $endpoints = [];

	protected $ajax_classes = [];

	public function __construct() {
		$this->endpoints['onboarding-settings'] = new Onboarding_Settings();

		$this->ajax_classes['setup-wizard'] = new Setup_Wizard();
	}
}
