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

	public function render(): void {
		echo '<div id="ehp-admin-home"></div>';
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}
}
