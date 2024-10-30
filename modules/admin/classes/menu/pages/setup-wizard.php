<?php

namespace HelloPlus\Modules\Admin\Classes\Menu\Pages;

use HelloPlus\Modules\Admin\Components\Api_Controller;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Setup_Wizard {

	public static $is_setup_wizard_completed;

	const SETUP_WIZARD_PAGE_SLUG = 'hello-plus-setup-wizard';

	public static function has_site_wizard_been_completed(): bool {
		if ( ! class_exists( '\Elementor\App\Modules\ImportExport\Processes\Revert' ) ) {
			return false;
		}

		if ( null === self::$is_setup_wizard_completed ) {
			$sessions = \Elementor\App\Modules\ImportExport\Processes\Revert::get_import_sessions();

			$last_session = end( $sessions );
			$kit_name = $last_session['kit_name'];

			try {
				/**
				 * @var \HelloPlus\Modules\Admin\Classes\Rest\Onboarding_Settings $onboarding_rest
				 */
				$onboarding_rest = Api_Controller::get_endpoint( 'onboarding-settings' );
				$kits = $onboarding_rest->get_kits();

				$kit = array_filter( $kits, function ( $k ) use ( $kit_name ) {
					return $k['manifest']['name'] === $kit_name;
				} );

				self::$is_setup_wizard_completed = ! empty( $kit );
			} catch ( \Exception $e ) {
				self::$is_setup_wizard_completed = false;
			}
		}

		return self::$is_setup_wizard_completed;
	}

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
		echo '<div id="ehp-admin-onboarding"></div>';
	}
}
