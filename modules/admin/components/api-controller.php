<?php

namespace HelloPlus\Modules\Admin\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Modules\Admin\Classes\Ajax\Setup_Wizard;
use HelloPlus\Modules\Admin\Classes\Rest\Onboarding_Settings;
use HelloPlus\Modules\Admin\Classes\Rest\Set_Editor_Visited;

class Api_Controller {

	protected $endpoints = [];

	protected $ajax_classes = [];

	public function get_endpoint( string $endpoint ) {
		if ( ! isset( $this->endpoints[ $endpoint ] ) ) {
			throw new \Exception( esc_html__( 'Endpoint not found', 'hello-plus' ) );
		}

		return $this->endpoints[ $endpoint ];
	}

	public function __construct() {
		$this->endpoints['onboarding-settings'] = new Onboarding_Settings();
		$this->endpoints['set-editor-visited'] = new Set_Editor_Visited();

		$this->ajax_classes['setup-wizard'] = new Setup_Wizard();
	}
}
