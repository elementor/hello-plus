<?php
namespace HelloPlus\Modules\TemplateParts\Classes\Runners;

use Elementor\Modules\FloatingButtons\Documents\Floating_Buttons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Elementor\Modules\FloatingButtons\Module as FloatingButtonsModule;
use Elementor\App\Modules\ImportExport\Runners\Import\Import_Runner_Base;
use Elementor\App\Modules\ImportExport\Utils as ImportExportUtils;

class Import_Floating_Elements extends Import_Runner_Base {

	public static function get_name(): string {
		return 'floating-elements';
	}

	public function should_import( array $data ): bool {
		return (
			isset( $data['include'] ) &&
			in_array( 'content', $data['include'], true ) &&
			! empty( $data['manifest']['content']['e-floating-buttons'] ) &&
			! empty( $data['extracted_directory_path'] )
		);
	}

	public function import( array $data, array $imported_data ) {
		$post_type = 'e-floating-buttons';
		$posts_settings = $data['manifest']['content'][ $post_type ];
		$path = $data['extracted_directory_path'] . 'content/' . $post_type . '/';
		$imported_floating_elements = $imported_data['content']['e-floating-buttons']['succeed'] ?? [];

		foreach ( $posts_settings as $id => $post_settings ) {
			$this->import_floating_element_metadata(
				$id,
				$path,
				$imported_floating_elements
			);
		}

		return [];
	}

	private function import_floating_element_metadata( $id, $path, $imported_floating_elements ) {
		$post_data = ImportExportUtils::read_json_file( $path . $id );
		$widget_type = $post_data['content'][0]['elements'][0]['widgetType'] ?? '';
		$floating_element_type = 'floating-buttons';
		$imported_post_id = $imported_floating_elements[ $id ] ?? null;

		if ( ! $imported_post_id ) {
			return;
		}

		if ( strpos( $widget_type, 'floating-bars' ) !== 0 ) {
			$floating_element_type = 'floating-bars';
			update_post_meta(
				$imported_post_id,
				FloatingButtonsModule::FLOATING_ELEMENTS_TYPE_META_KEY,
				$floating_element_type
			);
		}

		$posts = get_posts( [
			'post_type' => FloatingButtonsModule::CPT_FLOATING_BUTTONS,
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'fields' => 'ids',
			'no_found_rows' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'meta_query' => Floating_Buttons::get_meta_query_for_floating_buttons(
				$floating_element_type
			),
		] );

		foreach ( $posts as $post_id ) {
			delete_post_meta( $post_id, '_elementor_conditions' );
		}

		update_post_meta(
			$imported_post_id,
			'_elementor_conditions',
			[ 'include/general' ]
		);
	}
}
