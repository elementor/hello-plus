<?php

namespace HelloPlus\Modules\Admin\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * class Menu
 **/
class Admin_Menu {
	const MENU_PAGE_TITLE = 'Hello+';
	const MENU_PAGE_SLUG   = 'hello-plus';
	const MENU_PAGE_ICON   = 'dashicons-fullscreen-exit-alt';
	const MENU_PAGE_POSITION   = 59.9;

	/**
	 * @return void
	 */
	public function admin_menu(): void {
		add_menu_page(
			self::MENU_PAGE_TITLE,
			self::MENU_PAGE_TITLE,
			'manage_options',
			self::MENU_PAGE_SLUG,
			[ $this, 'render' ],
			self::MENU_PAGE_ICON,
			self::MENU_PAGE_POSITION
		);
	}

	/**
	 * @return void
	 */
	public function render(): void {
		// TODO: should render the home-dashboard app
		echo '<div id="ehp-admin-home"></div>';
	}

	/**
	 * register menu actions
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}
}
