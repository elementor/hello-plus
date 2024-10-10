<?php

namespace HelloPlus\Modules\Admin\Classes\Rest;

use HelloPlus\Includes\Utils;
use WP_REST_Server;

class Onboarding_Settings {

	const KITS_ENDPOINT = 'https://ms-8874.elementor.com/api/v1/kits-library/kits';

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

	public function get_kits() {
		$kits = get_transient( 'e_hello_plus_kits' );

		if ( ! $kits ) {
			$kits = wp_remote_get( self::KITS_ENDPOINT );
			$kits = wp_remote_retrieve_body( $kits );
			$kits = json_decode( $kits, true );
			$kits = array_slice( $kits, 0, 8 );
			set_transient( 'e_hello_plus_kits', $kits, 24 * HOUR_IN_SECONDS );
		}

		return $kits;
	}

	public function get_onboarding_settings() {
		$nonce = wp_create_nonce( 'updates' );

		return rest_ensure_response(
			[
				'settings' => [
					'nonce' => $nonce,
					'elementorInstalled' => Utils::is_elementor_installed(),
					'elementorActive' => Utils::is_elementor_active(),
					'modalCloseRedirectUrl' => admin_url( 'admin.php?page=hello-plus' ),
					'kits' => $this->get_kits(),
				],
			]
		);
	}
}
