<?php

namespace HelloPlus\Modules\Admin\Components;

use HelloPlus\Modules\Admin\Classes\Elementor\Elementor_Helper;

class Scripts_Controller {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_hello_plus_admin_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_hello_plus_onboarding_scripts' ) );
	}

	public function enqueue_hello_plus_admin_scripts() {
		$screen = get_current_screen();

		if ( 'toplevel_page_' . Admin_Menu_Controller::MENU_PAGE_SLUG !== $screen->id ) {
			return;
		}

		$handle = 'hello-plus-admin';
		$asset_path = HELLO_PLUS_SCRIPTS_PATH . 'hello-plus-admin.asset.php';
		$asset_url = HELLO_PLUS_SCRIPTS_URL;

		if ( ! file_exists( $asset_path ) ) {
			throw new \Error( 'You need to run `npm run build` for the "hello-plus" first.' );
		}

		$script_asset = require $asset_path;

		wp_enqueue_script(
			$handle,
			HELLO_PLUS_SCRIPTS_URL . "$handle.js",
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations( $handle, 'hello-plus' );
	}

	public function enqueue_hello_plus_onboarding_scripts() {
		$screen = get_current_screen();

		if ( 'hello_page_hello-plus-setup-wizard' !== $screen->id ) {
			return;
		}

		$handle = 'hello-plus-onboarding';
		$asset_path = HELLO_PLUS_SCRIPTS_PATH . 'hello-plus-admin.asset.php';
		$asset_url = HELLO_PLUS_SCRIPTS_URL;

		if ( ! file_exists( $asset_path ) ) {
			throw new \Error( 'You need to run `npm run build` for the "hello-plus" first.' );
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
