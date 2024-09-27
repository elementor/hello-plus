<?php

namespace HelloPlus\Modules\Content\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

use HelloPlus\Modules\Content\Base\Traits\Shared_Content_Traits;
use HelloPlus\Modules\Content\Classes\Render\Widget_CTA_Render;
use HelloPlus\Modules\Theme\Module as Theme_Module;

class CTA extends Widget_Base {

	use Shared_Content_Traits;

	public function get_name(): string {
		return 'cta';
	}

	public function get_title(): string {
		return esc_html__( 'CTA', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLO_PLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'cta' ];
	}

	public function get_icon(): string {
		return 'eicon-single-page';
	}

	public function get_style_depends(): array {
		return [ 'hello-plus-cta' ];
	}

	protected function render(): void {
		$render_strategy = new Widget_CTA_Render( $this );

		$render_strategy->render();
	}

	protected function register_controls() {
		$this->add_content_section();
		$this->add_style_section();
	}

	protected function add_content_section() {
		$this->add_content_text_section();
		$this->add_content_cta_section();
	}

	protected function add_style_section() {
		$this->add_style_section_text();
		$this->add_style_section_cta();
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
				'default' => esc_html__( 'Ready to take your business to the next level?', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
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
			'description_text',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( 'Schedule a free consultation with our team and let\'s make things happen!', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'description_tag',
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
				'default' => 'p',
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
			'primary_cta_button_text',
			[
				'label' => esc_html__( 'Primary CTA', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Schedule Now', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'primary_cta_button_link',
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
			'primary_cta_button_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
			]
		);

		$this->add_control(
			'secondary_cta_show',
			[
				'label' => esc_html__( 'Secondary CTA', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'hello-plus' ),
				'label_off' => esc_html__( 'Hide', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'secondary_cta_button_text',
			[
				'label' => esc_html__( 'Primary CTA', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Schedule Now', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'secondary_cta_show' => 'yes'
				],
			]
		);

		$this->add_control(
			'secondary_cta_button_link',
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
				'condition' => [
					'secondary_cta_show' => 'yes'
				],
			]
		);

		$this->add_control(
			'secondary_cta_button_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
				'condition' => [
					'secondary_cta_show' => 'yes'
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_section_text() {
		$this->start_controls_section(
			'style_text',
			[
				'label' => esc_html__( 'Text', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'elements_position',
			[
				'label' => esc_html__( 'Position', 'hello-plus' ),
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
				],
				'default' => 'start',
				'tablet_default' => 'start',
				'mobile_default' => 'start',
			]
		);

		$this->add_responsive_control(
			'text_width',
			[
				'label' => esc_html__( 'Text Width', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => 'Default',
					'narrow' => 'Narrow',
					'wide' => 'Wide',
				],
				'default' => 'default',
				'tablet_default' => 'default',
				'mobile_default' => 'default',
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-text-heading-width: var(--cta-text-{{VALUE}}-heading); --cta-text-description-width: var(--cta-text-{{VALUE}}-description);',
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
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-heading-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .e-cta__heading',
			]
		);

		$this->add_control(
			'description_label',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);


		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-description-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .e-cta__description',
			]
		);

		$this->add_control(
			'text_gap',
			[
				'label' => esc_html__( 'Gap', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-text-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_section_cta() {
		$this->start_controls_section(
			'style_cta',
			[
				'label' => esc_html__( 'CTA Button', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cta_position',
			[
				'label' => esc_html__( 'Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'description' => esc_html__( 'Position controls Desktop layout', 'hello-plus' ),
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-start-v',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-align-end-v',
					],
				],
				'default' => 'flex-start',
				'tablet_default' => 'flex-start',
				'mobile_default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-buttons-position: {{VALUE}};',
				],
				'condition' => [
					'elements_position' => 'start',
				],
			]
		);

		$this->add_primary_cta_button_controls();
		$this->add_secondary_cta_button_controls();

		$this->add_responsive_control(
			'cta_space_between',
			[
				'label' => esc_html__( 'Space Between', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 200,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-buttons-space-between: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'secondary_cta_show' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_primary_cta_button_controls() {
		$this->add_control(
			'primary_button_label',
			[
				'label' => esc_html__( 'Primary CTA', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'primary_button_type',
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
				'name' => 'primary_button_typography',
				'selector' => '{{WRAPPER}} .e-cta__button--primary',
			]
		);

		$this->add_responsive_control(
			'primary_button_icon_position',
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
					'{{WRAPPER}} .e-cta__button--primary' => 'flex-direction: {{VALUE}};',
				],
				'condition' => [
					'primary_cta_button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'primary_button_icon_spacing',
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
					'{{WRAPPER}} .e-cta' => '--cta-button-primary-icon-spacing: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'primary_cta_button_icon[value]!' => '',
				],
			]
		);

		$this->start_controls_tabs(
			'primary_button_style'
		);

		$this->start_controls_tab(
			'primary_button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->add_control(
			'primary_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-button-primary-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'primary_button_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .e-cta__button--primary',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
				'condition' => [
					'primary_button_type' => 'button',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'primary_button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
			]
		);

		$this->add_control(
			'primary_hover_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-button-primary-text-color-hover: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'primary_button_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .e-cta__button--primary:hover, {{WRAPPER}} .e-cta__button--primary:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
				'condition' => [
					'primary_button_type' => 'button',
				],
			]
		);

		$this->add_control(
			'primary_button_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,

			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'primary_show_button_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
				'condition' => [
					'primary_button_type' => 'button',
				],
			]
		);

		$this->add_control(
			'primary_button_border_width',
			[
				'label' => __( 'Border Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 2,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-button-primary-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'primary_show_button_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'primary_button_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-button-primary-border-color: {{VALUE}}',
				],
				'condition' => [
					'primary_show_button_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'primary_button_shape',
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
					'primary_button_type' => 'button',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'primary_button_box_shadow',
				'selector' => '{{WRAPPER}} .e-cta__button--primary',
				'condition' => [
					'primary_button_type' => 'button',
				],
			]
		);

		$this->add_responsive_control(
			'primary_button_padding',
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-button-primary-padding-block-end: {{BOTTOM}}{{UNIT}}; --cta-button-primary-padding-block-start: {{TOP}}{{UNIT}}; --cta-button-primary-padding-inline-end: {{RIGHT}}{{UNIT}}; --cta-button-primary-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'primary_button_type' => 'button',
				],
			]
		);
	}

	protected function add_secondary_cta_button_controls() {
		$this->add_control(
			'secondary_button_label',
			[
				'label' => esc_html__( 'Secondary CTA', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'secondary_cta_show' => 'yes',
				],
			]
		);

		$this->add_control(
			'secondary_button_type',
			[
				'label' => esc_html__( 'Type', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'button',
				'options' => [
					'button' => esc_html__( 'Button', 'hello-plus' ),
					'link' => esc_html__( 'Link', 'hello-plus' ),
				],
				'condition' => [
					'secondary_cta_show' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'secondary_button_typography',
				'selector' => '{{WRAPPER}} .e-cta__button--secondary',
				'condition' => [
					'secondary_cta_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'secondary_button_icon_position',
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
					'{{WRAPPER}} .e-cta__button--secondary' => 'flex-direction: {{VALUE}};',
				],
				'condition' => [
					'secondary_cta_show' => 'yes',
					'secondary_cta_button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'secondary_button_icon_spacing',
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
					'{{WRAPPER}} .e-cta' => '--cta-button-secondary-icon-spacing: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'secondary_cta_show' => 'yes',
					'secondary_cta_button_icon[value]!' => '',
				],
			]
		);

		$this->start_controls_tabs(
			'secondary_button_style',
			[
				'condition' => [
					'secondary_cta_show' => 'yes',
				],
			]
		);

		$this->start_controls_tab(
			'secondary_button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->add_control(
			'secondary_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-button-secondary-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'secondary_button_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .e-cta__button--secondary',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
				'condition' => [
					'secondary_button_type' => 'button',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'secondary_button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
			]
		);

		$this->add_control(
			'secondary_hover_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-button-secondary-text-color-hover: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'secondary_button_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .e-cta__button--secondary:hover, {{WRAPPER}} .e-cta__button--secondary:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
				'condition' => [
					'secondary_button_type' => 'button',
				],
			]
		);

		$this->add_control(
			'secondary_button_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,

			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'secondary_show_button_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
				'condition' => [
					'secondary_cta_show' => 'yes',
					'secondary_button_type' => 'button',
				],
			]
		);

		$this->add_control(
			'secondary_button_border_width',
			[
				'label' => __( 'Border Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 2,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-button-secondary-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'secondary_cta_show' => 'yes',
					'secondary_button_type' => 'button',
				],
			]
		);

		$this->add_control(
			'secondary_button_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-button-secondary-border-color: {{VALUE}}',
				],
				'condition' => [
					'secondary_cta_show' => 'yes',
					'secondary_button_type' => 'button',
				],
			]
		);

		$this->add_control(
			'secondary_button_shape',
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
					'secondary_cta_show' => 'yes',
					'secondary_button_type' => 'button',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'secondary_button_box_shadow',
				'selector' => '{{WRAPPER}} .e-cta__button--secondary',
				'condition' => [
					'secondary_cta_show' => 'yes',
					'secondary_button_type' => 'button',
				],
			]
		);

		$this->add_responsive_control(
			'secondary_button_padding',
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-button-secondary-padding-block-end: {{BOTTOM}}{{UNIT}}; --cta-button-secondary-padding-block-start: {{TOP}}{{UNIT}}; --cta-button-secondary-padding-inline-end: {{RIGHT}}{{UNIT}}; --cta-button-secondary-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'secondary_cta_show' => 'yes',
					'secondary_button_type' => 'button',
				],
			]
		);
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
				'selector' => '{{WRAPPER}} .e-cta',

			]
		);

		$this->add_responsive_control(
			'content_width',
			[
				'label' => esc_html__( 'Content Width', 'hello-plus' ),
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
					'{{WRAPPER}} .e-cta' => '--cta-content-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'box_element_spacing',
			[
				'label' => esc_html__( 'Element Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .e-cta' => '--cta-elements-gap: {{SIZE}}{{UNIT}};',
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
				'label' => esc_html__( 'Apply Full Screen Height on', 'elementor' ),
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
