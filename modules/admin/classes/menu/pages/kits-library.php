<?php

namespace HelloPlus\Modules\Admin\Classes\Menu\Pages;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Kits_Library {

	const KITS_LIBRARY_PAGE_SLUG = 'hello-plus-kits-library';

	public static function get_page_by_meta_key( $meta_key ) {
		$query = new \WP_Query(
			[
				'meta_key' => $meta_key,
				'meta_compare' => 'EXISTS',
				'post_type' => 'page',
				'posts_per_page' => 1,
				'fields' => 'ids',
				'post_status' => 'publish',
			]
		);

		return $query->posts[0] ?? null;
	}

	public static function get_about_page_id() {
		return self::get_page_by_meta_key( '_elementor_about' );
	}

	public static function get_services_page_id() {
		return self::get_page_by_meta_key( '_elementor_services' );
	}

	public static function get_contact_page_id() {
		return self::get_page_by_meta_key( '_elementor_contact' );
	}

	public static function get_work_page_id() {
		return self::get_page_by_meta_key( '_elementor_work' );
	}

	public function register_kits_library_page( $parent_slug ): void {
		add_submenu_page(
			$parent_slug,
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
