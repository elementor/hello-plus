<?php
namespace HelloPlus\Modules\Content\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Control_Animation;

class Control_Zig_Zag_Animation extends Control_Animation {

	const CONTROL_TYPE = 'ehp-zigzag-animation';

	public function get_type(): string {
		return static::CONTROL_TYPE;
	}

	public static function get_animations(): array {
		return [
			'fadeInDown' => esc_html__( 'Down', 'hello-plus' ),
			'fadeInUp' => esc_html__( 'Up', 'hello-plus' ),
			'fadeInRight' => esc_html__( 'Right', 'hello-plus' ),
			'fadeInLeft' => esc_html__( 'Left', 'hello-plus' ),
			'zoomIn' => esc_html__( 'Zoom', 'hello-plus' ),
		];
	}
}
