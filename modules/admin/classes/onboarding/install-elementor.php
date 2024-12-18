<?php

namespace HelloPlus\Modules\Admin\Classes\Onboarding;

use HelloPlus\Includes\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Install_Elementor {

	public function install_and_activate() {
		wp_ajax_install_plugin();
	}

	public function activate() {
		if ( ! Utils::is_installed_elementor_version_supported() ) {
			wp_send_json_error(
				[
					'errorMessage' => Utils::get_update_elementor_message(),
				],
			);
		}

		$activated = activate_plugin( 'elementor/elementor.php' );

		if ( is_wp_error( $activated ) ) {
			wp_send_json_error( [ 'errorMessage' => $activated->get_error_message() ] );
		}

		wp_send_json_success( [ 'message' => __( 'Elementor activated successfully.', 'hello-plus' ) ] );
	}
}
