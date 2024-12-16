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
		$this->check_if_local_version_is_higher_than_min_version();

		$activated = activate_plugin( 'elementor/elementor.php' );

		if ( is_wp_error( $activated ) ) {
			wp_send_json_error( [ 'errorMessage' => $activated->get_error_message() ] );
		}

		wp_send_json_success( [ 'message' => __( 'Elementor activated successfully.', 'hello-plus' ) ] );
	}

	public function check_if_local_version_is_higher_than_min_version(): void {
		$plugin_file = WP_PLUGIN_DIR . '/elementor/elementor.php';

		if ( file_exists( $plugin_file ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';

			$plugin_data = get_plugin_data( $plugin_file );

			$plugin_version = $plugin_data['Version'];

			if ( version_compare( $plugin_version, HELLOPLUS_MIN_ELEMENTOR_VERSION, '<' ) ) {
				wp_send_json_error(
					[
						'errorMessage' => sprintf(
							__(
								'Elementor plugin version needs to be at least %s for Hello Plus to Work. Please update.', 'hello-plus'
							),
							HELLOPLUS_MIN_ELEMENTOR_VERSION
						),
					],
				);
			}
		}
	}
}
