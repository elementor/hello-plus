<?php

namespace HelloPlus\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	Widget_Base
};

class Ehp_Padding {
	private $context = [];
	private ?Widget_Base $widget = null;

	public function set_context( array $context ) {
		$this->context = $context;
	}

	public function render_selectors() {
		$widget_name = $this->context['widget_name'];
		$container_prefix = $this->context['container_prefix'] ?? '';
		$type_prefix = $this->context['type_prefix'] ?? '';

		$prefix = implode( '-', array_filter( [
			$widget_name,
			$container_prefix,
			$type_prefix,
		] ) );

		$is_rtl = is_rtl();
		$inline_end_value = $is_rtl ? 'LEFT' : 'RIGHT';
		$inline_start_value = $is_rtl ? 'RIGHT' : 'LEFT';

		$properties = [
			'padding-block-end' => '{{BOTTOM}}{{UNIT}}',
			'padding-block-start' => '{{TOP}}{{UNIT}}',
			'padding-inline-end' => "{{{$inline_end_value}}}{{UNIT}}",
			'padding-inline-start' => "{{{$inline_start_value}}}{{UNIT}}",
		];

		$css_rules = array_map( fn( $value, $prop ) => "--{$prefix}-{$prop}: {$value};", $properties, array_keys( $properties ) );

		return implode( ' ', $css_rules );
	}

	public function add_style_controls() {
		$widget_name = $this->context['widget_name'];
		$condition = $this->context['condition'] ?? [];

		$container_prefix = $this->context['container_prefix'] ?? '';
		$type_prefix = $this->context['type_prefix'] ?? '';
		$padding_prefix = implode( '_', array_filter( [ $type_prefix, $container_prefix, 'padding' ] ) );

		$this->widget->add_responsive_control(
			$padding_prefix,
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $widget_name => $this->render_selectors(),
				],
				'default' => $this->context['default_padding'] ?? [
					'top' => '60',
					'right' => '60',
					'bottom' => '60',
					'left' => '60',
					'unit' => 'px',
				],
				'tablet_default' => $this->context['tablet_default_padding'] ?? [],
				'mobile_default' => $this->context['mobile_default_padding'] ?? [],
				'separator' => $this->context['separator'] ?? 'before',
				'condition' => $condition,
			]
		);
	}

	public function __construct( Widget_Base $widget, $context = [] ) {
		$this->widget = $widget;
		$this->context = $context;
	}
}
