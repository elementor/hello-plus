<?php

namespace HelloPlus\Modules\TemplateParts\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Core\DocumentTypes\Post,
	TemplateLibrary\Source_Local,
	Modules\Library\Documents\Library_Document
};
use HelloPlus\Includes\Utils as Theme_Utils;
use WP_Post;
use WP_Query;

/**
 * class Document_Base
 **/
abstract class Document_Base extends Library_Document {

	public static function get_properties(): array {
		$properties = parent::get_properties();
		$properties['support_kit'] = true;
		$properties['show_in_finder'] = true;
		$properties['support_site_editor'] = true;
		$properties['support_conditions'] = true;
		$properties['support_lazyload'] = false;
		$properties['condition_type'] = 'general';
		$properties['allow_adding_widgets'] = false;
		$properties['support_page_layout'] = false;
		$properties['allow_closing_remote_library'] = false;

		return $properties;
	}

	public function print_content() {
		$plugin = Theme_Utils::elementor();

		if ( $plugin->preview->is_preview_mode( $this->get_main_id() ) ) {
			// PHPCS - the method builder_wrapper is safe.
			echo $plugin->preview->builder_wrapper( '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			// PHPCS - the method get_content is safe.
			echo $this->get_content(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	public function get_css_wrapper_selector(): string {
		return '.ehp-' . $this->get_main_id();
	}

//	protected static function get_editor_panel_categories(): array {
//		return [ Module::HELLO_PLUS_EDITOR_CATEGORY_SLUG ];
//	}

// TODO: which controls do we need?
	protected function register_controls() {
		parent::register_controls();

		Post::register_style_controls( $this );

		$this->update_control(
			'section_page_style',
			[
				'label' => esc_html__( 'Style', 'elementor-pro' ),
			]
		);
	}

	protected function get_remote_library_config(): array {
		$config = parent::get_remote_library_config();

		$config['category'] = $this->get_name(); //Header_Footer_Base

		return $config;
	}

	public static function get_create_url(): string {
		$base_create_url = Theme_Utils::elementor()->documents->get_create_new_post_url( Source_Local::CPT );

		return add_query_arg( [ 'template_type' => static::get_type() ], $base_create_url );
	}


	public function get_name(): string {
		return static::get_type();
	}

	protected static function get_templates_path(): string {
		return HELLO_PLUS_PATH . '/modules/template-parts/templates/';
	}

	/**
	 * Retrieve the template-document post.
	 * There should be only one, so return null if not found, or found too many.
	 *
	 * @return ?WP_Post
	 */
	public static function get_document_post(): ?WP_Post {
		static $posts = null;

		if ( is_null( $posts ) ) {
			$args  = array(
				'post_type' => 'elementor_library',
				'tax_query' => [
					[
						'taxonomy' => self::TAXONOMY_TYPE_SLUG,
						'field'    => 'slug',
						'terms'    => static::get_type(),
					],
				],
			);
			$query = new WP_Query( $args );
			$posts = $query->posts;
		}
		return ( 1 !== count( $posts ) ) ? null : $posts[0];
	}

	/**
	 * @return void
	 */
	public static function register_hooks(): void {
		if ( is_null( static::get_document_post() ) ) {
			return;
		}

		add_action( static::get_template_hook(), [ static::get_class_full_name(), 'get_template' ], 10, 2 );
	}

	/**
	 * The WP hook for rendering the relevant template.
	 *
	 * @return string
	 */
	abstract public static function get_template_hook(): string;

	/**
	 * @param string $name
	 * @param array $args
	 *
	 * @return mixed
	 */
	abstract public static function get_template( string $name, array $args );
}
