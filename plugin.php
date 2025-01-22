<?php
namespace HelloPlus;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Includes\Module_Base;

/**
 * Theme's main class,
 * responsible over initializing the modules & some general definitions.
 *
 * @package HelloPlus
 */
final class Plugin {

	/**
	 * @var ?Plugin
	 */
	private static ?Plugin $instance = null;

	/**
	 * @var Module_Base[]
	 */
	private array $modules = [];

	/**
	 * @var array
	 */
	private array $classes_aliases = [];

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong(
			__FUNCTION__,
			sprintf( 'Cloning instances of the singleton "%s" class is forbidden.', get_class( $this ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'1.0.0'
		);
	}

	/**
	 * Disable un-serializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong(
			__FUNCTION__,
			sprintf( 'Deserializing instances of the singleton "%s" class is forbidden.', get_class( $this ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'1.0.0'
		);
	}

	public function activate() {
		/**
		 * Fires on plugin activation
		 *
		 * @since 1.0.0
		 */
		do_action( 'hello-plus/activate' );
	}

	public function init() {
		/**
		 * Fires on plugin init
		 *
		 * @since 1.0.0
		 */
		do_action( 'hello-plus/init' );
	}

	/**
	 * @param $class_name
	 *
	 * @return void
	 */
	public function autoload( $class_name ) {
		if ( 0 !== strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class_name ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class_name ];
			$class_to_load = $class_alias_name;
		} else {
			$class_to_load = $class_name;
		}

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = trailingslashit( HELLOPLUS_PATH ) . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include $filename;
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class_name );
		}
	}

	/**
	 * Singleton
	 *
	 * @return Plugin
	 */
	public static function instance(): Plugin {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

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
	 * Initialize all Modules
	 *
	 * @return void
	 */
	private function init_modules() {
		$modules_list = [
			'Theme',
			'Admin',
			'Content',
			'TemplateParts',
			'Forms',
		];

		foreach ( $modules_list as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

			/** @var Module_Base $class_name */
			if ( $class_name::is_active() && empty( $this->classes_aliases[ $module_name ] ) ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	}

	/**
	 * Theme private constructor.
	 */
	private function __construct() {
		static $autoloader_registered = false;

		if ( ! $autoloader_registered ) {
			$autoloader_registered = spl_autoload_register( [ $this, 'autoload' ] );
		}

		register_activation_hook( HELLOPLUS_PLUGIN_BASE, [ $this, 'activate' ] );

		add_action( 'init', [ $this, 'init' ] );

		$this->init_modules();
	}
}
