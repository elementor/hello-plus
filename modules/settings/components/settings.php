<?php

namespace HelloPlus\Modules\Settings\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Theme;

/**
 * class Settings
 *
 * @package HelloPlus
 * @subpackage HelloPlusModules
 */
class Settings {

	const SETTINGS_GROUP = 'hello_plus_settings';

	/**
	 * Register theme settings page.
	 */
	public function settings_page() {

		$menu_hook = '';

		$menu_hook = add_theme_page(
			esc_html__( 'Hello Plus Settings', 'hello-plus' ),
			esc_html__( 'Theme Settings', 'hello-plus' ),
			'manage_options',
			'hello-plus-settings',
			[ __NAMESPACE__ . '\Settings', 'settings_page_render' ]
		);

		add_action( 'load-' . $menu_hook, function() {
			add_action( 'admin_enqueue_scripts', [ $this, 'settings_page_scripts' ], 10 );
		} );

	}

	/**
	 * Register settings page scripts.
	 */
	public function settings_page_scripts() {

		$dir = HELLO_PLUS_ASSETS_PATH . '/js';
		$suffix = Theme::get_min_suffix();
		$handle = 'hello-plus-admin';
		$asset_path = "$dir/hello-plus-admin.asset.php";
		$asset_url = HELLO_PLUS_ASSETS_URL . '/js';
		if ( ! file_exists( $asset_path ) ) {
			throw new \Error( 'You need to run `npm run build` for the "hello-plus" first.' );
		}
		$script_asset = require( $asset_path );

		wp_enqueue_script(
			$handle,
			"$asset_url/$handle$suffix.js",
			$script_asset['dependencies'],
			$script_asset['version']
		);

		wp_set_script_translations( $handle, 'hello-plus' );

		wp_enqueue_style(
			$handle,
			"$asset_url/$handle$suffix.css",
			[ 'wp-components' ],
			$script_asset['version']
		);

		$plugins = get_plugins();

		$action_links_data = [];

		if ( ! isset( $plugins['elementor/elementor.php'] ) ) {
			$action_links_data[] = [
				'type' => 'install-elementor',
				'url' => wp_nonce_url(
					add_query_arg(
						[
							'action' => 'install-plugin',
							'plugin' => 'elementor',
						],
						admin_url( 'update.php' )
					),
					'install-plugin_elementor'
				),
			];
		}

		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			$action_links_data[] = [
					'type' =>  'activate-elementor',
					'url' => wp_nonce_url( 'plugins.php?action=activate&plugin=elementor/elementor.php', 'activate-plugin_elementor/elementor.php' ),
				];
		}

		if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$action_links_data[] = [
					'type' => 'go-pro',
					'url' => 'https://elementor.com/pricing-plugin',
				];
		}

		if ( ! defined( 'ELEMENTOR_AI_VERSION' ) ) {
			$action_links_data[] = [
				'type' => 'go-ai',
				'url' => 'https://elementor.com/pricing-ai',
			];
		}

		if ( ! defined( 'ELEMENTOR_IMAGE_OPTIMIZER_VERSION' ) ) {
			$action_links_data[] = [
				'type' => 'go-image-optimizer',
				'url' => 'https://elementor.com/pricing-plugin',
			];
		}

		wp_localize_script(
			$handle,
			'helloPlusAdminData',
			[
				'links' => $action_links_data,
				'templateDirectoryURI' => HELLO_PLUS_URL,
			]
		);
	}

	/**
	 * Render settings page wrapper element.
	 */
	public static function settings_page_render() {
		?>
		<div id="hello-plus-settings"></div>
		<?php
	}

	/**
	 * Theme tweaks & settings.
	 */
	public function tweak_settings() {
		$settings = [
			'DESCRIPTION_META_TAG' => '_description_meta_tag',
			'SKIP_LINK' => '_skip_link',
			'HEADER_FOOTER' => '_header_footer',
			'PAGE_TITLE' => '_page_title',
			'HELLO_PLUS_STYLE' => '_hello_plus_style',
			'HELLO_PLUS_THEME' => '_hello_plus_theme',
		];

		/**
		 * allow child theme and 3rd party plugins to extend the Theme settings
		 *
		 * @param array $settings , the filtered value, array of key/value settings options pairs
		 */
		$settings = apply_filters( 'hello-plus/settings', $settings );

		$this->register_settings( self::SETTINGS_GROUP, $settings );
		$this->render_tweaks( self::SETTINGS_GROUP, $settings );
	}

	/**
	 * Register theme settings.
	 */
	public function register_settings( $settings_group, $settings ) {

		foreach ( $settings as $setting_key => $setting_value ) {
			register_setting(
				$settings_group,
				$settings_group . $setting_value,
				[
					'default' => '',
					'show_in_rest' => true,
					'type' => 'string',
				]
			);
		}

	}

	/**
	 * Run a tweak only if the user requested it.
	 */
	private function do_tweak( $setting, $tweak_callback ) {

		$option = get_option( $setting );
		if ( isset( $option ) && ( 'true' === $option ) && is_callable( $tweak_callback ) ) {
			$tweak_callback();
		}

	}

	/**
	 * @return array
	 */
	private function get_default_action_tweaks(): array {
		$tweaks = [
			'DESCRIPTION_META_TAG' => [
				'hook'      => 'wp_head',
				'callback'  => [
					'HelloPlus\Modules\Theme\Module',
					'add_description_meta_tag'
				],
			],
		];

		/**
		 * Allow child theme and 3rd party plugins to extend the list of tweaks controlled by the Theme settings
		 */
		return apply_filters( 'hello-plus/settings/tweaks-list/actions', $tweaks );
	}

	/**
	 * @return array
	 */
	private function get_default_filter_tweaks(): array {
		$tweaks = [
			'SKIP_LINK' => [
				'hook'      => 'hello_plus_enable_skip_link',
				'callback'  => '__return_false',
			],
			'HEADER_FOOTER' => [
				'hook'      => 'hello_plus_header_footer',
				'callback'  => '__return_false',
			],
			'PAGE_TITLE' => [
				'hook'      => 'hello_plus_page_title',
				'callback'  => '__return_false',
			],
			'HELLO_PLUS_STYLE' => [
				'hook'      => 'hello_plus_enqueue_style',
				'callback'  => '__return_false',
			],
			'HELLO_PLUS_THEME' => [
				'hook'      => 'hello_plus_enqueue_theme_style',
				'callback'  => '__return_false',
			]
		];

		/**
		 * Allow child theme and 3rd party plugins to extend the list of tweaks controlled by the Theme settings
		 */
		return apply_filters( 'hello-plus/settings/tweaks-list/filters', $tweaks );
	}
	/**
	 * Render theme tweaks.
	 */
	public function render_tweaks( $settings_group, $settings ) {

		$tweaks = $this->get_default_action_tweaks();

		foreach ( $tweaks as $tweak_key => $tweak_value ) {
			$this->do_tweak( $settings_group . $settings[ $tweak_key ], function () use ( $tweak_value ) {
				remove_action( $tweak_value[ 'hook' ], $tweak_value[ 'callback' ] );
			} );
		}

		$tweaks = $this->get_default_filter_tweaks();

		foreach ( $tweaks as $tweak_key => $tweak_value ) {
			$this->do_tweak( $settings_group . $settings[ $tweak_key ], function () use ( $tweak_value ) {
				add_filter( $tweak_value[ 'hook' ], $tweak_value[ 'callback' ] );
			} );
		}
	}

	/**
	 * class constructor
	 */
	public function __construct(  ) {
		add_action( 'admin_menu', [ $this, 'settings_page' ] );
		add_action( 'init', [ $this, 'tweak_settings' ], 0 );
	}
}
