<?php

namespace HelloPlus\Modules\TemplateParts\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Documents_Manager;
use HelloPlus\Includes\Utils;

/**
 * class Document
 **/
class Document {

	public function get_documents_list(): array {
		return [
			'Ehp_Header',
			'Ehp_Footer',
		];
	}

	public function get_documents_namespace(): string {
		return 'HelloPlus\Modules\TemplateParts\Documents\\';
	}

	/**
	 * Add Hello+ documents
	 *
	 * @param Documents_Manager $documents_manager
	 *
	 * @return void
	 */
	public function register( Documents_Manager $documents_manager ) {
		$documents = $this->get_documents_list();
		$namespace = $this->get_documents_namespace();

		foreach ( $documents as $document ) {
			/** @var \HelloPlus\Modules\TemplateParts\Documents\Ehp_Document_Base $doc_class */
			$doc_class = $namespace . $document;

			// add the doc type to Elementor documents:
			$documents_manager->register_document_type( $doc_class::get_type(), $doc_class );

			$doc_class::register_hooks();
		}
	}

	public function register_remote_source() {
		Utils::elementor()->templates_manager->register_source(
			'HelloPlus\Modules\TemplateParts\Classes\Sources\Source_Remote_Ehp'
		);
	}

	public function maybe_set_as_entire_site() {
		$action = sanitize_key( filter_input( INPUT_GET, 'action' ) );

		switch ( $action ) {
			case 'hello_plus_set_as_entire_site':
				$post = filter_input( INPUT_GET, 'post', FILTER_VALIDATE_INT );
				check_admin_referer( 'hello_plus_set_as_entire_site_' . $post );

				$redirect_to = filter_input( INPUT_GET, 'redirect_to', FILTER_SANITIZE_URL );

				$document = Utils::elementor()->documents->get( $post );

				if ( ! $document instanceof \HelloPlus\Modules\TemplateParts\Documents\Ehp_Document_Base ) {
					return;
				}

				$class_name = get_class( $document );
				$post_ids = $class_name::get_all_document_posts( [ 'posts_per_page' => -1 ] );

				foreach ( $post_ids as $post_id ) {
					wp_update_post( [
						'ID' => $post_id,
						'post_status' => 'draft',
					] );
				}

				wp_update_post( [
					'ID' => $post,
					'post_status' => 'publish',
				] );

				wp_safe_redirect( $redirect_to );

				exit;
			default:
				break;
		}
	}

	public function __construct() {
		add_action( 'elementor/documents/register', [ $this, 'register' ] );
		add_action( 'elementor/init', [ $this, 'register_remote_source' ] );
		add_action( 'admin_init', [ $this, 'maybe_set_as_entire_site' ] );
	}
}
