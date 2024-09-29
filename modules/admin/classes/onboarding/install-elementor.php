<?php

namespace HelloPlus\Modules\Admin\Classes\Onboarding;

class Install_Elementor {

	public function install_and_activate() {
		wp_ajax_install_plugin();
	}

	public function activate() {
		$activated = activate_plugin( 'elementor/elementor.php' );

		if ( is_wp_error( $activated ) ) {
			wp_send_json_error( [ 'errorMessage' => $activated->get_error_message() ] );
		}

		wp_send_json_success( [ 'message' => __( 'Elementor activated successfully.', 'hello-plus' ) ] );
	}
}
