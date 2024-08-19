<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Customizer controls for header & footer.
 *
 * @return void
 */
function hello_plus_customizer_register( $wp_customize ) {
	require_once get_template_directory() . '/includes/customizer/customizer-action-links.php';

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
		new HelloPlus\Includes\Customizer\Customizer_Action_Links(
			$wp_customize,
			'hello-plus-header-footer',
			[
				'section' => 'hello-plus-options',
				'priority' => 20,
			]
		)
	);
}
add_action( 'customize_register', 'hello_plus_customizer_register' );

/**
 * Register Customizer controls for Elementor Pro upsell.
 *
 * @return void
 */
function hello_plus_customizer_register_elementor_pro_upsell( $wp_customize ) {
	if ( function_exists( 'elementor_pro_load_plugin' ) ) {
		return;
	}

	require_once get_template_directory() . '/includes/customizer/customizer-upsell.php';

	$wp_customize->add_section(
		new HelloPlus\Includes\Customizer\Customizer_Upsell(
			$wp_customize,
			'hello-plus-upsell-elementor-pro',
			[
				'heading' => esc_html__( 'Customize your entire website with Elementor Pro', 'hello-plus' ),
				'description' => esc_html__( 'Build and customize every part of your website, including Theme Parts with Elementor Pro.', 'hello-plus' ),
				'button_text' => esc_html__( 'Upgrade Now', 'hello-plus' ),
				'button_url' => 'https://elementor.com/pro/?utm_source=hello-theme-customize&utm_campaign=gopro&utm_medium=wp-dash',
				'priority' => 999999,
			]
		)
	);
}
add_action( 'customize_register', 'hello_plus_customizer_register_elementor_pro_upsell' );

/**
 * Enqueue Customizer CSS.
 *
 * @return void
 */
function hello_plus_customizer_styles() {

	$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style(
		'hello-plus-customizer',
		get_template_directory_uri() . '/customizer' . $min_suffix . '.css',
		[],
		HELLO_PLUS_ELEMENTOR_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'hello_plus_customizer_styles' );
