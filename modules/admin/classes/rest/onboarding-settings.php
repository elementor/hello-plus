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
	const KITS_TRANSIENT = 'helloplus_elementor_kits';

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

	public function get_kits() {
		$kits = get_transient( self::KITS_TRANSIENT );

		if ( ! empty( $kits ) ) {
			return $kits;
		}

		if ( ! class_exists( 'Elementor\App\Modules\KitLibrary\Connect\Kit_Library' ) ) {
			return [];
		}

		$args = [
			'products' => 'ehp',
			'visibility' => 'restricted',
			'editor_layout_type' => 'container_flexbox',
		];

		/**
		 * Filter the arguments used to fetch the Hello+ kits.
		 *
		 * @param array $args default arguments.
		 */
		$args = apply_filters( 'hello-plus/onboarding/kits-args', $args );

		$endpoint_url = add_query_arg( $args, Kit_Library::DEFAULT_BASE_ENDPOINT . '/kits' );
		try {
			$kits = $this->call_and_check( $endpoint_url );

			foreach ( $kits as $index => $kit ) {
				$kits[ $index ]['manifest'] = $this->call_and_check(
					Kit_Library::DEFAULT_BASE_ENDPOINT . '/kits/' . $kit['_id'] . '/manifest'
				);
			}

			set_transient( self::KITS_TRANSIENT, $kits, 24 * HOUR_IN_SECONDS );
		} catch ( \Exception $e ) {
			return [];
		}

		return $kits;
	}

	/**
	 * @param string $url
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function call_and_check( string $url ) {
		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			if ( 0 === strpos( $url, Kit_Library::DEFAULT_BASE_ENDPOINT ) ) {
				return $this->call_and_check(
					str_replace( Kit_Library::DEFAULT_BASE_ENDPOINT, Kit_Library::FALLBACK_BASE_ENDPOINT, $url )
				);
			}

			throw new \Exception( esc_html( "Error when calling $url: message {$response->get_error_message()}" ) );
		}

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			if ( 0 === strpos( $url, Kit_Library::DEFAULT_BASE_ENDPOINT ) ) {
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
					'modalCloseRedirectUrl' => self_admin_url( 'admin.php?page=' . Utils::get_theme_slug() ),
					'kits' => $this->get_kits(),
					'applyKitBaseUrl' => self_admin_url( 'admin.php?page=elementor-app' ),
					'wizardCompleted' => Setup_Wizard::has_site_wizard_been_completed(),
					'returnUrl' => self_admin_url( 'admin.php?page=hello-plus-setup-wizard' ),
				],
			]
		);
	}

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}
}
