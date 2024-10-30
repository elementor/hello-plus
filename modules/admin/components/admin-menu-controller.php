<?php

namespace HelloPlus\Modules\Admin\Components;

use HelloPlus\Modules\Admin\Classes\Menu\Pages\Kits_Library;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Settings;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Setup_Wizard;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Admin_Menu_Controller {

	public function __construct() {
		add_action( 'hello-plus-theme/admin-menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
		$settings = new Settings();
		$settings->register_settings_page();

		$setup_wizard = new Setup_Wizard();
		$setup_wizard->register_setup_wizard_page();

		$kits_library = new Kits_Library();
		$kits_library->register_kits_library_page();
	}
}
