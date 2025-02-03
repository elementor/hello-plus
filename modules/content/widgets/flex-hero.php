<?php

namespace HelloPlus\Modules\Content\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Widget_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

use Elementor\Utils as Elementor_Utils;

use HelloPlus\Modules\Content\Base\Traits\Shared_Content_Traits;
use HelloPlus\Modules\Content\Classes\Choose_Img_Control;
use HelloPlus\Modules\Content\Classes\Render\Widget_Flex_Hero_Render;
use HelloPlus\Modules\Theme\Module as Theme_Module;
use HelloPlus\Classes\Ehp_Button;

class Flex_Hero extends Widget_Base {

	use Shared_Content_Traits;

	public function get_name(): string {
		return 'flex-hero';
	}

	public function get_title(): string {
		return esc_html__( 'Flex Hero', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLOPLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'flex-hero' ];
	}

	public function get_icon(): string {
		return 'eicon-ehp-hero';
	}

	public function get_style_depends(): array {
		return [ 'helloplus-flex-hero', 'helloplus-button' ];
	}

	protected function render(): void {
		$render_strategy = new Widget_Flex_Hero_Render( $this );

		$this->add_inline_editing_attributes( 'intro_text', 'basic' );
		$this->add_inline_editing_attributes( 'heading_text', 'basic' );
		$this->add_inline_editing_attributes( 'subheading_text', 'basic' );

		$render_strategy->render();
	}

	protected function register_controls() {
		$this->add_content_section();
		$this->add_style_section();
	}

	protected function add_content_section() {
		$this->add_content_layout_section();
		$this->add_content_text_section();
		$this->add_content_cta_section();
		$this->add_content_image_section();
	}

	protected function add_style_section() {
		$this->add_style_layout_section();
		$this->add_style_content_section();
		$this->add_style_cta_section();
		$this->add_style_image_section();
		$this->add_style_box_section();
	}

	protected function add_content_layout_section() {
		$this->start_controls_section(
			'content_layout',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout_preset',
			[
				'label' => esc_html__( 'Preset', 'hello-plus' ),
				'type' => Choose_Img_Control::CONTROL_NAME,
				'default' => 'showcase',
				'label_block' => true,
				'columns' => 1,
				'options' => [
					'showcase' => [
						'title' => esc_html__( 'Showcase: Highlight key concepts with a balanced layout.', 'hello-plus' ),
						'image' => HELLOPLUS_IMAGES_URL . 'showcase.svg',
						'hover_image' => true,
					],
					'storytelling' => [
						'title' => esc_html__( 'Storytelling: Focus on a narrative with supporting visuals.', 'hello-plus' ),
						'image' => HELLOPLUS_IMAGES_URL . 'storytelling.svg',
						'hover_image' => true,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
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
			'intro_text',
			[
				'label' => esc_html__( 'Intro', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( 'Start your journey', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your description here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'intro_tag',
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

		$this->add_control(
			'heading_text',
			[
				'label' => esc_html__( 'Heading', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( 'Achieve your goals with a personal trainer who cares', 'hello-plus' ),
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
			'subheading_text',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( 'Get customized workouts, expert guidance, and the motivation you need to feel your best.', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
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
		$defaults = [
			'secondary_cta_show' => 'no',
		];
		$button = new Ehp_Button( $this, [ 'widget_name' => 'flex-hero' ], $defaults );
		$button->add_content_section();
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

	protected function add_style_layout_section() {
		$this->start_controls_section(
			'style_layout',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'layout_image_position',
			[
				'label' => esc_html__( 'Image Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => is_rtl() ? 'start' : 'end',
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-h-align-' . ( is_rtl() ? 'right' : 'left' ),
					],
					'end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-h-align-' . ( is_rtl() ? 'left' : 'right' ),
					],
				],
				'condition' => [
					'layout_preset' => 'showcase',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'layout_content_position',
			[
				'label' => esc_html__( 'Content Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-h-align-' . ( is_rtl() ? 'right' : 'left' ),
					],
					'center' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
						'icon' => 'eicon-h-align-center',
					],
				],
				'default' => 'center',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-content-position: {{VALUE}};',
				],
				'condition' => [
					'layout_preset' => 'storytelling',
				],
			]
		);

		$this->add_control(
			'layout_content_alignment',
			[
				'label' => esc_html__( 'Content Alignment', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-start-v',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
						'icon' => 'eicon-align-center-v',
					],
					'end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-align-end-v',
					],
				],
				'default' => 'center',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-content-alignment-showcase: {{VALUE}};',
				],
				'condition' => [
					'layout_preset' => 'showcase',
				],
			]
		);

		$this->add_responsive_control(
			'layout_text_alignment',
			[
				'label' => esc_html__( 'Content Alignment', 'hello-plus' ),
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
				'default' => 'center',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-content-alignment-storytelling: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'layout_preset',
							'operator' => '==',
							'value' => 'storytelling',
						],
						[
							'name' => 'layout_content_position',
							'operator' => '==',
							'value' => 'center',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'layout_content_width',
			[
				'label' => esc_html__( 'Content Width', 'hello-plus' ),
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
					'size' => 648,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 648,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 648,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-content-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_preset' => 'storytelling',
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

		$this->add_control(
			'intro_label',
			[
				'label' => esc_html__( 'Intro', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'intro_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-intro-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'intro_typography',
				'selector' => '{{WRAPPER}} .ehp-flex-hero__intro',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label' => esc_html__( 'Heading', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
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
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-heading-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .ehp-flex-hero__heading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
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
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-subheading-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subheading_typography',
				'selector' => '{{WRAPPER}} .ehp-flex-hero__subheading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_cta_section() {
		$this->start_controls_section(
			'style_cta',
			[
				'label' => esc_html__( 'CTA Button', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$button = new Ehp_Button( $this, [ 'widget_name' => 'flex-hero' ] );
		$button->add_style_controls();

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
			'image_stretch',
			[
				'label' => esc_html__( 'Stretch', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
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
						'max' => 1500,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 380,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-image-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_stretch!' => 'yes',
				],
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
						'max' => 1500,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-image-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_stretch!' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_min_height',
			[
				'label' => esc_html__( 'Min Height', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 1500,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-image-min-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_stretch' => 'yes',
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
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-image-position: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .ehp-flex-hero__image img',
			]
		);

		$this->add_control(
			'show_image_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_border_width',
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
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-image-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_image_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-image-border-color: {{VALUE}}',
				],
				'condition' => [
					'show_image_border' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_shape',
			[
				'label' => esc_html__( 'Shape', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sharp',
				'options' => [
					'sharp' => esc_html__( 'Sharp', 'hello-plus' ),
					'rounded' => esc_html__( 'Rounded', 'hello-plus' ),
					'round' => esc_html__( 'Round', 'hello-plus' ),
					'oval' => esc_html__( 'Oval', 'hello-plus' ),
					'custom' => esc_html__( 'Custom', 'hello-plus' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'image_shape_custom',
			[
				'label' => esc_html__( 'Border Radius', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-image-border-radius-custom-block-end: {{BOTTOM}}{{UNIT}}; --flex-hero-image-border-radius-custom-block-start: {{TOP}}{{UNIT}}; --flex-hero-image-border-radius-custom-inline-end: {{RIGHT}}{{UNIT}}; --flex-hero-image-border-radius-custom-inline-start: {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'image_shape' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-flex-hero__image img',
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
				'selector' => '{{WRAPPER}} .ehp-flex-hero',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'box_background_overlay_label',
			[
				'label' => esc_html__( 'Background Overlay', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_overlay',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ehp-flex-hero__overlay',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'background_overlay_opacity',
			[
				'label' => esc_html__( 'Opacity', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 0.5,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-overlay-opacity: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_element_spacing',
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
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-element-spacing: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'box_gap',
			[
				'label' => esc_html__( 'Gap', 'hello-plus' ),
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
					'size' => 60,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 60,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 60,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-gap: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_box_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'box_border_width',
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
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-box-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_box_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'box_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-box-border-color: {{VALUE}}',
				],
				'condition' => [
					'show_box_border' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'box_shape',
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

		$this->add_responsive_control(
			'box_shape_custom',
			[
				'label' => esc_html__( 'Border Radius', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-box-border-radius-custom-block-end: {{BOTTOM}}{{UNIT}}; --flex-hero-box-border-radius-custom-block-start: {{TOP}}{{UNIT}}; --flex-hero-box-border-radius-custom-inline-end: {{RIGHT}}{{UNIT}}; --flex-hero-box-border-radius-custom-inline-start: {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'box_shape' => 'custom',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-flex-hero',
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-hero' => '--flex-hero-box-padding-block-end: {{BOTTOM}}{{UNIT}}; --flex-hero-box-padding-block-start: {{TOP}}{{UNIT}}; --flex-hero-box-padding-inline-end: {{RIGHT}}{{UNIT}}; --flex-hero-box-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '60',
					'right' => '60',
					'bottom' => '60',
					'left' => '60',
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
