<?php

namespace HelloPlus\Modules\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Includes\Module_Base;

/**
 * Theme module
 *
 * @package HelloPlus
 * @subpackage HelloPlusModules
 */
class Module extends Module_Base {
	const HELLO_PLUS_THEME_VERSION_OPTION = 'hello_plus_theme_version';
	const HELLO_PLUS_EDITOR_CATEGORY_SLUG = 'helloplus';

	/**
	 * @inheritDoc
	 */
	public static function get_name(): string {
		return 'theme';
	}

	/**
	 * @inheritDoc
	 */
	protected function get_component_ids(): array {
		return [];
	}

	/**
	 * @return void
	 */
	public function setup() {
		if ( is_admin() ) {
			$this->maybe_update_theme_version_in_db();
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
			add_editor_style( HELLO_PLUS_STYLE_PATH . 'classic-editor.css' );

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

	public function maybe_update_theme_version_in_db() {
		// The theme version saved in the database.
		$hello_plus_theme_db_version = get_option( self::HELLO_PLUS_THEME_VERSION_OPTION );

		// If the 'hello_plus_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
		if ( ! $hello_plus_theme_db_version || version_compare( $hello_plus_theme_db_version, HELLO_PLUS_ELEMENTOR_VERSION, '<' ) ) {
			update_option( self::HELLO_PLUS_THEME_VERSION_OPTION, HELLO_PLUS_ELEMENTOR_VERSION );
		}
	}

	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	public static function display_header_footer(): bool {
		return apply_filters( 'hello_plus_header_footer', true );
	}

	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	public function scripts_styles() {
		if ( apply_filters( 'hello_plus_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-plus',
				HELLO_PLUS_STYLE_URL . '/theme.css',
				[],
				HELLO_PLUS_ELEMENTOR_VERSION
			);
		}

		if ( self::display_header_footer() ) {
			wp_enqueue_style(
				'hello-plus-header-footer',
				HELLO_PLUS_STYLE_URL . '/header-footer.css',
				[],
				HELLO_PLUS_ELEMENTOR_VERSION
			);
		}
	}

	/**
	 * Register Elementor Locations.
	 *
	 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	public function register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_plus_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}

	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	public function content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_plus_content_width', 800 );
	}

	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	public static function add_description_meta_tag() {
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

	/**
	 * @param \Elementor\Elements_Manager $elements_manager
	 *
	 * @return void
	 */
	public function add_hello_plus_e_panel_categories( \Elementor\Elements_Manager $elements_manager ) {
		$elements_manager->add_category(
			self::HELLO_PLUS_EDITOR_CATEGORY_SLUG,
			[
				'title' => esc_html__( 'Hello+', 'hello-plus' ),
				'icon' => 'fa fa-plug',
			]
		);
	}

	/**
	 * @inheritDoc
	 */
	protected function register_hooks(): void {
		parent::register_hooks();
		add_action( 'wp_head', [ __NAMESPACE__ . '\Module', 'add_description_meta_tag' ] );
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'after_setup_theme', [ $this, 'content_width' ], 0 );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts_styles' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_hello_plus_e_panel_categories' ] );
		add_action( 'elementor/theme/register_locations', [ $this, 'register_elementor_locations' ] );
	}
}
