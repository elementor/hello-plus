<?php
/**
 * Theme functions and definitions
 *
 * @package HelloPlus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_PLUS_ELEMENTOR_VERSION', '0.0.1' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists('hello_plus_elementor_setup') ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_plus_elementor_setup() {
		if ( is_admin() ) {
			hello_plus_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_plus_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-plus' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-plus' ) ] );
		}

		if ( apply_filters( 'hello_plus_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_plus_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'classic-editor.css' );

			/*
			 * Gutenberg wide images.
			 */
			add_theme_support( 'align-wide' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_plus_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_plus_elementor_setup');

function hello_plus_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_plus_theme_version';
	// The theme version saved in the database.
	$hello_plus_theme_db_version = get_option( $theme_version_option_name );

	// If the 'HELLO_PLUS_THEME_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_plus_theme_db_version || version_compare( $hello_plus_theme_db_version, HELLO_PLUS_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_PLUS_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_plus_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function hello_plus_display_header_footer(): bool {
		return apply_filters( 'hello_plus_header_footer', true );
	}
}

if ( ! function_exists( 'hello_plus_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_plus_scripts_styles() {
		$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_plus_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-plus',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_PLUS_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_plus_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-plus-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_PLUS_ELEMENTOR_VERSION
			);
		}

		if ( hello_plus_display_header_footer() ) {
			wp_enqueue_style(
				'hello-plus-header-footer',
				get_template_directory_uri() . '/header-footer' . $min_suffix . '.css',
				[],
				HELLO_PLUS_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_plus_scripts_styles' );

if ( ! function_exists( 'hello_plus_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_plus_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_plus_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_plus_register_elementor_locations' );

if ( ! function_exists( 'hello_plus_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_plus_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_plus_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_plus_content_width', 0 );

if ( ! function_exists( 'hello_plus_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_plus_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_plus_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_plus_add_description_meta_tag' );

// Admin notice
if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

// Add Hello-Plus widgets
require get_template_directory() . '/includes/widgets-manager.php';

if ( ! function_exists( 'hello_plus_customizer' ) ) {
	// Customizer controls
	function hello_plus_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! hello_plus_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_plus_customizer' );

if ( ! function_exists( 'hello_plus_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_plus_check_hide_title( bool $val ): bool {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_plus_page_title', 'hello_plus_check_hide_title' );
