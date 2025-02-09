<?php

namespace HelloPlus\Modules\Admin\Classes\Rest;

use Elementor\WPNotificationsPackage\V110\Notifications as Theme_Notifications;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Whats_New {

	public function get_notifications() {
		require_once HELLO_BIZ_PATH . '/vendor/autoload.php';

		$notificator = new Theme_Notifications(
			'hello-elementor',
			'3.1.0',
			'theme'
		);

		return $notificator->get_notifications_by_conditions( true );
	}

	public function register_routes() {
		register_rest_route(
			'elementor-hello-plus/v1',
			'/whats-new',
			[
				'methods' => WP_REST_Server::READABLE,
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
