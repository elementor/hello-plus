<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Steps;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Core\Utils\Promotions\Filtered_Promotions_Manager;
use HelloPlus\Includes\Utils;

class Setup_Header extends \Elementor\Modules\Checklist\Steps\Step_Base {
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
				'relation' => 'OR',
				[
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
				[
					[
						'key' => '_elementor_template_type',
						'value' => 'ehp-header',
						'compare' => '=',
					],
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
		return add_query_arg( [ 'page' => EHP_THEME_SLUG ], self_admin_url( 'edit.php' ) );
	}

	public function get_cta_text() : string {
		return esc_html__( 'Add a Hello+ Header', 'hello-plus' );
	}

	public function get_title() : string {
		return esc_html__( 'Set up a Hello+ header', 'hello-plus' );
	}

	public function get_description() : string {
		return esc_html__( 'This element applies across different pages, so visitors can easily navigate around your site.', 'hello-plus' );
	}

	// TODO: replace the image
	public function get_image_src() : string {
		return 'https://assets.elementor.com/checklist/v1/images/checklist-step-4.jpg';
	}

	public function get_is_completion_immutable() : bool {
		return false;
	}

	// TODO: replace the link
	public function get_learn_more_url() : string {
		return 'https://go.elementor.com/app-website-checklist-header-article';
	}
}
