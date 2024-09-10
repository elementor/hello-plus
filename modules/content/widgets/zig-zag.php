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
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

use HelloPlus\Modules\Content\Classes\Render\Widget_Zig_Zag_Render;
use HelloPlus\Modules\Theme\Module as Theme_Module;

class Zig_Zag extends Widget_Base {

	public function get_name(): string {
		return 'zigzag';
	}

	public function get_title(): string {
		return esc_html__( 'Zig-Zag', 'hello-plus' );
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
		return [ 'hello-plus-content' ];
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
		$this->add_layout_section();
		$this->add_blocks_section();
	}

	protected function add_style_section() {
		$this->add_blocks_style_section();
		$this->add_background_style_section();
		$this->add_border_style_section();
	}

	private function add_layout_section() {
		$this->start_controls_section(
			'layout_section',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-image-width: {{VALUE}};',
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-text-alignment: {{VALUE}};',
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
				'type' => Controls_Manager::REPEATER,
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-rows-spacing: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-title-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .e-zigzag__title',
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-description-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .e-zigzag__description',
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
				'selector' => '{{WRAPPER}} .e-zigzag__button',
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
					'{{WRAPPER}} .e-zigzag__button' => 'flex-direction: {{VALUE}};',
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-button-icon-spacing: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-button-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-zigzag' => '--zigzag-button-icon-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-zigzag' => '--zigzag-button-background-color: {{VALUE}}',
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-button-text-color-hover: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_button_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-zigzag' => '--zigzag-button-icon-color-hover: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .e-zigzag' => '--zigzag-button-background-color-hover: {{VALUE}}',
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-button-border-width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-button-border-color: {{VALUE}}',
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-button-border-radius: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .e-zigzag__button',
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
				'selector' => '{{WRAPPER}} .e-zigzag',

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
				'selector' => '{{WRAPPER}} .e-zigzag__item-container:nth-child(even)',
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-wrapper-border-width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-wrapper-border-color: {{VALUE}}',
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
					'{{WRAPPER}} .e-zigzag' => '--zigzag-wrapper-border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_shadow',
				'selector' => '{{WRAPPER}} .e-zigzag',
			]
		);

		$this->end_controls_section();
	}
}

