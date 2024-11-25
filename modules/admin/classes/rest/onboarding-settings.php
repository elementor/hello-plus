<?php

namespace HelloPlus\Modules\Admin\Classes\Rest;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\App\Modules\KitLibrary\Connect\Kit_Library;
use HelloPlus\Includes\Utils;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Setup_Wizard;
use WP_REST_Server;

class Onboarding_Settings {
	protected array $kits_ids = [];

	public function rest_api_init() {
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

	// TODO: implement using new kits api.
	public function get_kits() {
		$kits = get_transient( 'e_hello_plus_kits' );

		if ( ! $kits ) {
			$kits = [];
			if ( class_exists( 'Elementor\App\Modules\KitLibrary\Connect\Kit_Library' ) ) {
				try {
					foreach ( $this->kits_ids as $kit_id ) {
						$kit = $this->call_and_check( Kit_Library::DEFAULT_BASE_ENDPOINT . '/kits/' . $kit_id );
						$kit['manifest'] = $this->call_and_check( Kit_Library::DEFAULT_BASE_ENDPOINT . '/kits/' . $kit_id . '/manifest' );
						$kits[] = $kit;
					}

					set_transient( 'e_hello_plus_kits', $kits, 24 * HOUR_IN_SECONDS );
				} catch ( \Exception $e ) {
					$kits = [];
				}
			}
		}

		return $kits;
	}

	public function call_and_check( $url ) {
		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			if ( strpos( $url, Kit_Library::DEFAULT_BASE_ENDPOINT ) === 0 ) {
				return $this->call_and_check(
					str_replace( Kit_Library::DEFAULT_BASE_ENDPOINT, Kit_Library::FALLBACK_BASE_ENDPOINT, $url )
				);
			}

			throw new \Exception( esc_html( "Error when calling $url: message {$response->get_error_message()}" ) );
		}

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			if ( strpos( $url, Kit_Library::DEFAULT_BASE_ENDPOINT ) === 0 ) {
				return $this->call_and_check(
					str_replace( Kit_Library::DEFAULT_BASE_ENDPOINT, Kit_Library::FALLBACK_BASE_ENDPOINT, $url )
				);
			}

			throw new \Exception( esc_html( "Error when calling $url: response code $response_code" ) );
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
					'modalCloseRedirectUrl' => admin_url( 'admin.php?page=' . Utils::get_theme_slug() ),
					'kits' => $this->get_kits(),
					'applyKitBaseUrl' => admin_url( 'admin.php?page=elementor-app' ),
					'wizardCompleted' => Setup_Wizard::has_site_wizard_been_completed(),
					'returnUrl' => admin_url( 'admin.php?page=hello-plus-setup-wizard' ),
				],
			]
		);
	}

	public function __construct() {
		$this->kits_ids = apply_filters( 'hello-plus/kits/ids', [
			'673da3942580a31cc679737b',
			'67446ce1bda2e2012a73bbcc',
			'67434de5bb2d4185550be7ab',
			'67446d1dbda2e2012a73bbdd',
			'67446d3d7f31e7099cc64eae',
			'67446d627f817bb8debfa451',
			'67434559a5a0ed6d7a3fd0ac',
			'67446da5bda2e2012a73bbf3',
			'67446dc87f817bb8debfa469',
		] );

		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}
}
