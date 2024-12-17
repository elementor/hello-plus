<?php
namespace HelloPlus\Modules\TemplateParts\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use HelloPlus\Includes\Utils;
use HelloPlus\Modules\TemplateParts\Classes\Steps\Setup_Header;

class Checklist {
	public function __construct() {
		// Run after Pro did:
		add_filter( 'elementor/checklist/steps', [ $this, 'replace_steps' ], 2 );
	}

	/**
	 * @param array $steps
	 * @return \Elementor\Modules\Checklist\Steps\Step_Base[]
	 */
	public function replace_steps( array $steps ): array {
		$formatted_steps = [];

		$module = \Elementor\Modules\Checklist\Module::instance();
		$has_pro = Utils::has_pro();

		$elementor_step_id = ! $has_pro ? \Elementor\Modules\Checklist\Steps\Setup_Header::STEP_ID : \ElementorPro\Modules\Checklist\Steps\Setup_Header::STEP_ID;

		foreach ( $steps as $step_id => $step ) {
			if ( $elementor_step_id !== $step_id || $step->is_absolute_completed() ) {
				$formatted_steps[ $step_id ] = $step;
				continue;
			}

			$header_step = new Setup_Header( $module );
			$formatted_steps[ $header_step->get_id() ] = $header_step;
		}

		return $formatted_steps;
	}
}
