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

	public static function get_default_animations(): array {
		return [
			'Fading' => [
				'fadeIn' => 'Fade In',
				'fadeInLeft' => 'Fade In Left',
				'fadeInRight' => 'Fade In Right',
				'fadeInUp' => 'Fade In Up',
			],
			'Bouncing' => [
				'bounceIn' => 'Bounce In',
				'bounceInLeft' => 'Bounce In Left',
				'bounceInRight' => 'Bounce In Right',
				'bounceInUp' => 'Bounce In Up',
			],
			'Sliding' => [
				'slideInLeft' => 'Slide In Left',
				'slideInRight' => 'Slide In Right',
				'slideInUp' => 'Slide In Up',
			],
		];
	}
}
