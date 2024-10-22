<?php
namespace HelloPlus\Modules\Theme\components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Modules\Admin\Classes\Menu\Pages\Setup_Wizard;

/**
 * class Theme_Integration
 **/
class Theme_Integration {
	/**
	 * Activate the theme
	 *
	 * @return void
	 */
	public function activate() {
		if ( ! Setup_Wizard::has_site_wizard_been_completed() ) {
			set_transient( 'hello_plus_redirect_to_setup_wizard', true );
		}
	}

	public function redirect_on_first_activation() {
		if ( get_transient( 'hello_plus_redirect_to_setup_wizard' ) ) {
			delete_transient( 'hello_plus_redirect_to_setup_wizard' );
			wp_safe_redirect( admin_url( 'admin.php?page=' . Setup_Wizard::SETUP_WIZARD_PAGE_SLUG ) );
			exit;
		}
	}

	public function __construct() {
		// the original hooks:
		//add_action( 'after_switch_theme', [ $this, 'activate' ] );
		//add_action( 'admin_init', [ $this, 'redirect_on_first_activation' ] );

		add_action( 'hello_plus_theme/redirect_on_first_activation', [ $this, 'activate' ] );
		add_action( 'hello_plus_theme/after_switch_theme', [ $this, 'redirect_on_first_activation' ] );
	}
}
