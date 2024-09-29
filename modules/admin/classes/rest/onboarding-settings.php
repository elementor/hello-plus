<?php

namespace HelloPlus\Modules\Admin\Classes\Rest;

use HelloPlus\Modules\Admin\Classes\Elementor\Elementor_Helper;
use WP_REST_Server;

class Onboarding_Settings {

	public function __construct() {

		add_action(
			'rest_api_init',
			function () {

				register_rest_route(
					'elementor-hello-plus/v1',
					'/onboarding-settings',
					[
						'methods' => WP_REST_Server::READABLE,
						'callback' => [ $this, 'get_onboarding_settings' ],
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
					]
				);
			}
		);
	}

	public function get_onboarding_settings() {
		$nonce = wp_create_nonce( 'updates' );

		return rest_ensure_response(
			[
				'settings' => [
					'nonce' => $nonce,
					'elementorInstalled' => Elementor_Helper::is_elementor_installed(),
					'elementorActive' => Elementor_Helper::is_elementor_active(),
				],
			]
		);
	}
}
