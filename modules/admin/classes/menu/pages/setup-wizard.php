<?php

namespace HelloPlus\Modules\Admin\Classes\Menu\Pages;

use HelloPlus\Modules\Admin\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Setup_Wizard {
	const SETUP_WIZARD_PAGE_SLUG = 'hello-plus-setup-wizard';

	public static function has_site_wizard_been_completed(): bool {
		static $is_setup_wizard_completed = null;

		if ( ! class_exists( '\Elementor\App\Modules\ImportExport\Processes\Revert' ) ) {
			return false;
		}

		if ( ! is_null( $is_setup_wizard_completed ) ) {
			return $is_setup_wizard_completed;
		}

		$sessions = \Elementor\App\Modules\ImportExport\Processes\Revert::get_import_sessions();

		if ( ! $sessions ) {
			return false;
		}

		$last_session = end( $sessions );
		$kit_name = $last_session['kit_name'];

		try {
			/**
			 * @var \HelloPlus\Modules\Admin\Classes\Rest\Onboarding_Settings $onboarding_rest
			 */
			$onboarding_rest = Module::instance()->get_component( 'Api_Controller' )->get_endpoint( 'onboarding-settings' );

			$kits = $onboarding_rest->get_kits();

			$kit = array_filter( $kits, function ( $k ) use ( $kit_name ) {
				return $k['manifest']['name'] === $kit_name;
			} );

			$is_setup_wizard_completed = ! empty( $kit );
		} catch ( \Exception $e ) {
			$is_setup_wizard_completed = false;
		}

		return $is_setup_wizard_completed;
	}

	public function register_setup_wizard_page( $parent_slug ): void {
		add_submenu_page(
			$parent_slug,
			__( 'Setup Wizard', 'hello-plus' ),
			__( 'Setup Wizard', 'hello-plus' ),
			'manage_options',
			self::SETUP_WIZARD_PAGE_SLUG,
			[ $this, 'render_setup_wizard_page' ]
		);
	}

	public function render_setup_wizard_page(): void {
		echo '<div id="ehp-admin-onboarding"></div>';
	}
}
