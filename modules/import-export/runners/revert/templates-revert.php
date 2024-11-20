<?php

namespace HelloPlus\Modules\ImportExport\Runners\Revert;

use Elementor\App\Modules\ImportExport\Runners\Revert\Revert_Runner_Base;
use Elementor\Core\Base\Document;

use Elementor\TemplateLibrary\Source_Local;
use HelloPlus\Includes\Utils;

class Templates_Revert extends Revert_Runner_Base {

	public static function get_name() : string {
		return 'templates';
	}

	public function should_revert( array $data ) : bool {
		return (
			isset( $data['runners'] ) &&
			array_key_exists( static::get_name(), $data['runners'] )
		);
	}

	public function revert( array $data ) {
		$template_types = array_values( Source_Local::get_template_types() );

		$query_args = [
			'post_type' => Source_Local::CPT,
			'post_status' => 'any',
			'posts_per_page' => -1,
			'meta_query' => [
				[
					'key' => Document::TYPE_META_KEY,
					'value' => $template_types,
				],
				[
					'key' => static::META_KEY_ELEMENTOR_IMPORT_SESSION_ID,
					'value' => $data['session_id'],
				],
			],
		];

		$templates_query = new \WP_Query( $query_args );

		foreach ( $templates_query->posts as $template_post ) {
			$template_document = Utils::elementor()->documents->get( $template_post->ID );
			$template_document->delete();
		}
	}
}
