<?php

namespace HelloPlus\Modules\TemplateParts\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	// Group_Control_Background,
	// Group_Control_Box_Shadow,
	// Group_Control_Css_Filter,
	// Group_Control_Text_Shadow,
	Group_Control_Typography,
	Widget_Base,
};
use Elementor\Core\Kits\Documents\Tabs\{
	Global_Typography,
	Global_Colors,
};

class Ehp_Shared_Template_Parts {
	private $context = [];
	private ?Widget_Base $widget = null;

	const EHP_PREFIX = 'ehp-';

	private $widget_name = '';

	public function set_context( array $context ) {
		$this->context = $context;
	}

	public function add_style_brand_controls() {
		$this->widget->add_responsive_control(
			'style_logo_width',
			[
				'label' => __( 'Logo Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 68,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 68,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 68,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $this->widget_name => '--' . $this->widget_name . '-logo-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->widget->add_control(
			'style_title_heading',
			[
				'label' => esc_html__( 'Site Name', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->widget->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_title_typography',
				'selector' => '{{WRAPPER}} .ehp-' . $this->widget_name . '__site-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);
	}

	public function __construct( Widget_Base $widget, $context = [], $defaults = [] ) {
		$this->widget = $widget;
		$this->context = $context;

		$this->widget_name = $this->context['widget_name'];
	}
}