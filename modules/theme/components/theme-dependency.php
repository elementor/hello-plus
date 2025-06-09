<?php

namespace HelloPlus\Modules\Theme\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use HelloPlus\Includes\Utils;

class Theme_Dependency {
	public function activate() {
		if ( ! Utils::has_hello_biz() ) {
			deactivate_plugins( HELLOPLUS_PLUGIN_BASE );

			wp_die(
				wp_kses( __( '<strong>Hello+ needs an active Hello suite theme to work</strong>. Install and activate a theme from the Elementor Hello suite to start using the plugin.', 'hello-plus' ),
					[
						'strong' => [],
					]
				),
				esc_html__( 'Plugin Activation Error', 'hello-plus' ),
				[ 'back_link' => true ]
			);
		}

		if ( Utils::is_elementor_active() && ! Utils::is_active_elementor_version_supported() ) {
			deactivate_plugins( HELLOPLUS_PLUGIN_BASE );

			wp_die(
				esc_html( Utils::get_update_elementor_message() ),
				esc_html__( 'Plugin Activation Error', 'hello-plus' ),
				[ 'back_link' => true ]
			);
		}
	}

	public function __construct() {
		add_action( 'hello-plus/activate', [ $this, 'activate' ] );
	}
}
