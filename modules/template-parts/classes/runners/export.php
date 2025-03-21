<?php
namespace HelloPlus\Modules\TemplateParts\Classes\Runners;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\App\Modules\ImportExport\Runners\Export\Export_Runner_Base;
use Elementor\Core\Base\Document;
use Elementor\TemplateLibrary\Source_Local;
use HelloPlus\Includes\Utils;

class Export extends Export_Runner_Base {
	public static function get_name(): string {
		return 'templates';
	}

	public function should_export( array $data ) {
		return (
			isset( $data['include'] ) &&
			in_array( 'templates', $data['include'], true )
		);
	}

	public function export( array $data ) {
		$template_types = array_values( Source_Local::get_template_types() );

		$query_args = [
			'post_type' => Source_Local::CPT,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query' => [
				[
					'key' => Document::TYPE_META_KEY,
					'value' => $template_types,
				],
			],
		];

		$templates_query = new \WP_Query( $query_args );

		$templates_manifest_data = [];
		$files = [];

		foreach ( $templates_query->posts as $template_post ) {
			$template_id = $template_post->ID;

			$template_document = Utils::elementor()->documents->get( $template_id );

			$templates_manifest_data[ $template_id ] = $template_document->get_export_summary();

			$files[] = [
				'path' => 'templates/' . $template_id,
				'data' => $template_document->get_export_data(),
			];
		}

		$manifest_data['templates'] = $templates_manifest_data;

		return [
			'files' => $files,
			'manifest' => [
				$manifest_data,
			],
		];
	}
}
