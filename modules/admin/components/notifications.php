<?php
namespace HelloPlus\Modules\Admin\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\WPNotificationsPackage\V110\Notifications as Theme_Notifications;

class Notifications {
	private ?Theme_Notifications $notificator = null;

	public function get_notifications_by_conditions( $force_request = false ) {
		return $this->notificator->get_notifications_by_conditions( $force_request );
	}

	public function __construct() {
		require_once HELLOPLUS_PATH . '/vendor/autoload.php';

		$this->notificator = new Theme_Notifications(
			'hello-plus',
			HELLOPLUS_VERSION,
			'ehp'
		);
	}
}
