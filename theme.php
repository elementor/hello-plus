<?php
namespace HelloPlus;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Plugin;

/**
 * Theme's main class
 *
 * @package HelloPlus
 */
final class Theme {

	/**
	 * @var ?Theme
	 */
	private static ?Theme $_instance = null;

	/**
	 * @var ?Modules_Manager
	 */
	public ?Modules_Manager $modules_manager = null;

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
			sprintf( 'Unserializing instances of the singleton "%s" class is forbidden.', get_class( $this ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'1.0.0'
		);
	}

	/**
	 * @static
	 * @access public
	 *
	 * @return \Elementor\Plugin
	 */
	public static function elementor(): Plugin {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * @static
	 * @access public
	 *
	 * @return string
	 */
	public static function get_min_suffix(): string {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	/**
	 * @param $class
	 *
	 * @return void
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class ];
			$class_to_load = $class_alias_name;
		} else {
			$class_to_load = $class;
		}

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = trailingslashit( HELLO_PLUS_PATH ) . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class );
		}
	}

	/**
	 * @return void
	 */
	private function includes() {
		require_once  HELLO_PLUS_PATH . '/includes/modules-manager.php';
		$this->modules_manager = new Modules_Manager();
	}

	/**
	 * Singleton
	 *
	 * @return Theme
	 */
	public static function instance(): Theme {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Theme private constructor.
	 */
	private function __construct() {
		static $autoloader_registered = false;
		if ( ! $autoloader_registered ) {
			$autoloader_registered = spl_autoload_register( [ $this, 'autoload' ] );
		}
		$this->includes();
	}

}

Theme::instance();