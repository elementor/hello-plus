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

	public function __construct() {
		add_action( 'elementor/documents/register', [ $this, 'register' ] );
		add_action( 'elementor/init', [ $this, 'register_remote_source' ] );
	}
}
