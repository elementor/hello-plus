<?php

namespace HelloPlus\Modules\Content\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

use HelloPlus\Modules\Content\Classes\Render\Widget_Zig_Zag_Render;
use HelloPlus\Modules\Theme\Module as Theme_Module;

class Zig_Zag extends Widget_Base {

	public function get_name(): string {
		return 'zigzag';
	}

	public function get_title(): string {
		return esc_html__( 'Zigzag', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLO_PLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'zigzag', 'content' ];
	}

	public function get_icon(): string {
		return 'eicon-time-line';
	}

	public function get_style_depends(): array {
		return [ 'hello-plus-zigzag' ];
	}

	protected function render(): void {
		$render_strategy = new Widget_Zig_Zag_Render( $this );

		$render_strategy->render();
	}

	protected function register_controls() {
		$this->add_content_section();
		$this->add_style_section();
	}

	protected function add_content_section() {
		$this->add_zigzags_content_section();
	}

	protected function add_style_section() {
		$this->add_style_zigzags_section();
		$this->add_box_style_section();
	}

	private function add_zigzags_content_section() {
		$this->start_controls_section(
			'zigzags_content_section',
			[
				'label' => esc_html__( 'Zigzags', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'graphic_element',
			[
				'label' => esc_html__( 'Graphic Element', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'image' => [
						'title' => esc_html__( 'Image', 'hello-plus' ),
						'icon' => 'eicon-image',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'hello-plus' ),
						'icon' => 'eicon-star',
					],
				],
				'default' => 'image',
				'toggle' => false,
			]
		);

		$this->add_graphic_element_repeater( 'image' );

		$this->add_graphic_element_repeater( 'icon' );

		$this->add_control(
			'zigzag_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'hello-plus' ),
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
				'default' => 'h2',
			]
		);

		$this->end_controls_section();
	}

	private function add_graphic_element_repeater( $type ) {
		$repeater = new Repeater();

		if ( 'icon' === $type ) {
			$repeater->add_control(
				$type . '_graphic_icon',
				[
					'label' => esc_html__( 'Icon', 'hello-plus' ),
					'type' => Controls_Manager::ICONS,
					'default' => [
						'value' => 'fas fa-star',
						'library' => 'fa-solid',
					],
				]
			);
		}

		if ( 'image' === $type ) {
			$repeater->add_control(
				$type . '_graphic_image',
				[
					'label' => esc_html__( 'Image', 'hello-plus' ),
					'type' => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
				]
			);
		}

		$repeater->add_control(
			$type . '_title',
			[
				'label' => esc_html__( 'Title', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Social media done right', 'hello-plus' ),
				'label_block' => true,
				'placeholder' => esc_html__( 'Type your title here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			$type . '_description',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( 'Unlock the full potential of social media with our expert strategies and proven techniques. Let us guide you towards success in the online world and make your brand shine on every platform.', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your description here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			$type . '_button_label',
			[
				'label' => esc_html__( 'Button', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$repeater->add_control(
			$type . '_button_text',
			[
				'label' => esc_html__( 'Text', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Learn More', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			$type . '_button_link',
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

		$repeater->add_control(
			$type . '_button_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
			]
		);

		$this->add_control(
			$type . '_zigzag_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						$type . '_title' => esc_html__( 'Social media done right', 'hello-plus' ),
						$type . '_description' => 'Unlock the full potential of social media with our expert strategies and proven techniques. Let us guide you towards success in the online world and make your brand shine on every platform.',
					],
					[
						$type . '_title' => esc_html__( 'Award-winning  studio', 'hello-plus' ),
						$type . '_description' => 'Experience the unparalleled creativity and excellence of our award-winning studio. Our team of talented artists and industry professionals are dedicated to delivering innovative and impactful designs.',
					],
					[
						$type . '_title' => esc_html__( 'Join Our Community', 'hello-plus' ),
						$type . '_description' => 'Join our vibrant community and connect with like-minded individuals who share your passions and interests. Together, we can inspire, support, and empower each other to reach our goals.',
					],
					[
						$type . '_title' => esc_html__( 'Your Perfect Match', 'hello-plus' ),
						$type . '_description' => 'Discover a personalized shopping journey. Our recommendation engine curates items tailored to your tastes. Each suggestion feels hand-picked.',
					],
				],
				'title_field' => '{{{ ' . $type . '_title }}}',
				'condition' => [
					'graphic_element' => $type,
				],
			]
		);
	}

	private function add_style_zigzags_section() {
		$this->start_controls_section(
			'style_zigzags_section',
			[
				'label' => esc_html__( 'Zigzags', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'first_zigzag_direction',
			[
				'label' => esc_html__( 'Align First Graphic', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'hello-plus' ),
						'icon' => 'eicon-order-start',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'hello-plus' ),
						'icon' => 'eicon-order-end',
					],
				],
				'default' => 'left',
				'description' => esc_html__( 'Content will be stacked on smaller screens', 'hello-plus' ),
			]
		);

		$this->add_control(
			'image_label',
			[
				'label' => esc_html__( 'Image', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Image Width', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'50%' => '50%',
					'30%' => '30%',
				],
				'default' => '50%',
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-image-width: {{VALUE}};',
				],
				'condition' => [
					'graphic_element' => 'image',
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
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-image-position: {{VALUE}}',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'icon_label',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_responsive_control(
			'icon_width',
			[
				'label' => esc_html__( 'Box Width', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'50%' => '50%',
					'30%' => '30%',
				],
				'default' => '50%',
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-icon-width: {{VALUE}};',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_zigzag_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-icon-color: {{VALUE}}',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_responsive_control(
			'icon_zigzag_size',
			[
				'label' => esc_html__( 'Icon Size', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 300,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-icon-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Content Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-start-v',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
						'icon' => 'eicon-align-center-v',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-align-end-v',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-content-position: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'style_heading',
			[
				'label' => esc_html__( 'Heading', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-title-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .ehp-zigzag__title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'style_description',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'default',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-description-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .ehp-zigzag__description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_responsive_control(
			'elements_gap',
			[
				'label' => esc_html__( 'Gap', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 200,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-elements-gap: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'style_button',
			[
				'label' => esc_html__( 'Button', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_type',
			[
				'label' => esc_html__( 'Type', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'link',
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
				'selector' => '{{WRAPPER}} .ehp-zigzag__button',
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
					'{{WRAPPER}} .ehp-zigzag__button' => 'flex-direction: {{VALUE}};',
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
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-button-icon-spacing: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-button-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ehp-zigzag__button',
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
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-button-text-color-hover: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ehp-zigzag__button:hover, {{WRAPPER}} .ehp-zigzag__button:focus',
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
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-button-border-width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'show_button_border',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'button_type',
							'operator' => '==',
							'value' => 'button',
						],
					],
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#28292B',
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-button-border-color: {{VALUE}}',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'show_button_border',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'button_type',
							'operator' => '==',
							'value' => 'button',
						],
					],
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
				'selector' => '{{WRAPPER}} .ehp-zigzag__button',
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
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-button-padding-block-end: {{BOTTOM}}{{UNIT}}; --zigzag-button-padding-block-start: {{TOP}}{{UNIT}}; --zigzag-button-padding-inline-end: {{RIGHT}}{{UNIT}}; --zigzag-button-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'button_type' => 'button',
				],
			]
		);

		$this->end_controls_section();
	}

	private function add_box_style_section() {
		$this->start_controls_section(
			'box_style_section',
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
				'selector' => '{{WRAPPER}} .ehp-zigzag__item-wrapper',

			]
		);

		$this->add_control(
			'show_alternate_background',
			[
				'label' => esc_html__( 'Alternate Background', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'alternate_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#F6F7F8',
					],
				],
				'condition' => [
					'show_alternate_background' => 'yes',
				],
				'selector' => '{{WRAPPER}} .ehp-zigzag__item-wrapper:nth-child(even)',
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
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-content-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'space_rows_label',
			[
				'label' => esc_html__( 'Rows', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'space_rows',
			[
				'label' => esc_html__( 'Space Between', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-rows-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-box-padding-block-end: {{BOTTOM}}{{UNIT}}; --zigzag-box-padding-block-start: {{TOP}}{{UNIT}}; --zigzag-box-padding-inline-end: {{RIGHT}}{{UNIT}}; --zigzag-box-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => 60,
					'right' => 0,
					'bottom' => 60,
					'left' => 0,
					'isLinked' => true,
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}
}
