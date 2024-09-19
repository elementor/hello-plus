<?php

namespace HelloPlus\Modules\Admin\Components;

use HelloPlus\Modules\Admin\Classes\Rest\Admin_Config;
use HelloPlus\Modules\Admin\Classes\Rest\Promotions;

class Rest_Controller {

	protected $endpoints = [];

	public function __construct() {
		$this->endpoints['promotions'] = new Promotions();
		$this->endpoints['admin-config'] = new Admin_Config();
	}
}
