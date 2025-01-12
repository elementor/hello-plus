<?php

namespace HelloPlus\Modules\Theme\Components;

use HelloPlus\Includes\Utils;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Setup_Wizard;
use HelloPlus\Modules\TemplateParts\Documents\Ehp_Footer;
use HelloPlus\Modules\TemplateParts\Documents\Ehp_Header;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Theme_Overrides {

	public function admin_config( array $config ): array {
		if ( ! Setup_Wizard::has_site_wizard_been_completed() ) {
			return $config;
		}

		$config['siteParts']['siteParts'] = [];

		$header = Ehp_Header::get_active_document();
		$footer = Ehp_Footer::get_active_document();
		$elementor_active    = Utils::is_elementor_active();
		$edit_with_elementor = $elementor_active ? '&action=elementor' : '';

		if ( $header ) {
			$config['siteParts']['siteParts'][] = [
				'title' => __( 'Header', 'hello-plus' ),
				'link' => get_edit_post_link( $header[0], 'admin' ) . $edit_with_elementor,
			];
		}

		if ( $footer ) {
			$config['siteParts']['siteParts'][] = [
				'title' => __( 'Footer', 'hello-plus' ),
				'link' => get_edit_post_link( $footer[0], 'admin' ) . $edit_with_elementor,
			];
		}

		return $config;
	}

	public function register_remote_source() {
		Utils::elementor()->templates_manager->register_source(
			'HelloPlus\Modules\TemplateParts\Classes\Sources\Source_Remote_Ehp'
		);
	}

	public function localize_settings( $data ) {
		$data['close_modal_redirect_hello_plus'] = admin_url( 'admin.php?page=hello-biz' );

		return $data;
	}

	public function __construct() {
		add_filter( 'hello-plus-theme/settings/header_footer', '__return_false' );
		add_filter( 'hello-plus-theme/settings/hello_theme', '__return_false' );
		add_filter( 'hello-plus-theme/settings/hello_style', '__return_false' );
		add_filter( 'hello-plus-theme/customizer/enable', Setup_Wizard::has_site_wizard_been_completed() ? '__return_false' : '__return_true' );
		add_filter( 'hello-plus-theme/rest/admin-config', [ $this, 'admin_config' ] );
		add_action( 'elementor/init', [ $this, 'register_remote_source' ] );
		add_action( 'elementor/editor/localize_settings', [ $this, 'localize_settings' ] );
	}
}
