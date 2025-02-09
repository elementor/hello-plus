<?php

namespace HelloPlus\Modules\TemplateParts\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Includes\Utils;
use Elementor\{
	TemplateLibrary\Source_Local,
	Modules\Library\Documents\Library_Document
};

use HelloPlus\Includes\Utils as Theme_Utils;
use Elementor\Modules\PageTemplates\Module as Page_Templates_Module;
use WP_Query;

/**
 * class Ehp_Document_Base
 **/
abstract class Ehp_Document_Base extends Library_Document {

	const LOCATION = '';

	public static function get_properties(): array {
		$properties = parent::get_properties();
		$properties['support_kit'] = true;
		$properties['show_in_finder'] = true;
		$properties['support_site_editor'] = false;
		$properties['allow_adding_widgets'] = false;
		$properties['show_navigator'] = false;
		$properties['support_page_layout'] = false;
		$properties['allow_closing_remote_library'] = false;
		$properties['library_close_title'] = esc_html__( 'Go To Dashboard', 'hello-plus' );
		$properties['publish_button_title'] = esc_html__( 'After publishing this widget, you will be able to set it as visible on the entire site in the Admin Table.', 'hello-plus' );
		/**
		 * Filter the document properties.
		 *
		 * @param array $properties The document default properties.
		 *
		 * @since 1.0.0
		 *
		 */
		return apply_filters( 'hello-plus/template-parts/document/properties', $properties );
	}

	public function print_content(): void {
		$plugin = Theme_Utils::elementor();

		if ( $plugin->preview->is_preview_mode( $this->get_main_id() ) ) {
			// PHPCS - this method is safe
			echo $plugin->preview->builder_wrapper( '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			// PHPCS - this method is safe
			echo $this->get_content(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	public function get_css_wrapper_selector(): string {
		return '.ehp-' . $this->get_main_id();
	}

	public static function get_create_url(): string {
		$base_create_url = Theme_Utils::elementor()->documents->get_create_new_post_url( Source_Local::CPT );

		return add_query_arg( [ 'template_type' => static::get_type() ], $base_create_url );
	}

	public function get_name(): string {
		return static::get_type();
	}

	protected static function get_templates_path(): string {
		return HELLOPLUS_PATH . '/modules/template-parts/templates/';
	}

	/**
	 * Retrieve the template-document post.
	 * There should be only one, so return null if not found, or found too many.
	 *
	 * @return ?int
	 */
	public static function get_document_post(): ?int {
		$posts = static::get_all_document_posts();

		return ( 1 !== count( $posts ) ) ? null : $posts[0];
	}

	public static function get_all_document_posts( array $args = [] ): array {
		$default_args = [
			'post_type' => Source_Local::CPT,
			'fields' => 'ids',
			'lazy_load_term_meta' => true,
			'tax_query' => [
				[
					'taxonomy' => static::TAXONOMY_TYPE_SLUG,
					'field' => 'slug',
					'terms' => static::get_type(),
				],
			],
		];

		$args = wp_parse_args( $args, $default_args );

		$query = new WP_Query( $args );

		return $query->posts;
	}

	public static function get_active_document(): array {
		return static::get_all_document_posts(
			[
				'post_status' => 'publish',
				'posts_per_page' => 1,
			],
		);
	}

	/**
	 * @return void
	 */
	public static function register_hooks(): void {
		$post = static::get_document_post();
		if ( is_null( $post ) ) {
			return;
		}

		if ( Theme_Utils::elementor()->preview->is_preview_mode() ) {
			$post_id = filter_input( INPUT_GET, 'elementor-preview', FILTER_VALIDATE_INT );
			$document = Theme_Utils::elementor()->documents->get( $post_id );

			if ( $document instanceof Ehp_Document_Base ) {
				return;
			}
		}

		if ( Theme_Utils::is_preview_for_document( $post ) ) {
			return;
		}

		$method_name = defined( 'ELEMENTOR_PRO_VERSION' ) ? 'maybe_get_template' : 'get_template';
		add_action( static::get_template_hook(), [ static::get_class_full_name(), $method_name ], 10, 2 );
	}

	public static function maybe_get_template( ?string $name, array $args ): void {
		if ( Utils::has_pro() ) {
			/** @var $theme_builder_module */
			$theme_builder_module = \ElementorPro\Modules\ThemeBuilder\Module::instance();
			$conditions_manager = $theme_builder_module->get_conditions_manager();

			$location_docs = $conditions_manager->get_documents_for_location( static::LOCATION );

			if ( ! empty( $location_docs ) ) {
				return;
			}
		}
		static::get_template( $name, $args );
	}

	public function save( $data ) {
		if ( empty( $data['settings']['template'] ) ) {
			$data['settings']['template'] = Page_Templates_Module::TEMPLATE_CANVAS;
		}

		$active_documents = static::get_active_document();
		if ( ! empty( $active_documents ) ) {
			$data = $this->maybe_save_as_draft( $active_documents, $data );
		}

		return parent::save( $data );
	}

	protected function maybe_save_as_draft( $active_documents, $data ): array {
		if ( ! empty( $data['settings']['post_status'] ) && 'publish' === $data['settings']['post_status'] ) {

			$document_id = $this->get_main_id();

			if ( ! in_array( $document_id, $active_documents, true ) ) {
				$data['settings']['post_status'] = 'draft';
				add_filter( 'elementor/documents/ajax_save/return_data', [ $this, 'allow_only_one_active_document' ], 10, 2 );
			}
		}

		return $data;
	}

	/**
	 * @param $return_data
	 * @param $document
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function allow_only_one_active_document( $return_data, $document ) {
		$title = static::get_title();
		/* translators: %s: Template name (e.g. Hello+ Header). */
		$message = sprintf( __( 'Looks like you already have an active %s. Please navigate to Elementor\'s "Saved Templates" area and choose one. Don\'t worry, your changes are not lost, just saved as "draft".', 'hello-plus' ), $title );
		throw new \Exception( esc_html( $message ) );
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['type'] = $this->get_name();
		$config['default_route'] = 'templates/ehp-elements';
		$config['autoImportSettings'] = true;

		return $config;
	}

	/**
	 * The WP hook for rendering the relevant template.
	 *
	 * @return string
	 */
	abstract public static function get_template_hook(): string;

	/**
	 * @param ?string $name
	 * @param array $args
	 *
	 * @return mixed
	 */
	abstract public static function get_template( ?string $name, array $args ): void;
}
