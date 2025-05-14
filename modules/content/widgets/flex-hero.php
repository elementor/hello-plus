<?php

namespace HelloPlus\Modules\Content\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	Group_Control_Background,
	Group_Control_Box_Shadow,
	Group_Control_Typography,
	Widget_Base,
};
use Elementor\Core\Kits\Documents\Tabs\{
	Global_Colors,
	Global_Typography,
};

use HelloPlus\Modules\Content\Classes\Choose_Img_Control;
use HelloPlus\Modules\Content\Classes\Render\Widget_Flex_Hero_Render;
use HelloPlus\Modules\Theme\Module as Theme_Module;
use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Full_Height,
	Ehp_Image,
	Ehp_Padding,
	Ehp_Shapes,
	Ehp_Column_Structure,
};
use HelloPlus\Includes\Utils;

class Flex_Hero extends Widget_Base {

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

	public function get_custom_help_url(): string {
		return 'https://go.elementor.com/flex-hero-help';
	}

	public function get_icon(): string {
		return 'eicon-ehp-hero';
	}

	public function get_style_depends(): array {
		return array_merge( [ 'helloplus-flex-hero' ], Utils::get_widgets_depends() );
	}

	protected function render(): void {
		$render_strategy = new Widget_Flex_Hero_Render( $this );

		$this->add_inline_editing_attributes( 'intro_text', 'none' );
		$this->add_inline_editing_attributes( 'heading_text', 'none' );
		$this->add_inline_editing_attributes( 'subheading_text', 'none' );
		$this->add_inline_editing_attributes( 'primary_cta_button_text', 'none' );
		$this->add_inline_editing_attributes( 'secondary_cta_button_text', 'none' );

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
				'columns' => 2,
				'toggle' => false,
				'options' => [
					'showcase' => [
						'title' => wp_kses_post( "Showcase:\nHighlight key concepts\nwith a balanced layout." ),
						'image' => HELLOPLUS_IMAGES_URL . 'flex-hero-showcase.svg',
						'hover_image' => true,
					],
					'storytelling' => [
						'title' => wp_kses_post( "Storytelling:\nFocus on a narrative\nwith supporting visuals." ),
						'image' => HELLOPLUS_IMAGES_URL . 'flex-hero-storytelling.svg',
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
		$button = new Ehp_Button( $this, [ 'widget_name' => $this->get_name() ], $defaults );
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

		$image = new Ehp_Image( $this, [ 'widget_name' => $this->get_name() ] );
		$image->add_content_section();

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

		$ehp_column_structure = new Ehp_Column_Structure( $this, [
			'condition' => [
				'layout_preset' => [
					'showcase',
				],
			],
		] );

		$ehp_column_structure->add_style_controls();

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
				'separator' => 'before',
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
						'icon' => 'eicon-align-' . ( is_rtl() ? 'end' : 'start' ) . '-h',
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

		$button = new Ehp_Button( $this, [ 'widget_name' => $this->get_name() ] );
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

		$image = new Ehp_Image( $this, [ 'widget_name' => $this->get_name() ] );
		$image->add_style_controls();

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

		$ehp_shapes = new Ehp_Shapes( $this, [
			'widget_name' => $this->get_name(),
			'container_prefix' => 'box',
		] );
		$ehp_shapes->add_style_controls();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-flex-hero',
			]
		);

		$ehp_padding = new Ehp_Padding( $this, [
			'widget_name' => $this->get_name(),
			'container_prefix' => 'box',
		] );
		$ehp_padding->add_style_controls();

		$ehp_full_height = new Ehp_Full_Height( $this );
		$ehp_full_height->add_style_controls();

		$this->end_controls_section();
	}
}
