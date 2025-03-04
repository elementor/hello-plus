<?php
namespace HelloPlus\Modules\Admin\Classes\Rest;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Modules\Admin\Module;

class Whats_New {
	public function get_notifications() {
		/**
		 * @var \HelloPlus\Modules\Admin\Components\Notificator $notifications_component
		 */
		$notifications_component = Module::instance()->get_component( 'Notificator' );
		return $notifications_component->get_notifications_by_conditions( true );
	}

	public function rest_api_init() {
		register_rest_route(
			'elementor-hello-plus/v1',
			'/whats-new',
			[
				'methods' => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_notifications' ],
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			]
		);
	}

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
	}
}
