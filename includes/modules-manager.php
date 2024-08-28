<?php

namespace HelloPlus;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Includes\Module_Base;

/**
 * class Modules_Manager
 *
 * @package HelloPlus
 * @subpackage HelloPlusModules
 */
final class Modules_Manager {
	/**
	 * @var Module_Base[]
	 */
	private array $modules = [];

	/**
	 * @param string $module_name
	 *
	 * @return ?Module_Base
	 */
	public function get_module( string $module_name ): ?Module_Base {
		if ( isset( $this->modules[ $module_name ] ) ) {
			return $this->modules[ $module_name ];
		}

		return null;
	}

	/**
	 * @param Module_Base $module
	 *
	 * allow child theme and 3rd party plugins to add modules
	 *
	 * @return void
	 */
	public function add_module( Module_Base $module ) {
		$class_name = $module->get_reflection()->getName();
		if ( $module::is_active() ) {
			$this->modules[ $class_name ] = $module::instance();
		}
	}

	/**
	 * class constructor
	 */
	public function __construct() {
		$modules_list = [
			'Customizer',
			'Settings',
			'Admin',
			'Theme',
		];

		foreach ( $modules_list as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name =  __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

			/** @var Module_Base $class_name */
			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	}
}
