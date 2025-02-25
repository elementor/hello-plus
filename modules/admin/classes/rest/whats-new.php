<?php
namespace HelloPlus\Modules\Admin\Classes\Rest;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\WPNotificationsPackage\V110\Notifications as Theme_Notifications;
use HelloPlus\Modules\Admin\Module;
use WP_REST_Server;

class Whats_New {
	public function get_notifications() {
		/**
		 * @var \HelloPlus\Modules\Admin\Components\Notifications $notifications_component
		 */
		$notifications_component = Module::instance()->get_component( 'Notifications' );
		return $notifications_component->get_notifications_by_conditions( true );
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
