<?php
namespace HelloPlus\Modules\Content\Classes;

use Elementor\Control_Animation;

class Control_ZigZag_Animation extends Control_Animation {

	const CONTROL_TYPE = 'ehp-control-zigzag-animation';

	public function get_type() {
		return self::CONTROL_TYPE;
	}

	public static function get_animations(): array {
		return [
			'fadeInDown' => esc_html__( 'Down', 'elementor-pro' ),
			'fadeInUp' => esc_html__( 'Up', 'elementor-pro' ),
			'fadeInRight' => esc_html__( 'Right', 'elementor-pro' ),
			'fadeInLeft' => esc_html__( 'Left', 'elementor-pro' ),
			'zoomIn' => esc_html__( 'Zoom', 'elementor-pro' ),
		];
	}
}