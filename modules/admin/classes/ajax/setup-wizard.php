<?php

namespace HelloPlus\Modules\Admin\Classes\Ajax;

use HelloPlus\Modules\Admin\Classes\Onboarding\Install_Elementor;

class Setup_Wizard {

	public function __construct() {
		add_action( 'wp_ajax_hello_plus_setup_wizard', [ $this, 'ajax_setup_wizard' ] );
	}

	public function ajax_setup_wizard() {
		check_ajax_referer( 'updates', 'nonce' );

		$step = filter_input( INPUT_POST, 'step', FILTER_UNSAFE_RAW );

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
