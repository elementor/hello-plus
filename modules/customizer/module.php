<?php

namespace HelloPlus\Modules\Customizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Includes\Module_Base;
use HelloPlus\Modules\Customizer\Classes\Customizer_Action_Links;
use HelloPlus\Modules\Customizer\Classes\Customizer_Upsell;
use HelloPlus\Theme;

class Module extends Module_Base {

	/**
	 * @inheritDoc
	 */
	public static function get_name(): string {
		return 'customizer';
	}

	/**
	 * @inheritDoc
	 */
	protected function get_component_ids(): array {
		return [];
	}

	protected function register_hooks(  ) {
		add_filter( 'hello_plus_page_title', [ $this, 'check_hide_title' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'customize_register', [ $this, 'register' ] );
		add_action( 'customize_register', [ $this, 'register_elementor_pro_upsell' ] );
	}

	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function check_hide_title( bool $val ): bool {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Theme::elementor()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}

	function register( $wp_customize ) {
		$wp_customize->add_section(
			'hello-plus-options',
			[
				'title' => esc_html__( 'Header & Footer', 'hello-plus' ),
				'capability' => 'edit_theme_options',
			]
		);

		$wp_customize->add_setting(
			'hello-plus-header-footer',
			[
				'sanitize_callback' => false,
				'transport' => 'refresh',
			]
		);

		$wp_customize->add_control(
			new Customizer_Action_Links(
				$wp_customize,
				'hello-plus-header-footer',
				[
					'section' => 'hello-plus-options',
					'priority' => 20,
				]
			)
		);
	}

	/**
	 * Register Customizer controls for Elementor Pro upsell.
	 *
	 * @return void
	 */
	function register_elementor_pro_upsell( $wp_customize ) {
		if ( function_exists( 'elementor_pro_load_plugin' ) ) {
			return;
		}

		$wp_customize->add_section(
			new Customizer_Upsell(
				$wp_customize,
				'hello-plus-upsell-elementor-pro',
				[
					'heading' => esc_html__( 'Customize your entire website with Elementor Pro', 'hello-plus' ),
					'description' => esc_html__( 'Build and customize every part of your website, including Theme Parts with Elementor Pro.', 'hello-plus' ),
					'button_text' => esc_html__( 'Upgrade Now', 'hello-plus' ),
					'button_url' => 'https://elementor.com/pro/?utm_source=hello-plus-customize&utm_campaign=gopro&utm_medium=wp-dash',
					'priority' => 999999,
				]
			)
		);
	}

	/**
	 * Enqueue Customizer CSS.
	 *
	 * @return void
	 */
	function enqueue_styles() {
		wp_enqueue_style(
			'hello-plus-customizer',
			get_template_directory_uri() . '/customizer' . Theme::get_min_suffix() . '.css',
			[],
			HELLO_PLUS_ELEMENTOR_VERSION
		);
	}

	protected function __construct() {
		$this->register_components();
		$this->register_hooks();
	}
}
