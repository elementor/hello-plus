<?php

namespace HelloPlus\Modules\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Includes\Module_Base;

/**
 * class Module
 *
 * @package HelloPlus
 * @subpackage HelloPlusModules
 */
class Module extends Module_Base {
	/**
	 * @inheritDoc
	 */
	public static function get_name(): string {
		return 'admin';
	}

	/**
	 * @inheritDoc
	 */
	protected function get_component_ids(): array {
		return [
			'Api_Controller',
			'Scripts_Controller',
			'Admin_Menu_Controller',
			'Admin_Controller',
			'Notificator',
		];
	}
}
