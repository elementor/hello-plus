<?php

namespace HelloPlus\Modules\Content\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

use Elementor\Utils as Elementor_Utils;

use HelloPlus\Modules\Content\Base\Traits\Shared_Content_Traits;
use HelloPlus\Modules\Content\Classes\Render\Widget_Hero_Render;
use HelloPlus\Modules\Theme\Module as Theme_Module;

class Hero extends Widget_Base {

	use Shared_Content_Traits;

	public function get_name(): string {
		return 'hero';
	}

	public function get_title(): string {
		return esc_html__( 'Hero', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLOPLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'hero' ];
	}

	public function get_icon(): string {
		return 'eicon-ehp-hero';
	}

	public function get_style_depends(): array {
		return [ 'helloplus-hero' ];
	}

	public function get_script_depends(): array {
		return [ 'helloplus-content-fe' ];
	}

	protected function render(): void {
		$render_strategy = new Widget_Hero_Render( $this );

		$render_strategy->render();
	}

	protected function register_controls() {
		$this->add_content_section();
		$this->add_style_section();
	}

	protected function add_content_section() {
		$this->add_content_text_section();
		$this->add_content_cta_section();
		$this->add_content_image_section();
	}

	protected function add_style_section() {
		$this->add_style_content_section();
		$this->add_style_image_section();
		$this->add_style_box_section();
	}

	protected function add_content_text_section() {
		$this->start_controls_section(
			'content_text',
			[
				'label' => esc_html__( 'Text', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'heading_text',
			[
				'label' => esc_html__( 'Heading', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( '360 digital marketing agency that gets results', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your description here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'heading_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'subheading_text',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( 'Schedule a free consultation with our team and let’s make things happen!', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your description here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'subheading_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_cta_section() {
		$this->start_controls_section(
			'content_cta',
			[
				'label' => esc_html__( 'CTA Button', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'cta_button_text',
			[
				'label' => esc_html__( 'Text', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Contact us', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'cta_button_link',
			[
				'label' => esc_html__( 'Link', 'hello-plus' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '',
					'is_external' => true,
				],
			]
		);

		$this->add_control(
			'cta_button_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_image_section() {
		$this->start_controls_section(
			'content_image',
			[
				'label' => esc_html__( 'Image', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'hello-plus' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Elementor_Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_content_section() {
		$this->start_controls_section(
			'style_content',
			[
				'label' => esc_html__( 'Content', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_position',
			[
				'label' => esc_html__( 'Content Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-start-h',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
						'icon' => 'eicon-align-center-h',
					],
					'end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-align-end-h',
					],
				],
				'default' => 'center',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-content-position: {{VALUE}}; --hero-content-text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label' => esc_html__( 'Heading', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-heading-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .ehp-hero__heading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_responsive_control(
			'text_width_heading',
			[
				'label' => esc_html__( 'Text Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 1200,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 800,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 800,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 800,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-text-width-heading: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'subheading_label',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subheading_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-subheading-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subheading_typography',
				'selector' => '{{WRAPPER}} .ehp-hero__subheading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_responsive_control(
			'text_width_subheading',
			[
				'label' => esc_html__( 'Text Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 1200,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 440,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 440,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 440,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-text-width-subheading: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_label',
			[
				'label' => esc_html__( 'CTA Button', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_type',
			[
				'label' => esc_html__( 'Type', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'button',
				'options' => [
					'button' => esc_html__( 'Button', 'hello-plus' ),
					'link' => esc_html__( 'Link', 'hello-plus' ),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .ehp-hero__button',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
			]
		);

		$this->add_responsive_control(
			'button_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => is_rtl() ? 'row' : 'row-reverse',
				'toggle' => false,
				'options' => [
					'row' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-h-align-left',
					],
					'row-reverse' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary' => [
					'left' => is_rtl() ? 'row-reverse' : 'row',
					'right' => is_rtl() ? 'row' : 'row-reverse',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero__button' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 5,
					],
					'rem' => [
						'max' => 5,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-button-icon-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'button_style'
		);

		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-button-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ehp-hero__button',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
				'condition' => [
					'button_type' => 'button',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
			]
		);

		$this->add_control(
			'hover_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-button-text-color-hover: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ehp-hero__button:hover, {{WRAPPER}} .ehp-hero__button:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => Global_Colors::COLOR_ACCENT,
					],
				],
				'condition' => [
					'button_type' => 'button',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,

			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'show_button_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
				'condition' => [
					'button_type' => 'button',
				],
			]
		);

		$this->add_control(
			'button_border_width',
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
					'{{WRAPPER}} .ehp-hero' => '--hero-button-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_button_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-button-border-color: {{VALUE}}',
				],
				'condition' => [
					'show_button_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_shape',
			[
				'label' => esc_html__( 'Shape', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
					'sharp' => esc_html__( 'Sharp', 'hello-plus' ),
					'round' => esc_html__( 'Round', 'hello-plus' ),
					'rounded' => esc_html__( 'Rounded', 'hello-plus' ),
				],
				'condition' => [
					'button_type' => 'button',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-hero__button',
				'condition' => [
					'button_type' => 'button',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-button-padding-block-end: {{BOTTOM}}{{UNIT}}; --hero-button-padding-block-start: {{TOP}}{{UNIT}}; --hero-button-padding-inline-end: {{RIGHT}}{{UNIT}}; --hero-button-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'button_type' => 'button',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_image_section() {
		$this->start_controls_section(
			'style_image_section',
			[
				'label' => esc_html__( 'Image', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_full_width',
			[
				'label' => esc_html__( 'Full Width', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 1600,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 1304,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-image-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_full_width!' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => esc_html__( 'Height', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 1600,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-image-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_position',
			[
				'label' => esc_html__( 'Position', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'desktop_default' => 'center center',
				'tablet_default' => 'center center',
				'mobile_default' => 'center center',
				'options' => [
					'' => esc_html__( 'Default', 'hello-plus' ),
					'center center' => esc_html__( 'Center Center', 'hello-plus' ),
					'center left' => esc_html__( 'Center Left', 'hello-plus' ),
					'center right' => esc_html__( 'Center Right', 'hello-plus' ),
					'top center' => esc_html__( 'Top Center', 'hello-plus' ),
					'top left' => esc_html__( 'Top Left', 'hello-plus' ),
					'top right' => esc_html__( 'Top Right', 'hello-plus' ),
					'bottom center' => esc_html__( 'Bottom Center', 'hello-plus' ),
					'bottom left' => esc_html__( 'Bottom Left', 'hello-plus' ),
					'bottom right' => esc_html__( 'Bottom Right', 'hello-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-image-position: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_box_section() {
		$this->start_controls_section(
			'style_box_section',
			[
				'label' => esc_html__( 'Box', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'box_background_label',
			[
				'label' => esc_html__( 'Background', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ehp-hero',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_responsive_control(
			'content_gap',
			[
				'label' => esc_html__( 'Element Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 5,
					],
					'rem' => [
						'max' => 5,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 40,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 28,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-content-text-gap: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'style_box_padding',
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-hero' => '--hero-box-padding-block-end: {{BOTTOM}}{{UNIT}}; --hero-box-padding-block-start: {{TOP}}{{UNIT}}; --hero-box-padding-inline-end: {{RIGHT}}{{UNIT}}; --hero-box-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => 60,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'box_full_screen_height',
			[
				'label' => esc_html__( 'Full Screen Height', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => '',
				'tablet_default' => '',
				'mobile_default' => '',
				'separator' => 'before',
			]
		);

		$configured_breakpoints = $this->get_configured_breakpoints();

		$this->add_control(
			'box_full_screen_height_controls',
			[
				'label' => esc_html__( 'Apply Full Screen Height on', 'hello-plus' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $configured_breakpoints['devices_options'],
				'default' => $configured_breakpoints['active_devices'],
				'condition' => [
					'box_full_screen_height' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}
}
