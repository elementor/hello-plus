<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Traits;

use Elementor\Utils as Elementor_Utils;

trait Shared_Header_Traits {
	protected int $nav_menu_index = 1;

	public function get_site_logo_url(): string {
		if ( ! has_custom_logo() ) {
			return Elementor_Utils::get_placeholder_image_src();
		}

		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		return $image[0] ?? Elementor_Utils::get_placeholder_image_src();
	}

	public function get_site_url(): string {
		return site_url();
	}

	public function get_site_title(): string {
		return get_bloginfo( 'name' );
	}

	public function get_site_tagline(): string {
		return get_bloginfo( 'description' );
	}

	public function get_available_menus(): array {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	public function get_and_advance_nav_menu_index(): int {
		return $this->nav_menu_index++;
	}
}
