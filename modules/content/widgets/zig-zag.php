<?php

namespace HelloPlus\Modules\Content\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;

use HelloPlus\Modules\Theme\Module as Theme_Module;

class Zig_Zag extends Widget_Base {

	public function get_name(): string {
		return 'zigzag';
	}

	public function get_title(): string {
		return esc_html__( 'Zig-Zag', 'hello-plus' );
	}

	public function get_icon(): string {
		return 'eicon-time-line';
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLO_PLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'zigzag', 'content' ];
	}

	public function get_style_depends(): array {
		return [ 'hello-plus-content' ];
	}

	protected function register_controls() {
		$this->add_content_section();
		$this->add_style_section();
		$this->add_advanced_section();
	}

	protected function add_content_section() {
		$this->add_layout_section();
		$this->add_blocks_section();
	}

	protected function add_style_section() {
		$this->add_blocks_style_section();
		$this->add_background_style_section();
		$this->add_border_style_section();
	}

	protected function add_advanced_section() {
		$this->add_layout_advanced_section();
		$this->add_motion_effects_section();
		$this->add_advanced_responsive_section();
		$this->add_custom_section();
	}

	private function add_layout_section() {
		$this->start_controls_section(
			'layout_section',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Image Width', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'50%' => '50%',
					'30%' => '30%',
				],
				'default' => '50%',
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-image-width: {{VALUE}};',
				],
			]
		);


		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Align Content', 'hello-plus' ),
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
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-text-alignment: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'first_block_direction',
			[
				'label' => esc_html__( 'First Image Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'row' => [
						'title' => esc_html__( 'Row', 'hello-plus' ),
						'icon' => 'eicon-order-start',
					],
					'row-reverse' => [
						'title' => esc_html__( 'Row Reverse', 'hello-plus' ),
						'icon' => 'eicon-order-end',
					],
				],
				'default' => 'row',
			]
		);

		$this->add_control(
			'important_note',
			[
				'label' => '<em>' . esc_html__( 'Note: Image position applies only on desktop.', 'hello-plus' ) . '</em>',
				'type' => Controls_Manager::HEADING,
				'separator' => 'default',
			]
		);

		$this->end_controls_section();
	}

	private function add_blocks_section(  ) {
		$this->start_controls_section(
			'Blocks_section',
			[
				'label' => esc_html__( 'Blocks', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
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
				'toggle' => true,
			]
		);

		$repeater->add_control(
			'graphic_image',
			[
				'label' => esc_html__( 'Image', 'hello-plus' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$repeater->add_control(
			'graphic_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-circle',
					'library' => 'fa-solid',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Default title', 'hello-plus' ),
				'label_block' => true,
				'placeholder' => esc_html__( 'Type your title here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( 'Default description', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your description here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Learn More', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Link', 'hello-plus' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
				],
			]
		);

		$repeater->add_control(
			'button_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
			]
		);

		$this->add_control(
			'block_items',
			[
				'label' => esc_html__( 'Block Items', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => esc_html__( 'Social media done right', 'hello-plus' ),
						'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet',
					],
					[
						'title' => esc_html__( 'Award-winning  studio', 'hello-plus' ),
						'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet',
					],
					[
						'title' => esc_html__( 'Join Our Community', 'hello-plus' ),
						'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet',
					],
					[
						'title' => esc_html__( 'Your Perfect Match', 'hello-plus' ),
						'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet',
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();
	}

	private function add_blocks_style_section() {
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Blocks', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'style_spacing',
			[
				'label' => esc_html__( 'Spacing', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'space_rows',
			[
				'label' => esc_html__( 'Space Between Rows', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-rows-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style_heading',
			[
				'label' => esc_html__( 'Heading', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-title-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .elementor-widget-zigzag__title',
			]
		);

		$this->add_control(
			'style_description',
			[
				'label' => esc_html__( 'Paragraph', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'default',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-description-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .elementor-widget-zigzag__description',
			]
		);

		$this->add_control(
			'style_button',
			[
				'label' => esc_html__( 'Button', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'default',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .elementor-widget-zigzag__button',
				'fields_options' => [
					'typography' => [ 'default' => 'yes' ],
					'text_decoration' => [
						'default' => 'underline',
					],
				]
			]
		);

		$this->add_responsive_control(
			'button_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => is_rtl() ? 'row-reverse' : 'row',
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
					'{{WRAPPER}} .elementor-widget-zigzag__button' => 'flex-direction: {{VALUE}};',
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
					'{{WRAPPER}}' => '--zigzag-button-icon-spacing: {{SIZE}}{{UNIT}};',
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
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-button-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-button-icon-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-button-background-color: {{VALUE}}',
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
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-button-text-color-hover: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_button_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-button-icon-color-hover: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-button-background-color-hover: {{VALUE}}',
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
			'button_style_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'show_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'button_border_width',
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
					'{{WRAPPER}}' => '--zigzag-button-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-button-border-color: {{VALUE}}',
				],
				'condition' => [
					'show_border' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-button-border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_border' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'zigzag_button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-widget-zigzag__button',
			]
		);

		$this->end_controls_section();
	}

	private function add_background_style_section(  ) {
		$this->start_controls_section(
			'background_style-section',
			[
				'label' => esc_html__( 'Background', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .elementor-widget-zigzag__wrapper',

			]
		);

		$this->add_control(
			'show_alternate_background',
			[
				'label' => esc_html__( 'Alternate Background', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'hello-plus' ),
				'label_off' => esc_html__( 'Hide', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'alternate_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'condition' => [
					'show_alternate_background' => 'yes',
				],
				'selector' => '{{WRAPPER}} .elementor-widget-zigzag__item-container:nth-child(even)',
			]
		);

		$this->end_controls_section();
	}

	private function add_border_style_section(  ) {
		$this->start_controls_section(
			'border_style_section',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_widget_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'hello-plus' ),
				'label_off' => esc_html__( 'Hide', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_responsive_control(
			'widget_border_width',
			[
				'label' => esc_html__( 'Border Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'show_widget_border' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-wrapper-border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'widget_border_color',
			[
				'label' => esc_html__( 'Border Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'show_widget_border' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-wrapper-border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'widget_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 2,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'show_widget_border' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--zigzag-wrapper-border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-widget-zigzag__wrapper',
			]
		);

		$this->end_controls_section();
	}

	private function add_layout_advanced_section() {
		$this->start_controls_section(
			'advanced_section',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'full_width',
			[
				'label' => __( 'Full Width', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'hello-plus' ),
				'label_off' => __( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'zigzag_width',
			[
				'label' => esc_html__( 'Zig-Zag Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1600,
						'step' => 2,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1000,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-zigzag__wrapper' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'full_width!' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'main_content_width',
			[
				'label' => esc_html__( 'Content Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-zigzag__wrapper' => 'padding-right: calc(100% - {{SIZE}}{{UNIT}}); padding-left: calc(100% - {{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'padding_top',
			[
				'label' => esc_html__( 'Top Padding', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-zigzag__wrapper' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding_bottom',
			[
				'label' => esc_html__( 'Bottom Padding', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-zigzag__wrapper' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'element_spacing',
			[
				'label' => esc_html__( 'Element Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .your-class' => 'margin: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function add_motion_effects_section() {
		$this->start_controls_section(
			'zigzag_motion_effects',
			[
				'label' => esc_html__('Motion Effects', 'hello-plus'),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'zigzag_entrance_animation',
			[
				'label' => esc_html__( 'Entrance Animation', 'hello-plus' ),
				'type' => Controls_Manager::ANIMATION,
				'default' => 'none',
			]
		);

		$this->add_control(
			'zigzag_animation_duration',
			[
				'label' => esc_html__('Animation Duration', 'hello-plus'),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'slow' => esc_html__('Slow', 'hello-plus'),
					'normal' => esc_html__('Normal', 'hello-plus'),
					'fast' => esc_html__('Fast', 'hello-plus'),
				],
				'condition' => [
					'zigzag_entrance_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'zigzag_animation_delay',
			[
				'label' => esc_html__('Animation Delay (ms)', 'hello-plus'),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 3000,
				'condition' => [
					'zigzag_entrance_animation!' => 'none',
				],
			]
		);

		$this->end_controls_section();
	}

	private function add_advanced_responsive_section() {
		$this->start_controls_section(
			'zigzag_advanced_responsive',
			[
				'label' => esc_html__( 'Responsive', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'zigzag_responsive_description',
			[
				'raw' => esc_html__( 'Responsive visibility will take effect only on preview mode or live page, and not while editing in Elementor.', 'hello-plus' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		$this->add_control(
			'zigzag_hide_on_desktop',
			[
				'label' => esc_html__( 'Hide On Desktop', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'zigzag_hide_on_tablet',
			[
				'label' => esc_html__( 'Hide On Tablet', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'zigzag_hide_on_mobile',
			[
				'label' => esc_html__( 'Hide On Mobile', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->end_controls_section();
	}

	private function add_custom_section(  ) {
		$this->start_controls_section(
			'Custom_section',
			[
				'label' => esc_html__( 'Custom', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'css_id',
			[
				'label' => esc_html__( 'CSS ID', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'title' => esc_html__( 'Add your custom ID without the "#" prefix.', 'hello-plus' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'css_classes',
			[
				'label' => esc_html__( 'CSS Classes', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'prefix_class' => '',
				'title' => esc_html__( 'Add your custom class without the dot. e.g: my-class', 'hello-plus' ),
			]
		);

		$this->add_control(
			'custom_css',
			[
				'label' => esc_html__( 'Custom CSS', 'hello-plus' ),
				'type' => Controls_Manager::CODE,
				'language' => 'css',
				'rows' => 20,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'zigzag_custom_attributes',
			[
				'label' => esc_html__( 'Custom Attributes', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'key|value', 'hello-plus' ),
				'description' => esc_html__( 'Set custom attributes for the wrapper element. Each attribute in a separate line. Separate attribute key from the value using | character.', 'hello-plus' ),
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();

/*		// Custom Attributes
		$custom_attributes = $settings['zigzag_custom_attributes'];
		$custom_attribute_string = '';
		if (!empty($custom_attributes)) {
			$custom_attributes = explode("\n", $custom_attributes);
			$custom_attributes = array_filter($custom_attributes);
			foreach ($custom_attributes as $attribute) {
				if (strpos($attribute, '|') !== false) {
					list($key, $value) = explode('|', $attribute);
					$custom_attribute_string .= ' ' . $key . '="' . esc_attr($value) . '"';
				}
			}
		}
*/
/*
		if ( 'yes' === $settings['zigzag_hide_on_desktop'] ) {
			$wrapper_classes .= ' elementor-hidden-desktop';
		}

		if ('yes' === $settings['zigzag_hide_on_tablet']) {
			$wrapper_classes .= ' elementor-hidden-tablet';
		}

		if ('yes' === $settings['zigzag_hide_on_mobile']) {
			$wrapper_classes .= ' elementor-hidden-mobile';
		}
*/
		$wrapper_classes = 'elementor-widget-zigzag__wrapper';
		$has_border = $settings['show_widget_border'];
		$animation_styles = '';

		if ( ! empty( $settings['zigzag_entrance_animation'] ) ) {
			$wrapper_classes .= ' animated ' . esc_attr( $settings['zigzag_entrance_animation'] );

			$animation_duration_map = [
				'slow' => 2000,
				'normal' => 1000,
				'fast' => 500,
			];

			$animation_duration_setting = $settings['zigzag_animation_duration'];

			if ( is_numeric( $animation_duration_setting ) ) {
				if ( $animation_duration_setting <= 500 ) {
					$animation_duration_setting = 'fast';
				} elseif ( $animation_duration_setting <= 1000 ) {
					$animation_duration_setting = 'normal';
				} else {
					$animation_duration_setting = 'slow';
				}
			}

			$animation_duration = $animation_duration_map[ $animation_duration_setting ] ?? $animation_duration_map['normal'];
			$animation_delay = isset( $settings['zigzag_animation_delay'] ) ? intval( $settings['zigzag_animation_delay'] ) : 0;

			$wrapper_classes .= sprintf( ' style="animation-duration: %dms; animation-delay: %dms;"', $animation_duration, $animation_delay );
		}

		if ( 'yes' === $has_border ) {
			$wrapper_classes .= ' has-border';
		}

		$this->add_render_attribute( 'wrapper', [
			'class' => $wrapper_classes,
		] );
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php

			$first_block_direction = $settings['first_block_direction'];

			foreach ( $settings['block_items'] as $key => $item ) {
				$is_odd = 0 !== $key % 2;

				$item_class = 'elementor-widget-zigzag__item-container ';

				$item_class .= $first_block_direction . ( $is_odd ? '-odd' : '-even' );

				$this->add_render_attribute( 'block-item-' . $key, [
					'class' => $item_class,
				] );
				?>
				<div <?php echo $this->get_render_attribute_string( 'block-item-' . $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php
						$this->render_graphic_element_container( $item, $settings );
						$this->render_text_element_container( $item, $settings );					
					?>
				</div>
				<?php
			} ?>
			</div>
		<?php
	}

	private function render_graphic_element_container( $item, $settings ) {
		if ( 'icon' === $item['graphic_element'] ) {
			$this->add_render_attribute( 'graphic_element', 'class', 'elementor-widget-zigzag__graphic-element'
			);
		}
		?>
		<div class="elementor-widget-zigzag__graphic-element-container">
			<div class="elementor-widget-zigzag__image-container">
				<?php if ( 'image' === $item['graphic_element'] && ! empty( $item['graphic_image']['url'] ) ) : ?>
					<div class="elementor-widget-zigzag__graphic-image">
						<?php Group_Control_Image_Size::print_attachment_image_html( $item, 'graphic_image' ); ?>
					</div>
				<?php elseif ( 'icon' === $item['graphic_element'] && ( ! empty( $item['graphic_icon'] ) ) ) : ?>
					<div class="elementor-widget-zigzag__graphic-icon">
						<?php Icons_Manager::render_icon( $item['graphic_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	private function render_text_element_container( $item, $settings ) {
		$button_text = $item['button_text'] ?? '';
		$button_link = $item['button_link'] ?? '';
		$button_icon = $item['button_icon'] ?? '';
		$icon_color  = $item['icon_color'] ?? '';

		$button_classnames = 'elementor-widget-zigzag__button';
		$button_hover_animation = $settings['button_hover_animation'] ?? '';
		$button_has_border = $settings['show_border'];

		$this->add_render_attribute( 'title', [
			'class' => 'elementor-widget-zigzag__title',
		] );

		$this->add_render_attribute( 'description', [
			'class' => 'elementor-widget-zigzag__description',
		] );

		if ( $button_hover_animation ) {
			$button_classnames .= ' elementor-animation-' . $button_hover_animation;
		}

		if ( 'yes' === $button_has_border ) {
			$button_classnames .= ' has-border';
		}

		$this->add_render_attribute( 'button-link', [
			'class' => $button_classnames,
		] );

		if ( ! empty( $button_link ) ) {
			$this->add_link_attributes( 'button-link', $button_link );
		}
		?>
		<div class="elementor-widget-zigzag__text-container">
			<h2 <?php echo $this->get_render_attribute_string( 'title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( $item['title'], 'hello-plus' ); ?></h2>
			<p <?php echo $this->get_render_attribute_string( 'description' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( $item['description'], 'hello-plus' ); ?></p>

			<?php if ( ! empty( $button_text ) ) { ?>
			<div class="elementor-widget-zigzag__button-container">
				<a <?php echo $this->get_render_attribute_string( 'button-link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php Icons_Manager::render_icon( $button_icon, [
						'aria-hidden' => 'true',
						'class' => 'elementor-widget-zigzag__button-icon'
					] ); ?>
					<?php echo esc_html( $button_text ); ?>
				</a>
			</div>
			<?php } ?>
		</div>
		<?php
	}
}

