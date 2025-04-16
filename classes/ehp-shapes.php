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
	private ?Widget_Base $widget = null;

	private $widget_settings = [];
	private $control_prefix = '';
	private $prefix_attr = '';
	private $key_attr = '';

	public function set_context( array $context ) {
		$this->context = $context;
	}

	private function get_options() {
		$options_names = [
			'default' => esc_html__( 'Default', 'hello-plus' ),
			'sharp' => esc_html__( 'Sharp', 'hello-plus' ),
			'rounded' => esc_html__( 'Rounded', 'hello-plus' ),
			'round' => esc_html__( 'Round', 'hello-plus' ),
			'oval' => esc_html__( 'Oval', 'hello-plus' ),
			'custom' => esc_html__( 'Custom', 'hello-plus' ),
		];

		$options = [
			'button' => [ 'default', 'sharp', 'rounded', 'round', 'oval', 'custom' ],
			'submenu' => [ 'default', 'sharp', 'rounded', 'round', 'oval', 'custom' ],
			'box' => [ 'sharp', 'rounded', 'custom' ],
			'image' => [ 'sharp', 'rounded', 'round', 'oval', 'custom' ],
			'map' => [ 'sharp', 'rounded', 'round', 'oval', 'custom' ],
			'float' => [ 'default', 'sharp', 'round', 'rounded', 'custom' ],
			'logo' => [ 'sharp', 'rounded', 'round', 'custom' ],
		];

		return array_map( function ( $keys ) use ( $options_names ) {
			return array_intersect_key( $options_names, array_flip( $keys ) );
		}, $options );
	}

	public function get_shape_classnames() {
		$this->widget_settings = $this->widget->get_settings_for_display();
		$container_prefix = $this->context['container_prefix'];
		$is_responsive = $this->context['is_responsive'] ?? true;

		$shape = $this->widget_settings[ $this->control_prefix . $container_prefix . '_shape' ] ?? '';

		$shape_classnames = [];

		if ( ! empty( $shape ) ) {
			$shape_classnames[] = 'has-shape-' . $shape;
			$shape_classnames[] = 'shape-type-' . $container_prefix;

			if ( $is_responsive ) {
				$shape_mobile = $this->widget_settings[ $this->control_prefix . $container_prefix . '_shape_mobile' ];
				$shape_tablet = $this->widget_settings[ $this->control_prefix . $container_prefix . '_shape_tablet' ];

				if ( ! empty( $shape_mobile ) ) {
					$shape_classnames[] = 'has-shape-sm-' . $shape_mobile;
				}

				if ( ! empty( $shape_tablet ) ) {
					$shape_classnames[] = 'has-shape-md-' . $shape_tablet;
				}
			}
		}

		return $shape_classnames;
	}

	public function add_shape_attributes() {
		$this->widget_settings = $this->widget->get_settings_for_display();
		$shape = $this->widget_settings[ $this->control_prefix . $this->context['container_prefix'] . '_shape' ] ?? '';

		if ( ! empty( $shape ) ) {
			$shape_classnames = $this->get_shape_classnames();

			$this->widget->add_render_attribute( $this->context['render_attribute'] . $this->key_attr, [
				'class' => $shape_classnames,
			] );
		}
	}

	public function add_style_controls() {
		$widget_name = $this->context['widget_name'];
		$container_prefix = $this->context['container_prefix'];
		$condition = $this->context['condition'] ?? [];
		$is_responsive = $this->context['is_responsive'] ?? true;

		$defaults = [
			'box' => 'sharp',
			'image' => 'sharp',
			'map' => 'sharp',
			'logo' => 'sharp',
		];

		if ( $is_responsive ) {
			$this->widget->add_responsive_control(
				$this->control_prefix . $container_prefix . '_shape',
				[
					'label' => esc_html__( 'Shape', 'hello-plus' ),
					'type' => Controls_Manager::SELECT,
					'default' => $defaults[ $container_prefix ] ?? 'default',
					'options' => $this->get_options()[ $container_prefix ],
					'frontend_available' => true,
					'condition' => $condition,
				]
			);
		} else {
			$this->widget->add_control(
				$this->control_prefix . $container_prefix . '_shape',
				[
					'label' => esc_html__( 'Shape', 'hello-plus' ),
					'type' => Controls_Manager::SELECT,
					'default' => $defaults[ $container_prefix ] ?? 'default',
					'options' => $this->get_options()[ $container_prefix ],
					'frontend_available' => true,
					'condition' => $condition,
				]
			);
		}

		$prefix_property = "--{$widget_name}-{$container_prefix}{$this->prefix_attr}-border-radius";

		if ( $is_responsive ) {
			$this->widget->add_responsive_control(
				$this->control_prefix . $container_prefix . '_shape_custom',
				[
					'label' => esc_html__( 'Border Radius', 'hello-plus' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .ehp-' . $widget_name => "{$prefix_property}-block-end: {{BOTTOM}}{{UNIT}}; {$prefix_property}-block-start: {{TOP}}{{UNIT}}; {$prefix_property}-inline-end: {{RIGHT}}{{UNIT}}; {$prefix_property}-inline-start: {{LEFT}}{{UNIT}};",
					],
					'condition' => array_merge( $condition, [
						$this->control_prefix . $container_prefix . '_shape' => 'custom',
					] ),
				]
			);
		} else {
			$this->widget->add_control(
				$this->control_prefix . $container_prefix . '_shape_custom',
				[
					'label' => esc_html__( 'Border Radius', 'hello-plus' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .ehp-' . $widget_name => "{$prefix_property}-block-end: {{BOTTOM}}{{UNIT}}; {$prefix_property}-block-start: {{TOP}}{{UNIT}}; {$prefix_property}-inline-end: {{RIGHT}}{{UNIT}}; {$prefix_property}-inline-start: {{LEFT}}{{UNIT}};",
					],
					'condition' => array_merge( $condition, [
						$this->control_prefix . $container_prefix . '_shape' => 'custom',
					] ),
				]
			);
		}
	}

	public function __construct( Widget_Base $widget, $context = [] ) {
		$this->widget = $widget;
		$this->context = $context;

		$control_prefix = ( $this->context['control_prefix'] ?? $this->context['type_prefix'] ?? '' );
		$this->control_prefix = '' !== $control_prefix ? $control_prefix . '_' : '';

		$this->prefix_attr = ! empty( $this->context['type_prefix'] ) ? '-' . $this->context['type_prefix'] : '';
		$this->key_attr = ! empty( $this->context['key'] ) ? '-' . $this->context['key'] : '';
	}
}
