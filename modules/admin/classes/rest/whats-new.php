<?php

namespace HelloPlus\Modules\Admin\Classes\Rest;

use Elementor\WPNotificationsPackage\V110\Notifications as Theme_Notifications;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Whats_New {

	public function get_notifications() {
		require_once HELLOPLUS_PATH . '/vendor/autoload.php';

		$notificator = new Theme_Notifications(
			'hello-plus',
			HELLOPLUS_VERSION,
		);

		return $notificator->get_notifications_by_conditions( true );
	}

	public function rest_api_init() {
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
