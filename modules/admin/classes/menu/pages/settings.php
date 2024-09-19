<?php

namespace HelloPlus\Modules\Admin\Classes\Menu\Pages;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Settings {

	const SETTINGS_PAGE_SLUG = 'hello-plus-settings';

	public function register_settings_page(): void {
		add_submenu_page(
			'hello-plus',
			__( 'Settings', 'hello-plus' ),
			__( 'Settings', 'hello-plus' ),
			'manage_options',
			self::SETTINGS_PAGE_SLUG,
			[ $this, 'render_settings_page' ]
		);
	}

	public function render_settings_page(): void {
		echo '<div id="ehp-admin-settings">Settings Page Content</div>';
	}
}
