<?php

namespace HelloPlus\Modules\Admin\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Scripts_Controller {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_hello_plus_onboarding_scripts' ) );
	}

	public function enqueue_hello_plus_onboarding_scripts() {
		$screen = get_current_screen();

		if ( 'hello_page_hello-plus-setup-wizard' !== $screen->id ) {
			return;
		}

		$handle = 'hello-plus-onboarding';
		$asset_path = HELLO_PLUS_SCRIPTS_PATH . 'hello-plus-onboarding.asset.php';
		$asset_url = HELLO_PLUS_SCRIPTS_URL;

		if ( ! file_exists( $asset_path ) ) {
			throw new \Exception( 'You need to run `npm run build` for the "hello-plus" first.' );
		}

		$script_asset = require $asset_path;

		$script_asset['dependencies'][] = 'wp-util';

		wp_enqueue_script(
			$handle,
			HELLO_PLUS_SCRIPTS_URL . "$handle.js",
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations( $handle, 'hello-plus' );
	}
}
