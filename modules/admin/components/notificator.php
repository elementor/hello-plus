<?php
namespace HelloPlus\Modules\Admin\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\WPNotificationsPackage\V120\Notifications as Notifications_SDK;

class Notificator {
	private ?Notifications_SDK $notificator = null;

	public function get_notifications_by_conditions( $force_request = false ) {
		return $this->notificator->get_notifications_by_conditions( $force_request );
	}

	public function __construct() {
		if ( ! class_exists( 'Elementor\WPNotificationsPackage\V120\Notifications' ) ) {
			require_once HELLOPLUS_PATH . '/vendor/autoload.php';
		}

		$this->notificator = new Notifications_SDK( [
			'app_name' => 'hello-plus',
			'app_version' => HELLOPLUS_VERSION,
			'short_app_name' => 'ehp',
			'app_data' => [
				'plugin_basename' => HELLOPLUS_PLUGIN_BASE,
			],
		] );
	}
}
