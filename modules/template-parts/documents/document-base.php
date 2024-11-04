<?php

namespace HelloPlus\Modules\TemplateParts\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


use Elementor\{
	Controls_Manager,
	TemplateLibrary\Source_Local,
	Modules\Library\Documents\Library_Document,
	Utils as ElementorUtils
};
use HelloPlus\Includes\Utils as Theme_Utils;
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
		$properties['show_navigator'] = false;
		$properties['support_page_layout'] = false;
		$properties['allow_closing_remote_library'] = false;

		return apply_filters( 'hello-plus/template-parts/document/properties', $properties );
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

	public static function register() {
		if ( static::is_creating_document() || static::is_editing_existing_document() ) {
			Controls_Manager::add_tab(
				Header::get_advanced_tab_id(),
				esc_html__( 'Advanced', 'hello-plus' )
			);

			Controls_Manager::add_tab(
				Footer::get_advanced_tab_id(),
				esc_html__( 'Advanced', 'hello-plus' )
			);
		}
	}

	public function get_css_wrapper_selector(): string {
		return '.ehp-' . $this->get_main_id();
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

	public static function get_advanced_tab_id() {
		return 'advanced-tab-' . static::get_type();
	}

	public static function is_editing_existing_document(): bool {
		$action = ElementorUtils::get_super_global_value( $_GET, 'action' ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification is not required.
		$post_id = ElementorUtils::get_super_global_value( $_GET, 'post' ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification is not required.

		return 'elementor' === $action && static::is_current_doc_meta_key( $post_id );
	}

	public static function is_creating_document(): bool {
		$action = ElementorUtils::get_super_global_value( $_POST, 'action' ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
		$post_id = ElementorUtils::get_super_global_value( $_POST, 'editor_post_id' ); //phpcs:ignore WordPress.Security.NonceVerification.Missing

		return 'elementor_ajax' === $action && static::is_current_doc_meta_key( $post_id );
	}

	public static function is_current_doc_meta_key( $post_id ): bool {
		return static::get_type() === get_post_meta( $post_id, \Elementor\Core\Base\Document::TYPE_META_KEY, true );
	}

	/**
	 * Retrieve the template-document post.
	 * There should be only one, so return null if not found, or found too many.
	 *
	 * @return ?int
	 */
	public static function get_document_post(): ?int {
		$args  = array(
			'post_type' => 'elementor_library',
			'fields' => 'ids',
			'lazy_load_term_meta' => true,
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
		add_filter( 'elementor/widget/common/register_css_attributes_control', [ static::get_class_full_name(), 'register_css_attributes_control' ] );
	}

	/**
	 * @param bool $common_controls
	 * @param \Elementor\Widget_Common $common_widget
	 *
	 * @return bool
	 */
	public static function register_css_attributes_control( bool $common_controls ): bool {
		if ( static::is_creating_document() || static::is_editing_existing_document() ) {
			return false;
		}

		return $common_controls;
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
