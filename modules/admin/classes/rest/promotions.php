<?php

namespace HelloPlus\Modules\Admin\Classes\Rest;

use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Promotions {

	protected $plugins = [];

	public function __construct() {
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'elementor-hello-plus/v1',
					'/promotions',
					[
						'methods' => WP_REST_Server::READABLE,
						'callback' => [ $this, 'get_promotions' ],
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
					]
				);
			}
		);

		add_action( 'plugin_loaded', function () {
			$this->plugins = get_plugins();
		} );
	}

	public function get_promotions() {
		$plugins = $this->plugins;

		$action_links_data = [];

		if ( ! isset( $plugins['elementor/elementor.php'] ) ) {
			$action_links_data[] = [
				'type' => 'install-elementor',
				'url' => wp_nonce_url(
					add_query_arg(
						[
							'action' => 'install-plugin',
							'plugin' => 'elementor',
						],
						admin_url( 'update.php' )
					),
					'install-plugin_elementor'
				),
				'alt' => __( 'Elementor', 'hello-plus' ),
				'title' => __( 'Install Elementor', 'hello-plus' ),
				'message' => __( 'Create cross-site header & footer using Elementor.', 'hello-plus' ),
				'button' => __( 'Install Elementor', 'hello-plus' ),
				'image' => HELLO_PLUS_URL . '/assets/images/elementor.svg',
			];
		}

		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			$action_links_data[] = [
				'type' => 'activate-elementor',
				'image' => HELLO_PLUS_URL . '/assets/images/elementor.svg',
				'alt' => __( 'Elementor', 'hello-plus' ),
				'title' => __( 'Activate Elementor', 'hello-plus' ),
				'message' => __( 'Create cross-site header & footer using Elementor.', 'hello-plus' ),
				'button' => __( 'Install Elementor', 'hello-plus' ),
				'url' => wp_nonce_url( 'plugins.php?action=activate&plugin=elementor/elementor.php', 'activate-plugin_elementor/elementor.php' ),
			];
		}

		if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$action_links_data[] = [
				'type' => 'go-pro',
				'image' => HELLO_PLUS_URL . '/assets/images/elementor.svg',
				'url' => 'https://elementor.com/pricing-plugin',
				'alt' => __( 'Elementor Pro', 'hello-plus' ),
				'title' => __( 'Go Pro', 'hello-plus' ),
				'message' => __( 'Unleash Elementor Pro to boost your site.', 'hello-plus' ),
				'button' => __( 'Yes!', 'hello-plus' ),
			];
		}

		if ( ! defined( 'ELEMENTOR_AI_VERSION' ) ) {
			$action_links_data[] = [
				'type' => 'go-ai',
				'image' => HELLO_PLUS_URL . '/assets/images/elementor.svg',
				'url' => 'https://elementor.com/pricing-ai',
				'alt' => __( 'Elementor AI', 'hello-plus' ),
				'title' => __( 'AI Me', 'hello-plus' ),
				'message' => __( 'Stand on the shoulders of giants.', 'hello-plus' ),
				'button' => __( 'AI!', 'hello-plus' ),
			];
		}

		if ( ! defined( 'ELEMENTOR_IMAGE_OPTIMIZER_VERSION' ) ) {
			$action_links_data[] = [
				'type' => 'go-image-optimizer',
				'image' => HELLO_PLUS_URL . '/assets/images/elementor.svg',
				'url' => 'https://elementor.com/pricing-plugin',
				'alt' => __( 'Elementor Image Optimizer', 'hello-plus' ),
				'title' => __( 'Image Optimizer', 'hello-plus' ),
				'message' => __( 'Smaller is better! Minimize your site size with Elementor Image Optimizer', 'hello-plus' ),
				'button' => __( 'Go Small', 'hello-plus' ),
			];
		}

		return rest_ensure_response( [ 'links' => $action_links_data ] );
	}
}
