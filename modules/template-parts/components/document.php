<?php

namespace HelloPlus\Modules\TemplateParts\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Documents_Manager;

/**
 * class Document
 **/
class Document {

	private function get_documents_list(): array {
		return [
			'Header',
			'Footer',
		];
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

		foreach ( $documents as $document ) {
			$doc_class = '\HelloPlus\Modules\TemplateParts\Documents\\' . $document;

			// add the doc type to Elementor documents:
			$documents_manager->register_document_type( $doc_class::get_type(), $doc_class );

			/** @var \HelloPlus\Modules\TemplateParts\Documents\Document_Base $doc_class */
//			$doc_class::register();

			$doc_class::register_hooks();
		}
	}

	public function __construct() {
		add_action( 'elementor/documents/register', [ $this, 'register' ] );
	}
}
