<?php

namespace HelloPlus\Modules\Settings\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Theme;

class Settings {
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
			'HelloPlus\Modules\Settings\Components\Settings::settings_page_render'
		);

		add_action( 'load-' . $menu_hook, function() {
			add_action( 'admin_enqueue_scripts', [ $this, 'settings_page_scripts' ], 10 );
		} );

	}

	/**
	 * Register settings page scripts.
	 */
	public function settings_page_scripts() {

		$dir = HELLO_PLUS_PATH . '/assets/js';
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

		if ( ! isset( $plugins['elementor/elementor.php'] ) ) {
			$action_link_type = 'install-elementor';
			$action_link_url = wp_nonce_url(
				add_query_arg(
					[
						'action' => 'install-plugin',
						'plugin' => 'elementor',
					],
					admin_url( 'update.php' )
				),
				'install-plugin_elementor'
			);
		} elseif ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			$action_link_type = 'activate-elementor';
			$action_link_url = wp_nonce_url( 'plugins.php?action=activate&plugin=elementor/elementor.php', 'activate-plugin_elementor/elementor.php' );
		} else {
			$action_link_type = '';
			$action_link_url = '';
		}

		wp_localize_script(
			$handle,
			'helloPlusAdminData',
			[
				'actionLinkType' => $action_link_type,
				'actionLinkURL' => $action_link_url,
				'templateDirectoryURI' => get_template_directory_uri(),
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

		$settings_group = 'hello_plus_settings';

		$settings = [
			'DESCRIPTION_META_TAG' => '_description_meta_tag',
			'SKIP_LINK' => '_skip_link',
			'HEADER_FOOTER' => '_header_footer',
			'PAGE_TITLE' => '_page_title',
			'HELLO_PLUS_STYLE' => '_hello_plus_style',
			'HELLO_PLUS_THEME' => '_hello_plus_theme',
		];

		$this->register_settings( $settings_group, $settings );
		$this->render_tweaks( $settings_group, $settings );
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
	 * Render theme tweaks.
	 */
	public function render_tweaks( $settings_group, $settings ) {

		$this->do_tweak( $settings_group . $settings['DESCRIPTION_META_TAG'], function() {
			remove_action( 'wp_head', 'hello_plus_add_description_meta_tag' );
		} );

		$this->do_tweak( $settings_group . $settings['SKIP_LINK'], function() {
			add_filter( 'hello_plus_enable_skip_link', '__return_false' );
		} );

		$this->do_tweak( $settings_group . $settings['HEADER_FOOTER'], function() {
			add_filter( 'hello_plus_header_footer', '__return_false' );
		} );

		$this->do_tweak( $settings_group . $settings['PAGE_TITLE'], function() {
			add_filter( 'hello_plus_page_title', '__return_false' );
		} );

		$this->do_tweak( $settings_group . $settings['HELLO_PLUS_STYLE'], function() {
			add_filter( 'hello_plus_enqueue_style', '__return_false' );
		} );

		$this->do_tweak( $settings_group . $settings['HELLO_PLUS_THEME'], function() {
			add_filter( 'hello_plus_enqueue_theme_style', '__return_false' );
		} );

	}

	public function __construct(  ) {
		add_action( 'admin_menu', [ $this, 'settings_page' ] );
		add_action( 'init', [ $this, 'tweak_settings' ], 0 );
	}
}
