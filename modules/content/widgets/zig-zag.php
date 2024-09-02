<?php

namespace HelloPlus\Modules\Content\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use HelloPlus\Modules\Theme\Module as Theme_Module;

/**
 * class Zigzag
 **/
class Zig_Zag extends Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name(): string {
		return 'zigzag';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve list widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title(): string {
		return esc_html__( 'Zig-Zag', 'hello-plus' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve list widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-time-line';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories(): array {
		return [ Theme_Module::HELLO_PLUS_EDITOR_CATEGORY_SLUG ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the list widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords(): array {
		return [ 'zigzag', 'content' ];
	}

	/**
	 * @return string[]
	 */
	public function get_style_depends(): array {
		return [ 'hello-plus-content' ];
	}

	/**
	 * Register zigzag widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->add_layout_section();
		$this->add_blocks_section();
		$this->add_style_section();
		$this->add_background_style_section();
		$this->add_border_style_section();
		$this->add_advanced_section();
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
					'100' => '50%',
					'50' => '30%',
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => '100',
				'tablet_default' => '100',
				'mobile_default' => '100',
				'selectors' => [
					'{{WRAPPER}} .zigzag-widget-image-container img' => 'width: {{VALUE}}%;',
				],
			]
		);


		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Align Content', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'hello-plus' ),
						'icon' => 'eicon-align-start-v',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
						'icon' => 'eicon-align-center-v',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'hello-plus' ),
						'icon' => 'eicon-align-end-v',
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => 'center',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .zigzag-text-container' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'first_block_direction',
			[
				'label' => esc_html__( 'First Image Position', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
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
				'type' => \Elementor\Controls_Manager::HEADING,
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
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		/* Start repeater */

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'graphic_element',
			[
				'label' => esc_html__( 'Graphic Element', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
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
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
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
				'type' => \Elementor\Controls_Manager::ICONS,
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
				'type' => \Elementor\Controls_Manager::TEXT,
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
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( 'Default description', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your description here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		// Add a control for the button text
		$repeater->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::TEXT,
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
				'type' => \Elementor\Controls_Manager::URL,
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
				'type' => \Elementor\Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
			]
		);



		/* End repeater */

		$this->add_control(
			'block_items',
			[
				'label' => esc_html__( 'Block Items', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),           /* Use our repeater */
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

	private function add_style_section() {
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Blocks', 'hello-plus' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'style_spacing',
			[
				'label' => esc_html__( 'Spacing', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'space_rows',
			[
				'label' => esc_html__( 'Space Between Rows', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
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
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .zigzag-block-item-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style_heading',
			[
				'label' => esc_html__( 'Heading', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .zigzag-block-item-container h2' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .zigzag-block-item-container h2',
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'font_family' => [
						'default' => 'Poppins',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => 40,
						],
						'tablet_default' => [
							'unit' => 'px',
							'size' => 36,
						],
						'mobile_default' => [
							'unit' => 'px',
							'size' => 32,
						],
					],
					'line_height' => [
						'default' => [
							'unit' => 'px',
							'size' => 58,
						],
						'tablet_default' => [
							'unit' => 'px',
							'size' => 52,
						],
						'mobile_default' => [
							'unit' => 'px',
							'size' => 46,
						],
					],
				]
			]
		);

		$this->add_control(
			'style_description',
			[
				'label' => esc_html__( 'Paragraph', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'default',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .zigzag-block-item-container p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .zigzag-block-item-container p',
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'font_family' => [
						'default' => 'Poppins',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => 18,
						],
						'tablet_default' => [
							'unit' => 'px',
							'size' => 16,
						],
						'mobile_default' => [
							'unit' => 'px',
							'size' => 14,
						],
					],
					'line_height' => [
						'default' => [
							'unit' => 'px',
							'size' => 32,
						],
						'tablet_default' => [
							'unit' => 'px',
							'size' => 28,
						],
						'mobile_default' => [
							'unit' => 'px',
							'size' => 24,
						],
					],
				]
			]
		);

		$this->add_control(
			'style_button',
			[
				'label' => esc_html__( 'Button', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'default',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .zigzag-button a',
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'font_family' => [
						'default' => 'Poppins',
					],
					'font_weight' => [
						'default' => '500',
					],
					'font_size' => [
						'default' => [
							'unit' => 'px',
							'size' => 16,
						],
						'tablet_default' => [
							'unit' => 'px',
							'size' => 14,
						],
						'mobile_default' => [
							'unit' => 'px',
							'size' => 12,
						],
					],
					'line_height' => [
						'default' => [
							'unit' => 'px',
							'size' => 24,
						],
						'tablet_default' => [
							'unit' => 'px',
							'size' => 22,
						],
						'mobile_default' => [
							'unit' => 'px',
							'size' => 20,
						],
					],
					'text_decoration' => [
						'default' => 'underline',
					],
				]
			]
		);

		$this->add_control(
			'button_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'hello-plus' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'hello-plus' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'right',
				'toggle' => true,
			]
		);

		$this->add_control(
			'button_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .zigzag-button a .icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .zigzag-button a .icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
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
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .zigzag-button a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000', // Default to black if no color is selected
				'selectors' => [
					'{{WRAPPER}} .icon-left, {{WRAPPER}} .icon-right' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#00000000',
				'selectors' => [
					'{{WRAPPER}} .zigzag-button a' => 'background-color: {{VALUE}}', // Replace .your-class with .elementor-button-link
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
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .zigzag-button a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		// Button Icon Color on Hover
		$this->add_control(
			'hover_button_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .icon-left:hover, {{WRAPPER}} .icon-right:hover' => 'color: {{VALUE}}',
				],
			]
		);

		// Button Background Color on Hover
		$this->add_control(
			'hover_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .zigzag-button a:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,

			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_style_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'show_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
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
				'type' => \Elementor\Controls_Manager::SLIDER,
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
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'condition' => [
					'show_border' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .zigzag-button a' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000', // Default to black if no color is selected
				'selectors' => [
					'{{WRAPPER}} .zigzag-button a' => 'border-color: {{VALUE}}',
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
				'type' => \Elementor\Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .zigzag-button a' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'show_button_border_shadow',
			[
				'label' => esc_html__( 'Box Shadow', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'zigzag_button_box_shadow',
			[
				'label' => esc_html__( 'Box Shadow', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::BOX_SHADOW,
				'condition' => [
					'show_button_border_shadow' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .zigzag-button a' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function add_border_style_section(  ) {
		$this->start_controls_section(
			'border_style_section',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_widget_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
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
				'type' => \Elementor\Controls_Manager::SLIDER,
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
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'condition' => [
					'show_widget_border' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .zigzag-widget-container' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'widget_border_color',
			[
				'label' => esc_html__( 'Border Color', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'show_widget_border' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .zigzag-widget-container' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'widget_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
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
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [
					'show_widget_border' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .zigzag-widget-container' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'show_widget_shadow',
			[
				'label' => esc_html__( 'Box Shadow', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'show_widget_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'widget_box_shadow',
			[
				'label' => esc_html__( 'Box Shadow', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::BOX_SHADOW,
				'condition' => [
					'show_widget_shadow' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .zigzag-widget-container' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function add_background_style_section(  ) {
		$this->start_controls_section(
			'background_style-section',
			[
				'label' => esc_html__( 'Background', 'hello-plus' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .zigzag-widget-container',

			]
		);

		$this->add_control(
			'show_alternate_background',
			[
				'label' => esc_html__( 'Alternate Background', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'hello-plus' ),
				'label_off' => esc_html__( 'Hide', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'alternate_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'condition' => [
					'show_alternate_background' => 'yes',
				],
				'selector' => '{{WRAPPER}} .zigzag-block-item-container:nth-child(even)',
			]
		);

		$this->end_controls_section();
	}

	private function add_advanced_section() {
		$this->start_controls_section(
			'advanced_section',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'full_width',
			[
				'label' => __( 'Full Width', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
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
				'type' => \Elementor\Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .zigzag-widget-container' => 'width: {{SIZE}}{{UNIT}};',
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
				'type' => \Elementor\Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .zigzag-widget-container' => 'padding-right: calc(100% - {{SIZE}}{{UNIT}}); padding-left: calc(100% - {{SIZE}}{{UNIT}});',
//					'{{WRAPPER}} .zigzag-widget-container' => 'padding-right: calc(500px - {{SIZE}}{{UNIT}}); padding-left: calc(500px - {{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'padding_top',
			[
				'label' => esc_html__( 'Top Padding', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .zigzag-widget-container' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding_bottom',
			[
				'label' => esc_html__( 'Bottom Padding', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
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
					'{{WRAPPER}} .zigzag-widget-container' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'element_spacing',
			[
				'label' => esc_html__( 'Element Spacing', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::SELECT,
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
		// Motion Effects Section
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
		// Responsive Section
		$this->start_controls_section(
			'zigzag_advanced_responsive',
			[
				'label' => esc_html__( 'Responsive', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		// Responsive controls
		$this->add_control(
			'zigzag_responsive_description',
			[
				'raw' => esc_html__( 'Responsive visibility will take effect only on preview mode or live page, and not while editing in Elementor.', 'hello-plus' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		// Hide On Desktop control
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

		// Hide On Tablet control
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

		// Hide On Mobile control
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
				'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
			]
		);

		//CSS ID control
		$this->add_control(
			'css_id',
			[
				'label' => esc_html__( 'CSS ID', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'title' => esc_html__( 'Add your custom ID without the "#" prefix.', 'hello-plus' ),
				'separator' => 'before',
			]
		);

		// CSS Classes control
		$this->add_control(
			'css_classes',
			[
				'label' => esc_html__( 'CSS Classes', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'prefix_class' => '',
				'title' => esc_html__( 'Add your custom class without the dot. e.g: my-class', 'hello-plus' ),
			]
		);

		// Custom CSS control
		$this->add_control(
			'custom_css',
			[
				'label' => esc_html__( 'Custom CSS', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::CODE,
				'language' => 'css',
				'rows' => 20,
				'separator' => 'before',
			]
		);

		// Custom Attributes control
		$this->add_control(
			'zigzag_custom_attributes',
			[
				'label' => esc_html__( 'Custom Attributes', 'hello-plus' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
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

	/**
	 * Render zigzag widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
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
		$wrapper_classes = 'zigzag-widget-container';
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

			$animation_styles = sprintf( ' style="animation-duration: %dms; animation-delay: %dms;"', $animation_duration, $animation_delay );
		}

		// Open main widget container
		echo '<div class="' . esc_attr( $wrapper_classes ) . '"' . $animation_styles . '>';

		$first_block_direction = $settings['first_block_direction'];
		// Start the loop for the block items
		foreach ( $settings['block_items'] as $index => $item ) {
			// Determine if the item is odd or even
			$is_odd = 0 !== $index % 2;

			// Add a prefix to the CSS class of the item
			$item_class = $first_block_direction . ( $is_odd ? '-odd' : '-even' );

			// Start block item container
			echo '<div class="zigzag-block-item-container ' . $item_class . '">';
			$this->render_graphic_element_container( $item, $settings );
			$this->render_text_element_container( $item, $settings );
			echo '</div>';
		}
		// Close main widget container
		echo '</div>';
	}

	/**
	 * Render zigzag widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function _content_template() {
		?>
		<#
		var custom_attributes = settings.zigzag_custom_attributes;
		var custom_attribute_string = '';
		if (custom_attributes) {
		var attributes = custom_attributes.split("\n");
		_.each(attributes, function(attribute) {
		if (attribute.includes('|')) {
		var parts = attribute.split('|');
		custom_attribute_string += ' ' + parts[0] + '="' + parts[1] + '"';
		}
		});
		}

		var wrapper_classes = 'zigzag-widget-container';
		if ('yes' === settings.zigzag_hide_on_desktop) {
		wrapper_classes += ' elementor-hidden-desktop';
		}
		if ('yes' === settings.zigzag_hide_on_tablet) {
		wrapper_classes += ' elementor-hidden-tablet';
		}
		if ('yes' === settings.zigzag_hide_on_mobile) {
		wrapper_classes += ' elementor-hidden-mobile';
		}

		var animation_styles = '';
		if (settings.zigzag_entrance_animation) {
		wrapper_classes += ' animated ' + settings.zigzag_entrance_animation;

		var animation_duration_map = {
		'slow': 2000,
		'normal': 1000,
		'fast': 500,
		};

		var animation_duration_setting = settings.zigzag_animation_duration;
		if (_.isNumber(animation_duration_setting)) {
		if (animation_duration_setting <= 500) {
		animation_duration_setting = 'fast';
		} else if (animation_duration_setting <= 1000) {
		animation_duration_setting = 'normal';
		} else {
		animation_duration_setting = 'slow';
		}
		}

		var animation_duration = animation_duration_map[animation_duration_setting] || animation_duration_map['normal'];
		var animation_delay = settings.zigzag_animation_delay;

		animation_styles = ' style="';
		animation_styles += 'animation-duration: ' + animation_duration + 'ms;';
		animation_styles += 'animation-delay: ' + animation_delay + 'ms;';
		animation_styles += '"';
		}
		#>
		<div class="{{{ wrapper_classes }}}"{{{ animation_styles }}}>
			<#
			var first_block_direction = settings.first_block_direction;
			_.each(settings.block_items, function(item, index) {
			var is_odd = index % 2 == 0;
			var item_class = first_block_direction + (is_odd ? '-odd' : '-even');
			#>
			<div class="zigzag-block-item-container {{ item_class }}">
				<div class="zigzag-graphic-element-container">
					<div class="zigzag-widget-image-container">
						<# if (item.graphic_element === 'image' && item.graphic_image && item.graphic_image.url) { #>
						<img src="{{{ item.graphic_image.url }}}" alt="{{{ item.graphic_image.alt }}}" title="{{{ item.graphic_image.title }}}" class="my-custom-class">
						<# } else if (item.graphic_element === 'icon' && item.graphic_icon && item.graphic_icon.value) { #>
						<div class="hello-plus-zigzag-icon">
							<i class="{{{ item.graphic_icon.value }}}"></i>
						</div>
						<# } else { #>
						<img src="path/to/default/image.jpg" alt="">
						<# } #>
					</div>
				</div>
				<div class="zigzag-text-container">
					<h2>{{{ item.title }}}</h2>
					<p>{{{ item.description }}}</p>
					<# if (item.button_text) { #>
					<div class="zigzag-button">
						<div class="{{ settings.button_hover_animation ? 'elementor-animation-' + settings.button_hover_animation : '' }}">
							<a href="{{ item.button_link && item.button_link.url ? item.button_link.url : 'javascript:void(0);' }}">
								<# if (item.button_icon && item.button_icon.value) { #>
								<# if (settings.button_icon_position === 'left') { #>
								<i class="{{ item.button_icon.value }} icon-left" style="color: {{ item.icon_color }}"></i>
								<# } #>
								<# } #>
								{{{ item.button_text }}}
								<# if (item.button_icon && item.button_icon.value) { #>
								<# if (settings.button_icon_position === 'right') { #>
								<i class="{{ item.button_icon.value }} icon-right" style="color: {{ item.icon_color }}"></i>
								<# } #>
								<# } #>
							</a>
						</div>
					</div>
					<# } #>
				</div>
			</div>
			<# }); #>
		</div>
		<?php
	}

	private function render_graphic_element_container( $item, $settings ) {
		// Start graphic element container
		echo '<div class="zigzag-graphic-element-container">';

		// Start of the image container
		echo '<div class="zigzag-widget-image-container">';

		// Graphic Element
		if ( 'image' === $item['graphic_element'] && ! empty( $item['graphic_image']['url'] ) ) {
			// Output the image
			$this->add_render_attribute( 'image', 'src', $item['graphic_image']['url'] );
			$this->add_render_attribute( 'image', 'alt', \Elementor\Control_Media::get_image_alt( $item['graphic_image'] ) );
			$this->add_render_attribute( 'image', 'title', \Elementor\Control_Media::get_image_title( $item['graphic_image'] ) );
//			$this->add_render_attribute( 'image', 'class', 'my-custom-class' );
			echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( [ 'image' => $item['graphic_image'] ], 'thumbnail', 'image' );
		} elseif ( 'icon' === $item['graphic_element'] && !empty( $item['graphic_icon']['value'] ) ) {
			// Output the icon
			echo '<div class="hello-plus-zigzag-icon">';
			\Elementor\Icons_Manager::render_icon( $item['graphic_icon'], [ 'aria-hidden' => 'true' ] );
			echo '</div>';

		} else {
			// Output a default image
			echo '<img src="' . HELLO_PLUS_URL . '/screenshot.png' . '" alt="">';
		}

		// End of the image container
		echo '</div>';

		// End graphic element container
		echo '</div>';

	}

	private function render_text_element_container( $item, $settings ) {
		// Start text and button container
		echo '<div class="zigzag-text-container">';

		// Title
		echo '<h2>' . esc_html( $item['title'] ) . '</h2>';

		// Description
		echo '<p>' . esc_html( $item['description'] ) . '</p>';


		// Get the button text, link, and icon from the item
		$button_text = $item['button_text'] ?? 'Lean More';
		$button_link = $item['button_link']['url'] ?? '#';
		$button_icon = $item['button_icon'] ?? '';
		$icon_color  = $item['icon_color'] ?? ''; // Default to black if no color is selected

		// Get the hover animation for the button
		$button_hover_animation = $settings['button_hover_animation'] ?? '';

		if ( ! empty( $button_text ) ) :
			$btn_hover_animation_class = $button_hover_animation ? 'elementor-animation-' . $button_hover_animation : '';
			?>
			<div class="zigzag-button">
				<div class="<?php echo esc_attr( $btn_hover_animation_class ); ?>">
					<a href="<?php echo ! empty( $button_link ) ? esc_url( $button_link ) : 'javascript:void(0);'; ?>">
						<?php if ( is_array( $button_icon ) && ! empty( $button_icon['value'] ) ) : ?>
							<?php if ( $settings['button_icon_position'] === 'left' ) : ?>
								<i class="<?php echo esc_attr( $button_icon['value'] ); ?> icon-left"
								   style="color: <?php echo esc_attr( $icon_color ); ?>;"></i>
							<?php endif; ?>
						<?php endif; ?>
						<?php echo esc_html( $button_text ); ?>
						<?php if ( is_array( $button_icon ) && ! empty( $button_icon['value'] ) ) : ?>
							<?php if ( $settings['button_icon_position'] === 'right' ) : ?>
								<i class="<?php echo esc_attr( $button_icon['value'] ); ?> icon-right"
								   style="color: <?php echo esc_attr( $icon_color ); ?>;"></i>
							<?php endif; ?>
						<?php endif; ?>
					</a>
				</div>

			</div>
		<?php endif; ?>

		</div>
		<?php
	}
}

