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

	private function get_options() {
		$options_names = [
			'default' => esc_html__('Default', 'hello-plus'),
			'sharp' => esc_html__('Sharp', 'hello-plus'),
			'rounded' => esc_html__('Rounded', 'hello-plus'),
			'round' => esc_html__('Round', 'hello-plus'),
			'oval' => esc_html__('Oval', 'hello-plus'),
			'custom' => esc_html__('Custom', 'hello-plus'),
		];
	
		$options = [
			'button' => ['default', 'sharp', 'rounded', 'round', 'oval', 'custom'],
			'submenu' => ['default', 'sharp', 'rounded', 'round', 'oval', 'custom'],
			'box' => ['sharp', 'rounded', 'custom'],
			'image' => ['sharp', 'rounded', 'round', 'oval', 'custom'],
		];
	
		return array_map(function ($keys) use ($options_names) {
			return array_intersect_key($options_names, array_flip($keys));
		}, $options);
	}

	public function render_shape_classnames() {
		$this->widget_settings = $this->widget->get_settings_for_display();
		$type = $this->context['container_type'];
		$content_prefix = $this->context['prefix'] ?? '';
		$prefix = $content_prefix . '_' ?? '';
		$prefix_attr = $content_prefix . '-' ?? '';
		$key = $this->context['key'] ?? '';
		$key_attr = $key ? '-' . $key : '';

		$shape = $this->widget_settings[ $prefix . $type . '_shape' ] ?? '';

		$shape_classnames = [];

		if ( ! empty( $shape ) ) {
			$shape_mobile = $this->widget_settings[ $prefix .  $type . '_shape_mobile' ];
			$shape_tablet = $this->widget_settings[ $prefix .  $type . '_shape_tablet' ];

			$shape_classnames[] = 'has-shape-' . $shape;
			$shape_classnames[] = 'shape-type-' . $type;

			if ( ! empty( $shape_mobile ) ) {
				$shape_classnames[] = 'has-shape-sm-' . $shape_mobile;
			}

			if ( ! empty( $shape_tablet ) ) {
				$shape_classnames[] = 'has-shape-md-' . $shape_tablet;
			}

			$this->widget->add_render_attribute( $this->context['render_attribute'] . $key_attr, [
				'class' => $shape_classnames,
			] );
		}
	}

	public function add_style_controls() {
		$widget_name = $this->context['widget_name'];
		$type = $this->context['container_type'];
		$context_prefix = $this->context['prefix'] ?? '';
		$condition = $this->context['condition'] ?? [];
		$is_responsive = $this->context['is_responsive'] ?? true;

		$prefix = $context_prefix . '_' ?? '';
		$prefix_attr = '-' . $context_prefix ?? '';

		$defaults = [
			'button' => 'default',
			'box' => 'sharp',
			'image' => 'sharp',
			'submenu' => 'default',
		];

		$control_options = [
			'label' => esc_html__( 'Shape', 'hello-plus' ),
			'type' => Controls_Manager::SELECT,
			'default' => $defaults[ $type ] ?? $default,
			'options' => $this->get_options()[ $type ],
			'frontend_available' => true,
			'condition' => $condition,
		];

		if ( $is_responsive ) {
			$this->widget->add_responsive_control(
				$prefix . $type . '_shape',
				$control_options
			);
		} else {
			$this->widget->add_control(
				$prefix . $type . '_shape',
				$control_options
			);
		}

		$prefix_property = "--{$widget_name}-{$type}{$prefix_attr}-border-radius";

		$custom_control_options = [
			'label' => esc_html__( 'Border Radius', 'hello-plus' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'selectors' => [
				'{{WRAPPER}} .ehp-' . $widget_name => "{$prefix_property}-block-end: {{BOTTOM}}{{UNIT}}; {$prefix_property}-block-start: {{TOP}}{{UNIT}}; {$prefix_property}-inline-end: {{RIGHT}}{{UNIT}}; {$prefix_property}-inline-start: {{LEFT}}{{UNIT}};",
			],
			'condition' => array_merge( $condition, [
				$prefix . $type . '_shape' => 'custom',
			] ),
		];

		if ( $is_responsive ) {
			$this->widget->add_responsive_control(
				$prefix . $type . '_shape_custom',
				$custom_control_options
			);
		} else {
			$this->widget->add_control(
				$prefix . $type . '_shape_custom',
				$custom_control_options
			);
		}
	}

	public function __construct( Widget_Base $widget, $context = [], $defaults = [] ) {
		$this->widget = $widget;
		$this->context = $context;
		$this->defaults = $defaults;
	}
}