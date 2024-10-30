<?php

namespace HelloPlus\Modules\Admin\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Modules\Admin\Classes\Ajax\Setup_Wizard;
use HelloPlus\Modules\Admin\Classes\Rest\Onboarding_Settings;


class Api_Controller {

	protected static $endpoints = [];

	protected static $ajax_classes = [];

	public function __construct() {
		self::$endpoints['onboarding-settings'] = new Onboarding_Settings();

		self::$ajax_classes['setup-wizard'] = new Setup_Wizard();
	}

	public static function get_endpoint( string $endpoint ) {
		if ( ! isset( self::$endpoints[ $endpoint ] ) ) {
			throw new \Exception( esc_html__( 'Endpoint not found', 'hello-plus' ) );
		}
		return self::$endpoints[ $endpoint ];
	}
}
