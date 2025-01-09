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
				wp_kses( sprintf( __( 'The Hello Plus plugin requires the Hello Biz theme to be installed and active. <a href="%s">Check it out!</a>', 'hello-plus' ), admin_url( 'theme-install.php?theme=hello-biz' ) ),
					[
						'a' => [
							'href' => [],
						],
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
