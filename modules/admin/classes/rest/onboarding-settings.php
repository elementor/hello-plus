<?php

namespace HelloPlus\Modules\Admin\Classes\Rest;

use HelloPlus\Includes\Utils;
use WP_REST_Server;

class Onboarding_Settings {
	const DEFAULT_BASE_ENDPOINT = 'https://my.elementor.com/api/v1/kits-library/kits/';
	const FALLBACK_BASE_ENDPOINT = 'https://ms-8874.elementor.com/api/v1/kits-library/kits/';

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
			try {
				$kits = $this->call_and_check( self::DEFAULT_BASE_ENDPOINT );
				$kits = array_slice( $kits, - 8 );
				foreach ( $kits as &$kit) {
					$kit['manifest'] = $this->call_and_check( self::DEFAULT_BASE_ENDPOINT . $kit['_id'] . '/manifest' );
				}

				set_transient( 'e_hello_plus_kits', $kits, 24 * HOUR_IN_SECONDS );
			} catch ( \Exception $e ) {
				// do nothing
			}
		}

		return $kits;
	}

	public function call_and_check( $url ) {
		$response = wp_remote_get( $url );
		if ( is_wp_error( $response ) ) {
			throw new \Exception( "Error when calling $url: message {$response->get_error_message()}" );
		}
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			throw new \Exception( "Unexpected response code, expecting 200, got $response_code" );
		}

		$response_body = wp_remote_retrieve_body( $response );
		return json_decode( $response_body, true );
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

	public function get_connect_data(  ) {
		/** @var ConnectModule $connect */
		$connect = Plugin::$instance->common->get_component( 'connect' );

		/** @var Kit_Library $kit_library */
		$kit_library = $connect->get_app( 'kit-library' );

		Plugin::$instance->app->set_settings( 'kit-library', [
			'has_access_to_module' => current_user_can( 'administrator' ),
			'subscription_plans' => $this->apply_filter_subscription_plans( $connect->get_subscription_plans( 'kit-library' ) ),
			'is_pro' => false,
			'is_library_connected' => $kit_library->is_connected(),
			'library_connect_url'  => $kit_library->get_admin_url( 'authorize', [
				'utm_source' => 'kit-library',
				'utm_medium' => 'wp-dash',
				'utm_campaign' => 'library-connect',
				'utm_term' => '%%page%%', // Will be replaced in the frontend.
			] ),
			'access_level' => ConnectModule::ACCESS_LEVEL_CORE,
			'access_tier' => ConnectModule::ACCESS_TIER_FREE,
			'app_url' => Plugin::$instance->app->get_base_url() . '#/' . $this->get_name(),
		] );
	}
}
