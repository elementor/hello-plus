<?php

namespace HelloPlus\Modules\Admin\Components;

use HelloPlus\Includes\Utils;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Settings;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Setup_Wizard;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Admin_Menu_Controller {

	const SETUP_WIZARD_TRANSIENT_NAME = 'helloplus_redirect_to_setup_wizard';

	public function admin_menu( $parent_slug ) {
		if ( Utils::has_hello_elementor_theme() ) {
			return;
		}
		$setup_wizard = new Setup_Wizard();
		$setup_wizard->register_setup_wizard_page( $parent_slug );
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

		if ( ! is_admin() ) {
			return;
		}

		delete_transient( self::SETUP_WIZARD_TRANSIENT_NAME );

		if ( Utils::are_we_on_elementor_domains() ) {
			return;
		}

		wp_safe_redirect( self_admin_url( 'admin.php?page=' . Setup_Wizard::SETUP_WIZARD_PAGE_SLUG ) );
		exit;
	}

	public function __construct() {
		add_action( 'hello-plus-theme/admin-menu', [ $this, 'admin_menu' ], 10, 1 );
		add_action( 'hello-plus/init', [ $this, 'redirect_on_first_activation' ] );
		if ( ! Utils::has_hello_elementor_theme() ) {
			add_action( 'hello-plus/activate', [ $this, 'activate' ] );
		}
	}
}
