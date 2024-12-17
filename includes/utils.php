<?php
namespace HelloPlus\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * class Utils
 **/
class Utils {

	private static ?bool $elementor_installed = null;

	private static ?bool $elementor_active = null;

	public static function elementor(): \Elementor\Plugin {
		return \Elementor\Plugin::$instance;
	}

	public static function has_pro(): bool {
		return defined( 'ELEMENTOR_PRO_VERSION' );
	}

	public static function has_hello_biz(): bool {
		return defined( 'EHP_THEME_SLUG' );
	}

	public static function is_elementor_active(): bool {
		if ( null === self::$elementor_active ) {
			self::$elementor_active = defined( 'ELEMENTOR_VERSION' );
		}

		return self::$elementor_active;
	}

	public static function is_elementor_installed(): bool {
		if ( null === self::$elementor_installed ) {
			self::$elementor_installed = file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' );
		}

		return self::$elementor_installed;
	}

	public static function get_current_post_id(): int {
		if ( isset( self::elementor()->documents ) && self::elementor()->documents->get_current() ) {
			return self::elementor()->documents->get_current()->get_main_id();
		}

		return get_the_ID();
	}

	public static function get_update_elementor_message(): string {
		return sprintf(
			/* translators: %s: Elementor version number. */
			__( 'Elementor plugin version needs to be at least %s for Hello Plus to Work. Please update.', 'hello-plus' ),
			HELLOPLUS_MIN_ELEMENTOR_VERSION,
		);
	}

	public static function get_client_ip(): string {
		$server_ip_keys = [
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		];

		foreach ( $server_ip_keys as $key ) {
			$value = filter_input( INPUT_SERVER, $key, FILTER_VALIDATE_IP );

			if ( $value ) {
				return $value;
			}
		}

		return '127.0.0.1';
	}

	public static function ends_with( $full_string, $end_string ): bool {
		$len = strlen( $end_string );
		if ( 0 === $len ) {
			return true;
		}

		return ( substr( $full_string, -$len ) === $end_string );
	}

	public static function get_theme_slug(): string {
		if ( defined( 'EHP_THEME_SLUG' ) ) {
			return EHP_THEME_SLUG;
		}

		return 'hello-plus';
	}

	public static function get_theme_admin_home(): string {
		if ( defined( 'EHP_THEME_SLUG' ) ) {
			return add_query_arg( [ 'page' => EHP_THEME_SLUG ], self_admin_url( 'edit.php' ) );
		}

		return self_admin_url();
	}

	public static function is_preview_for_document( $post_id ): bool {
		$preview_id = filter_input( INPUT_GET, 'preview_id', FILTER_VALIDATE_INT );
		$preview = filter_input( INPUT_GET, 'preview', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		return 'true' === $preview && (int) $post_id === (int) $preview_id;
	}
}
