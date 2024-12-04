<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Steps;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use HelloPlus\Includes\Utils;
use const Avifinfo\INVALID;

class Setup_Header extends \Elementor\Modules\Checklist\Steps\Setup_Header {
	const STEP_ID = 'setup_header_ehp';

	public function get_id() : string {
		return self::STEP_ID;
	}

	public function is_visible() : bool {
		return true;
	}

	public function is_absolute_completed() : bool {
		$args = [
			'post_type' => 'elementor_library',
			'meta_query' => [
				'relation' => 'AND',
				[
					'key' => '_elementor_template_type',
					'value' => [ 'header', 'ehp-header' ],
					'compare' => 'IN',
				],
				[
					'key' => '_elementor_conditions',
				],
			],
			'posts_per_page' => 1,
			'fields' => 'ids',
			'no_found_rows' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		];
		$query = $this->wordpress_adapter->get_query( $args );
		$header_templates = $query->posts ?? [];

		return count( $header_templates ) >= 1;
	}

	public function get_cta_url() : string {
		$base_create_url = Utils::elementor()->documents->get_create_new_post_url( 'elementor_library' );

		return add_query_arg( [ 'template_type' => 'ehp-header' ], $base_create_url );
	}
}
