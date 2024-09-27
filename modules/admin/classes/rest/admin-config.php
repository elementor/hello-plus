<?php

namespace HelloPlus\Modules\Admin\Classes\Rest;

use HelloPlus\Modules\Admin\Classes\Menu\Pages\Kits_Library;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Setup_Wizard;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Admin_Config {
	public function __construct() {
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'elementor-hello-plus/v1',
					'/admin-settings',
					[
						'methods' => WP_REST_Server::READABLE,
						'callback' => [ $this, 'get_admin_config' ],
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
					]
				);
			}
		);
	}

	public function get_admin_config() {
		$config = $this->get_welcome_box_config( [] );

		$config = $this->get_site_parts( $config );

		return rest_ensure_response( [ 'config' => $config ] );
	}

	public function get_site_parts( array $config ) {
		$last_five_pages_query = new \WP_Query(
			[
				'posts_per_page' => 5,
				'post_type' => 'page',
				'post_status' => 'publish',
				'orderby' => 'post_date',
				'order' => 'DESC',
				'fields' => 'ids',
				'no_found_rows' => true,
				'lazy_load_term_meta' => true,
				'update_post_meta_cache' => false,
			]
		);

		$site_pages = [];

		if ( $last_five_pages_query->have_posts() ) {
			while ( $last_five_pages_query->have_posts() ) {
				$last_five_pages_query->the_post();
				$site_pages[] = [
					'title' => get_the_title(),
					'link' => get_edit_post_link( get_the_ID() ),
				];
			}
		}

		$general = [
			[
				'title' => __( 'Add New Page', 'hello-plus' ),
				'link' => admin_url( 'post-new.php?post_type=page' ),
			],
			[
				'title' => __( 'Settings', 'hello-plus' ),
				'link' => admin_url( 'admin.php?page=hello-plus-settings' ),
			],
		];

		if ( ! Setup_Wizard::has_site_wizard_been_completed() ) {
			$general[] = [
				'title' => __( 'Run setup wizard', 'hello-plus' ),
				'link' => admin_url( 'admin.php?page=hello-plus-setup-wizard' ),
			];
		}

		$config['siteParts'] = [
			'siteParts' => [
				[
					'title' => __( 'Header', 'hello-plus' ),
					'link' => admin_url( 'customize.php?autofocus[section]=header_options' ),
				],
				[
					'title' => __( 'Footer', 'hello-plus' ),
					'link' => admin_url( 'customize.php?autofocus[section]=footer_options' ),
				],
			],
			'sitePages' => $site_pages,
			'general' => $general,
		];

		return $config;
	}

	public function get_welcome_box_config( array $config ): array {
		$config['welcome'] = [
			[
				'title' => __( 'Edit home page', 'hello-plus' ),
				'variant' => 'contained',
				'link' => get_edit_post_link( get_option( 'page_on_front' ) ),
			],
			[
				'title' => __( 'View site', 'hello-plus' ),
				'variant' => 'outlined',
				'link' => get_site_url(),
			],
		];

		if ( ! Setup_Wizard::has_site_wizard_been_completed() ) {
			$config['welcome'][] = [
				'title' => __( 'Run setup wizard', 'hello-plus' ),
				'variant' => 'contained',
				'link' => admin_url( 'admin.php?page=hello-plus-setup-wizard' ),
			];
		}

		return $config;
	}
}
