<?php

namespace HelloPlus\Modules\Admin\Components;

use HelloPlus\Modules\Admin\Classes\Menu\Pages\Kits_Library;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Settings;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Setup_Wizard;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Admin_Menu_Controller {

	const MENU_PAGE_SLUG = 'hello-plus';
	const MENU_PAGE_ICON = 'dashicons-fullscreen-exit-alt';
	const MENU_PAGE_POSITION = 59.9;

	public function admin_menu(): void {
		add_menu_page(
			__( 'Hello+', 'hello-plus' ),
			__( 'Hello+', 'hello-plus' ),
			'manage_options',
			self::MENU_PAGE_SLUG,
			[ $this, 'render' ],
			self::MENU_PAGE_ICON,
			self::MENU_PAGE_POSITION
		);

		add_submenu_page(
			self::MENU_PAGE_SLUG,
			__( 'Home', 'hello-plus' ),
			__( 'Home', 'hello-plus' ),
			'manage_options',
			self::MENU_PAGE_SLUG,
			[ $this, 'render' ]
		);

		$settings = new Settings();
		$settings->register_settings_page();

		$setup_wizard = new Setup_Wizard();
		$setup_wizard->register_setup_wizard_page();

		$kits_library = new Kits_Library();
		$kits_library->register_kits_library_page();
	}

	public function enqueue_scripts() {
		$screen = get_current_screen();

		if ( 'toplevel_page_' . self::MENU_PAGE_SLUG !== $screen->id ) {
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

		wp_enqueue_style(
			$handle,
			HELLO_PLUS_STYLE_URL . "$handle.css",
			[ 'wp-components' ],
			$script_asset['version']
		);
	}

	public function render(): void {
		echo '<div id="ehp-admin-home"></div>';
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}
}
