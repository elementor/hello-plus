<?php
namespace HelloPlus\Modules\TemplateParts\Classes\Runners;

use Elementor\Core\Base\Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\App\Modules\ImportExport\Runners\Import\Import_Runner_Base;
use Elementor\App\Modules\ImportExport\Utils as ImportExportUtils;
use Elementor\TemplateLibrary\Source_Local;
use HelloPlus\Includes\Utils;

class Import extends Import_Runner_Base {

	private $import_session_id;

	private $import_session_metadata = [];

	public static function get_name(): string {
		return 'templates';
	}

	public function should_import( array $data ): bool {
		return (
			isset( $data['include'] ) &&
			in_array( 'templates', $data['include'], true ) &&
			! empty( $data['extracted_directory_path'] ) &&
			! empty( $data['manifest']['templates'] )
		);
	}

	public function import( array $data, array $imported_data ) {
		$this->import_session_id = $data['session_id'];

		$path = $data['extracted_directory_path'] . 'templates/';
		$templates = $data['manifest']['templates'];

		$result['templates'] = [
			'succeed' => [],
			'failed' => [],
		];

		foreach ( $templates as $id => $template_settings ) {
			try {
				$template_data = ImportExportUtils::read_json_file( $path . $id );
				$this->unpublish_by_doc_type( $template_settings['doc_type'] );
				$import = $this->import_template( $id, $template_settings, $template_data );

				$result['templates']['succeed'][ $id ] = $import;
			} catch ( \Exception $error ) {
				$result['templates']['failed'][ $id ] = $error->getMessage();
			}
		}

		return $result;
	}

	/**
	 * Unpublish all documents of a given type.
	 * This is needed as having multiple published header or footer will result in a conflict.
	 *
	 * @param string $doc_type The document type to unpublish.
	 * @return void
	 */
	private function unpublish_by_doc_type( $doc_type ) {

		$doc_types_to_unpublish = [
			'ehp-header' => true,
			'ehp-footer' => true,
			'ehp-flex-footer' => true,
		];

		if ( ! isset( $doc_types_to_unpublish[ $doc_type ] ) ) {
			return;
		}

		$query = new \WP_Query( [
			'post_type' => Source_Local::CPT,
			'meta_key' => Document::TYPE_META_KEY ,
			'meta_value' => $doc_type,
			'post_status' => 'publish',
			'posts_per_page' => 100,
			'fields' => 'ids',
			'no_found_rows' => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		] );

		foreach ( $query->get_posts() as $post_id ) {
			wp_update_post( [
				'ID' => $post_id,
				'post_status' => 'draft',
			] );
		}
	}

	private function import_template( $id, array $template_settings, array $template_data ) {
		$doc_type = $template_settings['doc_type'];

		$new_document = Utils::elementor()->documents->create(
			$doc_type,
			[
				'post_title' => $template_settings['title'],
				'post_type' => Source_Local::CPT,
				'post_status' => 'publish',
			]
		);

		if ( is_wp_error( $new_document ) ) {
			throw new \Exception( esc_html( $new_document->get_error_message() ) );
		}

		$template_data['import_settings'] = $template_settings;
		$template_data['id'] = $id;

		$new_attachment_callback = function ( $attachment_id ) {
			$this->set_session_post_meta( $attachment_id, $this->import_session_id );
		};

		add_filter( 'elementor/template_library/import_images/new_attachment', $new_attachment_callback );

		$new_document->import( $template_data );

		remove_filter( 'elementor/template_library/import_images/new_attachment', $new_attachment_callback );

		$document_id = $new_document->get_main_id();

		$this->set_session_post_meta( $document_id, $this->import_session_id );

		$this->import_session_metadata['templates'][ $id ] = $document_id;

		return $document_id;
	}

	public function get_import_session_metadata(): array {
		return $this->import_session_metadata;
	}
}
