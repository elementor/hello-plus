<?php

namespace HelloPlus\Modules\Admin\Components;

use HelloPlus\Modules\Admin\Classes\Menu\Pages\Setup_Wizard;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Theme_Override {

	public function __construct() {
		add_filter( 'hello-biz/rest/admin-config', [ $this, 'override_admin_config' ] );
	}

	public function override_admin_config( $config ) {

		if ( ! Setup_Wizard::has_site_wizard_been_completed() ) {
			$config['welcome'][] = [
				'title' => __( 'Run setup wizard', 'hello-biz' ),
				'variant' => 'contained',
				'link' => admin_url( 'admin.php?page=hello-plus-setup-wizard' ),
			];

			$config['siteParts']['general'][] = [
				'title' => __( 'Run setup wizard', 'hello-biz' ),
				'link' => admin_url( 'admin.php?page=hello-plus-setup-wizard' ),
			];
		}

		return $config;
	}
}
