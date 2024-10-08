<?php

namespace HelloPlus\Modules\TemplateParts\Base\Traits;

use HelloPlus\Includes\Utils as Theme_Utils;

trait Shared_Header_Traits {

	protected function get_site_logo(): string {
		$site_logo = Theme_Utils::elementor()->dynamic_tags->get_tag_data_content( null, 'site-logo' );
		return $site_logo['url'] ?? Theme_Utils::get_placeholder_image_src();
	}

    protected function get_site_url() {
        $site_url = Theme_Utils::elementor()->dynamic_tags->get_tag_data_content( null, 'site-url' );

        return $site_url ?? '';
    }

    protected function get_site_title() {
        $site_title = Theme_Utils::elementor()->dynamic_tags->get_tag_data_content( null, 'site-title' );

        return $site_title ?? '';
    }

    private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}
}