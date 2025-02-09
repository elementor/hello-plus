<?php

namespace HelloPlus\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	Widget_Base
};

class Ehp_Shapes {
	private $context = [];
	private $defaults = [];
	private ?Widget_Base $widget = null;

	private $widget_settings = [];

	public function set_context( array $context ) {
		$this->context = $context;
	}

	public function render_shape_classnames() {
		$this->widget_settings = $this->widget->get_settings_for_display();
		$type = $this->context['container_type'];
		
		$shape = $this->widget_settings[ $type . '_shape' ];
		$shape_classnames = [];

		if ( ! empty( $shape ) ) {
			$shape_mobile = $this->widget_settings[ $type . '_shape_mobile' ];
			$shape_tablet = $this->widget_settings[ $type . '_shape_tablet' ];

			$shape_classnames[] = 'has-shape-' . $shape;
			$shape_classnames[] = 'shape-type-' . $type;

			if ( ! empty( $shape_mobile ) ) {
				$shape_classnames[] = 'has-shape-sm-' . $shape_mobile;
			}

			if ( ! empty( $shape_tablet ) ) {
				$shape_classnames[] = 'has-shape-md-' . $shape_tablet;
			}

			$this->widget->add_render_attribute( $this->context['render_attribute'], [
				'class' => $shape_classnames,
			] );
		}
	}

	public function add_style_controls() {
		$widget_name = $this->context['widget_name'];
		$type = $this->context['container_type'];
		$prefix = $this->context['prefix'] ?? '';

		$this->widget->add_responsive_control(
			$prefix . $type . '_shape',
			[
				'label' => esc_html__( 'Shape', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sharp',
				'options' => [
					'sharp' => esc_html__( 'Sharp', 'hello-plus' ),
					'rounded' => esc_html__( 'Rounded', 'hello-plus' ),
					'custom' => esc_html__( 'Custom', 'hello-plus' ),
				],
				'frontend_available' => true,
			]
		);

		$this->widget->add_responsive_control(
			$prefix . $type . '_shape_custom',
			[
				'label' => esc_html__( 'Border Radius', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-box-border-radius-custom-block-end: {{BOTTOM}}{{UNIT}}; --' . $widget_name . '-box-border-radius-custom-block-start: {{TOP}}{{UNIT}}; --' . $widget_name . '-box-border-radius-custom-inline-end: {{RIGHT}}{{UNIT}}; --' . $widget_name . '-box-border-radius-custom-inline-start: {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					$type . '_shape' => 'custom',
				],
			]
		);
	}

	public function __construct( Widget_Base $widget, $context = [], $defaults = [] ) {
		$this->widget = $widget;
		$this->context = $context;
		$this->defaults = $defaults;
	}
}