<?php

namespace HelloPlus\Modules\Admin\Components;

use HelloPlus\Modules\Admin\Classes\Menu\Pages\Kits_Library;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Settings;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Setup_Wizard;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Admin_Menu_Controller {

	const SETUP_WIZARD_TRANSIENT_NAME = 'hello_plus_redirect_to_setup_wizard';

	public function admin_menu() {
		$settings = new Settings();
		$settings->register_settings_page();

		$setup_wizard = new Setup_Wizard();
		$setup_wizard->register_setup_wizard_page();

		$kits_library = new Kits_Library();
		$kits_library->register_kits_library_page();
	}

	public function activate() {
		if ( ! Setup_Wizard::has_site_wizard_been_completed() ) {
			set_transient( self::SETUP_WIZARD_TRANSIENT_NAME, true );
		}
	}

	public function redirect_on_first_activation() {
		if ( empty( get_transient( self::SETUP_WIZARD_TRANSIENT_NAME ) ) ) {
			return;
		}
		delete_transient( self::SETUP_WIZARD_TRANSIENT_NAME );
		wp_safe_redirect( admin_url( 'admin.php?page=' . Setup_Wizard::SETUP_WIZARD_PAGE_SLUG ) );
		exit;
	}

	public function __construct() {
		add_action( 'hello-plus-theme/admin-menu', [ $this, 'admin_menu' ] );
		add_action( 'hello-plus/activate', [ $this, 'activate' ] );
		add_action( 'hello-plus/init', [ $this, 'redirect_on_first_activation' ] );
	}
}
