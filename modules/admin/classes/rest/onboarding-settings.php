<?php

namespace HelloPlus\Modules\Admin\Classes\Rest;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\App\Modules\KitLibrary\Connect\Kit_Library;
use HelloPlus\Includes\Utils;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Setup_Wizard;

class Onboarding_Settings {
	const KITS_TRANSIENT = 'helloplus_elementor_kits';

	public function rest_api_init() {
		register_rest_route(
			'elementor-hello-plus/v1',
			'/onboarding-settings',
			[
				'methods' => \WP_REST_Server::READABLE,
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

			$sorted_kits = $this->sort_kits_by_index( $kits, 'updated_at' );

			foreach ( $sorted_kits as $index => $kit ) {
				$sorted_kits[ $index ]['manifest'] = $this->call_and_check(
					Kit_Library::DEFAULT_BASE_ENDPOINT . '/kits/' . $kit['_id'] . '/manifest'
				);
			}

			set_transient( self::KITS_TRANSIENT, $sorted_kits, HOUR_IN_SECONDS );
		} catch ( \Exception $e ) {
			return [];
		}

		return $sorted_kits;
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

		/**
		 * Filters the labels for the Get Started page in the Setup Wizard.
		 *
		 * @since 1.6.2
		 *
		 * @param array $labels The labels and buttons text for the Get Started page in the Setup Wizard.
		 */
		$get_started_text = apply_filters(
			'hello-plus/onboarding/get-started-text',
			[
				'title' => __( 'Welcome! Let\'s create your website.', 'hello-plus' ),
				'description' => __( 'Thanks for installing the Hello Biz theme by Elementor. This setup wizard will help you create a website in moments.', 'hello-plus' ),
				'disclaimer' => __( 'By clicking "Start building my website", I agree to install & activate the Elementor plugin. I accept the Elementor', 'hello-plus' ),
				'termsUrl' => 'https://elementor.com/terms/',
				'termsText' => __( 'Terms and Conditions', 'hello-plus' ),
				'buttonText' => __( 'Start building my website', 'hello-plus' ),
			]
		);

		/**
		 * Filters the labels for the Ready to Go page in the Setup Wizard.
		 *
		 * @since 1.6.2
		 *
		 * @param array $labels The labels and buttons text for the Ready to Go page in the Setup Wizard.
		 */
		$ready_to_go_text = apply_filters(
			'hello-plus/onboarding/ready-to-go-text',
			[
				'title' => __( 'Congratulations, you have created your website!', 'hello-plus' ),
				'description' => __( 'It\'s time to make it yours—add your content, style, and personal touch.', 'hello-plus' ),
				'viewSite' => __( 'View my site', 'hello-plus' ),
				'customizeSite' => __( 'Customize my site', 'hello-plus' ),
			]
		);

		/**
		 * Filters the labels for the Install Kit page in the Setup Wizard.
		 *
		 * @since 1.6.2
		 *
		 * @param array $labels The labels and buttons text for the Install Kit page in the Setup Wizard.
		 */
		$install_kit_text = apply_filters(
			'hello-plus/onboarding/install-kit-text',
			[
				'title' => __( 'Choose your website template kit', 'hello-plus' ),
				'description' => __( 'Explore our versatile website kits to find one that fits your style or project.', 'hello-plus' ),
			]
		);

		return rest_ensure_response(
			[
				'settings' => [
					'nonce' => $nonce,
					'elementorInstalled' => Utils::is_elementor_installed(),
					'elementorActive' => Utils::is_elementor_active(),
					'modalCloseRedirectUrl' => self_admin_url( 'admin.php?page=' . Utils::get_theme_slug() ),
					'kits' => $this->filter_kits_by_theme( $this->get_kits() ),
					'applyKitBaseUrl' => self_admin_url( 'admin.php?page=elementor-app' ),
					'wizardCompleted' => Setup_Wizard::has_site_wizard_been_completed(),
					'returnUrl' => self_admin_url( 'admin.php?page=hello-plus-setup-wizard' ),
					'getStartedText' => $get_started_text,
					'readyToGoText' => $ready_to_go_text,
					'installKitText' => $install_kit_text,
				],
			]
		);
	}

	public function filter_kits_by_theme( array $kits ): array {

		$theme_slug = Utils::get_theme_slug();
		return array_filter( $kits, function ( $kit ) use ( $theme_slug ) {
			if ( empty( $kit['taxonomies'] ) || ! is_array( $kit['taxonomies'] ) ) {
				return false;
			}

			foreach ( $kit['taxonomies'] as $category ) {
				$cat_name = $category['name'] ?? '';
				$cat_type = $category['type'] ?? '';

				if ( $cat_name === $theme_slug && 'third_category' === $cat_type ) {
					return true;
				}
			}

			return false;
		} );
	}

	private function sort_kits_by_index( array $kits, string $sort_by = 'featured_index' ): array {
		if ( empty( $kits[0][ $sort_by ] ) ) {
			$sort_by = 'featured_index';
		}

		$sort_by_time = false;

		if ( in_array( $sort_by, [ 'created_at', 'updated_at', 'created' ], true ) ) {
			$sort_by_time = true;
		}

		usort($kits, function ( $kit_1, $kit_2 ) use ( $sort_by, $sort_by_time ) {
			if ( $sort_by_time ) {
				return \strtotime( $kit_2[ $sort_by ] ) <=> \strtotime( $kit_1[ $sort_by ] );
			}

			return $kit_1[ $sort_by ] <=> $kit_2[ $sort_by ];
		} );

		return $kits;
	}

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}
}
