<?php

namespace HelloPlus\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	Widget_Base
};

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

class Ehp_Border {
	private $context = [];
	private ?Widget_Base $widget = null;

	private $control_prefix = '';
	private $container_prefix = '';
	private $widget_name = '';
	private $type_prefix = '';
	private $render_attribute = '';
	private $key = '';

	public function set_context( array $context ) {
		$this->context = $context;
	}

	public function get_border_classname() {
		$this->settings = $this->widget->get_settings_for_display();

		$has_border = $this->settings[ $this->control_prefix . 'show_' . $this->container_prefix . '_border'] ?? '';

		return 'yes' === $has_border ? ['has-border'] : [];
	}

	public function add_border_attributes() {
		$this->widget->add_render_attribute( $this->render_attribute . $this->key, [
			'class' => $this->get_border_classname(),
		] );
	}

	public function add_style_controls() {
		$selector_prefix = $this->type_prefix ? '-' . $this->type_prefix : '';

		$this->widget->add_control(
			$this->control_prefix . 'show_' . $this->container_prefix . '_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
				'condition' => $this->context['condition'] ?? [],
			]
		);

		$this->widget->add_control(
			$this->control_prefix . $this->container_prefix . '_border_width',
			[
				'label' => __( 'Border Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $this->widget_name => '--' . $this->widget_name . '-' . $this->container_prefix . $selector_prefix . '-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => array_merge( $this->context['condition']  ?? [], [
					$this->control_prefix . 'show_' . $this->container_prefix . '_border' => 'yes',
				]),
			]
		);

		$this->widget->add_control(
			$this->control_prefix . $this->container_prefix . '_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $this->widget_name => '--' . $this->widget_name . '-' . $this->container_prefix . $selector_prefix . '-border-color: {{VALUE}}',
				],
				'condition' => array_merge( $this->context['condition']  ?? [], [
					$this->control_prefix . 'show_' . $this->container_prefix . '_border' => 'yes',
				]),
			]
		);
	}

	public function __construct( Widget_Base $widget, $context = [] ) {
		$this->widget = $widget;
		$this->context = $context;
		
		$this->type_prefix = $this->context['type_prefix'] ?? '';
		$this->control_prefix = $this->type_prefix ? $this->type_prefix . '_' : '';
		$this->container_prefix = $this->context['container_prefix'] ?? '';
		$this->widget_name = $this->context['widget_name'] ?? '';
		$this->render_attribute = $this->context['render_attribute'] ?? '';
		$this->key = $this->context['key'] ?? '';
	}
}
