<?php

namespace HelloPlus\Modules\Admin\Classes\Rest;

use HelloPlus\Modules\TemplateParts\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Set_Editor_Visited {
	const ROUTE_NAMESPACE = 'elementor-hello-plus/v1';

	public function rest_api_init() {
		register_rest_route(
			self::ROUTE_NAMESPACE,
			'/set-editor-visited',
			[
				'methods' => \WP_REST_Server::CREATABLE,
				'callback' => [ $this, 'increment_editor_visited' ],
				'permission_callback' => [ $this, 'permission_callback' ],
			]
		);
	}

	public function increment_editor_visited() {
		$user_id = get_current_user_id();
		$current_count = (int) get_user_meta( $user_id, Module::USER_META_TIMES_EDITOR_OPENED, true );
		$new_count = $current_count + 1;
		update_user_meta( $user_id, Module::USER_META_TIMES_EDITOR_OPENED, $new_count );

		return rest_ensure_response( [ 'new_count' => $new_count ] );
	}

	public function permission_callback() {
		return is_user_logged_in();
	}

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}
}
