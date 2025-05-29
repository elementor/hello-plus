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

		if ( ! Setup_Wizard::has_site_wizard_been_completed() || has_filter( 'hello-plus-theme/template-parts' ) ) {
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

	protected function update_site_part_link( &$part, $part_type = '', $title = '' ) {
		if ( empty( $part ) || empty( $part_type ) || empty( $title ) ) {
			return;
		}
		$part_id = 'hello-' . $part_type;

		// Only process if this part matches our type.
		if ( $part['id'] !== $part_id ) {
			return;
		}

		$elementor_active    = Utils::is_elementor_active();
		$edit_with_elementor = $elementor_active ? '&action=elementor' : '';
		$library_type        = 'ehp-' . $part_type;

		// Get the active document based on part type.
		$active_document = null;
		if ( 'header' === $part_type ) {
			$active_document = Ehp_Header::get_active_document();
		} elseif ( 'footer' === $part_type ) {
			$active_document = Ehp_Footer::get_active_document();
		}

		$edit_link    = $part['link'];
		$add_new_link = admin_url( "edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type={$library_type}" );
		$pro_part     = Utils::get_pro_part( $part_type );
		if ( $pro_part ) {
			$edit_link = get_edit_post_link( $pro_part, 'admin' ) . $edit_with_elementor;
		} elseif ( ! empty( $active_document ) ) {
			$edit_link = get_edit_post_link( $active_document[0], 'admin' ) . $edit_with_elementor;
		}

		if ( Utils::has_pro() ) {
			$add_new_link = \Elementor\Plugin::instance()->app->get_base_url() . '#/site-editor/templates/' . $part_type;
		}

		$part['title']    = $title;
		$part['sublinks'] = [
			[
				'title' => __( 'Edit', 'hello-plus' ),
				'link'  => $edit_link,
			],
			[
				'title' => __( 'Add New', 'hello-plus' ),
				'link'  => $add_new_link,
			],
		];
	}

	public function site_parts_filter( $site_parts ) {
		$elementor_active = Utils::is_elementor_active();

		// If Elementor is not active or if Elementor Pro is active, let the theme handle the logic.
		if ( ! $elementor_active ) {
			return $site_parts;
		}

		foreach ( $site_parts['siteParts'] as &$part ) {
			if ( ! isset( $part['id'] ) || ! in_array( $part['id'], [ 'hello-header', 'hello-footer' ], true ) ) {
				continue;
			}

			$this->update_site_part_link( $part, 'header', __( 'Header', 'hello-plus' ) );
			$this->update_site_part_link( $part, 'footer', __( 'Footer', 'hello-plus' ) );
		}

		return $site_parts;
	}

	public function __construct() {
		add_filter( 'hello-plus-theme/settings/hello_theme', '__return_false' );
		add_filter( 'hello-plus-theme/settings/hello_style', '__return_false' );
		add_filter( 'hello-plus-theme/customizer/enable', Setup_Wizard::has_site_wizard_been_completed() ? '__return_false' : '__return_true' );
		add_filter( 'hello-plus-theme/rest/admin-config', [ $this, 'admin_config' ] );
		add_filter( 'elementor/editor/localize_settings', [ $this, 'localize_settings' ] );

		add_filter( 'hello-plus-theme/display-default-header', [ $this, 'display_default_header' ], 100 );
		add_filter( 'hello-plus-theme/display-default-footer', [ $this, 'display_default_footer' ], 100 );

		add_filter( 'hello-plus-theme/template-parts', [ $this, 'site_parts_filter' ], 100 );
	}
}
