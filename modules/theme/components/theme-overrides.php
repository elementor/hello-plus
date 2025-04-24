<?php

namespace HelloPlus\Modules\Theme\Components;

use HelloPlus\Includes\Utils;
use HelloPlus\Modules\Admin\Classes\Menu\Pages\Setup_Wizard;
use HelloPlus\Modules\TemplateParts\Documents\{
	Ehp_Document_Base,
	Ehp_Footer,
	Ehp_Header
};

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

	public function localize_settings( $data ) {
		$data['close_modal_redirect_hello_plus'] = admin_url( 'edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=' );

		return $data;
	}

	public function display_default_header( bool $display ): bool {
		return $this->display_default_header_footer( $display, 'header' );
	}

	public function display_default_footer( bool $display ): bool {
		return $this->display_default_header_footer( $display, 'footer' );
	}

	protected function display_default_header_footer( bool $display, string $location ): bool {
		if ( ! Utils::elementor()->preview->is_preview_mode() ) {
			switch ( $location ) {
				case 'header':
					return 0 >= Ehp_Header::get_published_post_count() ? $display : false;
				case 'footer':
					return 0 >= Ehp_Footer::get_published_post_count() ? $display : false;
				default:
					return $display;
			}
		}

		$preview_post_id = filter_input( INPUT_GET, 'elementor-preview', FILTER_VALIDATE_INT );
		$document = Utils::elementor()->documents->get( $preview_post_id );

		if ( $document instanceof Ehp_Document_Base && $document::LOCATION === $location ) {
			return false;
		}

		return $display;
	}

	public function __construct() {
		add_filter( 'hello-plus-theme/settings/hello_theme', '__return_false' );
		add_filter( 'hello-plus-theme/settings/hello_style', '__return_false' );
		add_filter( 'hello-plus-theme/customizer/enable', Setup_Wizard::has_site_wizard_been_completed() ? '__return_false' : '__return_true' );
		add_filter( 'hello-plus-theme/rest/admin-config', [ $this, 'admin_config' ] );
		add_filter( 'elementor/editor/localize_settings', [ $this, 'localize_settings' ] );

		add_filter( 'hello-plus-theme/display-default-header', [ $this, 'display_default_header' ], 100 );
		add_filter( 'hello-plus-theme/display-default-footer', [ $this, 'display_default_footer' ], 100 );
	}
}
