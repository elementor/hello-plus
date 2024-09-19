<?php

namespace HelloPlus\Modules\Admin\Classes\Menu\Pages;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Kits_Library {

	const KITS_LIBRARY_PAGE_SLUG = 'hello-plus-kits-library';

	public function register_kits_library_page(): void {
		add_submenu_page(
			'hello-plus',
			__( 'Kits Library', 'hello-plus' ),
			__( 'Kits Library', 'hello-plus' ),
			'manage_options',
			self::KITS_LIBRARY_PAGE_SLUG,
			[ $this, 'render_kits_library_page' ]
		);
	}

	public function render_kits_library_page(): void {
		echo '<div id="ehp-admin-kits-library">Kits Library Page Content</div>';
	}
}
