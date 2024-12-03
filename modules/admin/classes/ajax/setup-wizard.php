<?php

namespace HelloPlus\Modules\Admin\Classes\Ajax;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Modules\Admin\Classes\Onboarding\Install_Elementor;

class Setup_Wizard {

	public function __construct() {
		add_action( 'wp_ajax_helloplus_setup_wizard', [ $this, 'ajax_setup_wizard' ] );
	}

	public function ajax_setup_wizard() {
		check_ajax_referer( 'updates', 'nonce' );

		$step = filter_input( INPUT_POST, 'step', FILTER_UNSAFE_RAW );
		$allow_tracking = filter_input( INPUT_POST, 'allowTracking', FILTER_VALIDATE_BOOLEAN );

		if ( $allow_tracking ) {
			update_option( 'elementor_allow_tracking', true );
		} else {
			delete_option( 'elementor_allow_tracking' );
		}

		$campaign_data = [
			'source' => 'ecore-ehp-install',
			'campaign' => 'ec-plg',
			'medium' => 'wp-dash',
		];

		set_transient(
			'elementor_elementor_core_campaign',
			$campaign_data,
			30 * DAY_IN_SECONDS
		);

		switch ( $step ) {
			case 'install-elementor':
				$step = new Install_Elementor();
				$step->install_and_activate();
				break;
			case 'activate-elementor':
				$step = new Install_Elementor();
				$step->activate();
				break;
			default:
				wp_send_json_error( [ 'message' => __( 'Invalid step.', 'hello-plus' ) ] );
		}
	}
}
