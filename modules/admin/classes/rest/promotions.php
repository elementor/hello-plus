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
				'image' => HELLO_PLUS_URL . '/assets/images/go-pro.svg',
				'url' => 'https://elementor.com/pricing-plugin',
				'alt' => __( 'Elementor Pro', 'hello-plus' ),
				'title' => __( 'Bring Your Vision To Life', 'hello-plus' ),
				'message' => __( 'Get complete design flexibility for your website with Elementor Pro’s advanced tools and premium features.', 'hello-plus' ),
				'button' => __( 'Upgrade Now', 'hello-plus' ),
				'features' => [
					__( 'Popup Builder', 'hello-plus' ),
					__( 'Custom Code & CSS', 'hello-plus' ),
					__( 'E-commerce Features', 'hello-plus' ),
					__( 'Collaborative Notes', 'hello-plus' ),
					__( 'Form Submission', 'hello-plus' ),
					__( 'Form Integration', 'hello-plus' ),
					__( 'Customs Attribute ', 'hello-plus' ),
					__( 'Role Manager', 'hello-plus' ),
				],
			];
		}

		if ( ! defined( 'ELEMENTOR_AI_VERSION' ) ) {
			$action_links_data[] = [
				'type' => 'go-ai',
				'image' => HELLO_PLUS_URL . '/assets/images/ai.png',
				'url' => 'https://elementor.com/pricing-ai',
				'alt' => __( 'Elementor AI', 'hello-plus' ),
				'title' => __( 'AI Me', 'hello-plus' ),
				'message' => __( 'Boost creativity with Elementor AI. Craft & enhance copy, create custom CSS & Code, and generate images to elevate your website.', 'hello-plus' ),
				'button' => __( 'Let\'s Go', 'hello-plus' ),
			];
		}

		if ( ! defined( 'ELEMENTOR_IMAGE_OPTIMIZER_VERSION' ) ) {
			$action_links_data[] = [
				'type' => 'go-image-optimizer',
				'image' => HELLO_PLUS_URL . '/assets/images/image-optimizer.png',
				'url' => 'https://elementor.com/pricing-plugin',
				'alt' => __( 'Elementor Image Optimizer', 'hello-plus' ),
				'title' => __( 'Image Optimizer', 'hello-plus' ),
				'message' => __( 'Check out this incredibly useful plugin that will compress and optimize your images, giving you leaner, faster websites.', 'hello-plus' ),
				'button' => __( 'Go Small', 'hello-plus' ),
			];
		}

		return rest_ensure_response( [ 'links' => $action_links_data ] );
	}
}
