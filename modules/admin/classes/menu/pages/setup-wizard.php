<?php

namespace HelloPlus\Modules\Admin\Classes\Menu\Pages;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Setup_Wizard {

	const SETUP_WIZARD_PAGE_SLUG = 'hello-plus-setup-wizard';

	public function register_setup_wizard_page(): void {
		add_submenu_page(
			'hello-plus',
			__( 'Setup Wizard', 'hello-plus' ),
			__( 'Setup Wizard', 'hello-plus' ),
			'manage_options',
			self::SETUP_WIZARD_PAGE_SLUG,
			[ $this, 'render_setup_wizard_page' ]
		);
	}

	public function render_setup_wizard_page(): void {
		echo '<div id="ehp-admin-setup-wizard">Setup Wizard Page Content</div>';
	}
}
