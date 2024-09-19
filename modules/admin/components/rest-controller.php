<?php

namespace HelloPlus\Modules\Admin\Components;

use HelloPlus\Modules\Admin\Classes\Rest\Get_Promotions;

class Rest_Controller {

	protected $endpoints = [];

	public function __construct() {
		$this->endpoints['promotions'] = new Get_Promotions();
	}
}
