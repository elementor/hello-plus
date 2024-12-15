<?php

namespace HelloPlus\Modules\Admin\Classes\Onboarding;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Install_Elementor {

	public function install_and_activate() {
		wp_ajax_install_plugin();
	}

	public function activate() {
		$plugin_file = WP_PLUGIN_DIR . '/elementor/elementor.php';

		if ( file_exists( $plugin_file ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';

			$plugin_data = get_plugin_data( $plugin_file );

			$plugin_version = $plugin_data['Version'];

			if ( version_compare( $plugin_version, '3.25.11', '<' ) ) {
				wp_send_json_error(
					[
						'errorMessage' => __( 'Elementor plugin version needs to be at least 3.25.11 for Hello Plus to Work. Please update.', 'hello-plus' )
					]
				);
			}
		}

		$activated = activate_plugin( 'elementor/elementor.php' );

		if ( is_wp_error( $activated ) ) {
			wp_send_json_error( [ 'errorMessage' => $activated->get_error_message() ] );
		}

		wp_send_json_success( [ 'message' => __( 'Elementor activated successfully.', 'hello-plus' ) ] );
	}
}
