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
				esc_html__( 'The Hello Plus plugin requires the Hello Biz theme to be installed and active.', 'hello-plus' ),
				esc_html__( 'Plugin Activation Error', 'hello-plus' ),
				[ 'back_link' => true ]
			);
		}

		if ( Utils::is_elementor_active() && version_compare( ELEMENTOR_VERSION, HELLOPLUS_MIN_ELEMENTOR_VERSION, '<' ) ) {
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
