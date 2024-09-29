<?php

namespace HelloPlus\Modules\Admin\Components;

use HelloPlus\Modules\Admin\Classes\Ajax\Setup_Wizard;
use HelloPlus\Modules\Admin\Classes\Rest\Admin_Config;
use HelloPlus\Modules\Admin\Classes\Rest\Onboarding_Settings;
use HelloPlus\Modules\Admin\Classes\Rest\Promotions;


class Rest_Controller {

	protected $endpoints = [];

	protected $ajax_classes = [];

	public function __construct() {
		$this->endpoints['promotions'] = new Promotions();
		$this->endpoints['admin-config'] = new Admin_Config();
		$this->endpoints['onboarding-settings'] = new Onboarding_Settings();

		$this->ajax_classes['setup-wizard'] = new Setup_Wizard();
	}
}
