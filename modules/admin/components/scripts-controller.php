<?php

namespace HelloPlus\Modules\Admin\Components;

use HelloPlus\Includes\Utils;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Setup_Wizard;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Scripts_Controller {
	public function enqueue_hello_plus_onboarding_scripts() {
		$screen = get_current_screen();

		if ( ! Utils::ends_with( $screen->id, Setup_Wizard::SETUP_WIZARD_PAGE_SLUG ) ) {
			return;
		}

		$handle = 'helloplus-onboarding';
		$asset_path = HELLOPLUS_SCRIPTS_PATH . 'helloplus-onboarding.asset.php';
		$asset_url = HELLOPLUS_SCRIPTS_URL;

		if ( ! file_exists( $asset_path ) ) {
			throw new \Exception( 'You need to run `npm run build` for the "hello-plus" first.' );
		}

		$script_asset = require $asset_path;

		$script_asset['dependencies'][] = 'wp-util';

		wp_enqueue_script(
			$handle,
			HELLOPLUS_SCRIPTS_URL . "$handle.js",
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations( $handle, 'hello-plus' );
	}

	public function enqueue_whats_new_onboarding_scripts() {
		$screen = get_current_screen();

		if ( 'plugins' !== $screen->id ) {
			return;
		}

		$handle = 'helloplus-whats-new';
		$asset_path = HELLOPLUS_SCRIPTS_PATH . $handle . '.asset.php';
		$asset_url = HELLOPLUS_SCRIPTS_URL;

		if ( ! file_exists( $asset_path ) ) {
			throw new \Exception( 'You need to run `npm run build` for the "hello-plus" first.' );
		}

		$script_asset = require $asset_path;

		$script_asset['dependencies'][] = 'wp-util';

		wp_enqueue_script(
			$handle,
			HELLOPLUS_SCRIPTS_URL . "$handle.js",
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations( $handle, 'hello-plus' );
	}

	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_hello_plus_onboarding_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_whats_new_onboarding_scripts' ] );
	}
}
