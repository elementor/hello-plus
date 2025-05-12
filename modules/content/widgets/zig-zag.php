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
	Repeater,
	Widget_Base,
	Utils as Elementor_Utils,
};
use Elementor\Core\Kits\Documents\Tabs\{
	Global_Typography,
	Global_Colors
};

use HelloPlus\Modules\Content\Classes\{
	Control_Zig_Zag_Animation,
	Render\Widget_Zig_Zag_Render
};
use HelloPlus\Modules\Content\Traits\Widget_Repeater_Editable;
use HelloPlus\Modules\Theme\Module as Theme_Module;
use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Image,
	Ehp_Padding,
};
use HelloPlus\Includes\Utils;

class Zig_Zag extends Widget_Base {

	use Widget_Repeater_Editable;

	public function get_name(): string {
		return 'zigzag';
	}

	public function get_title(): string {
		return esc_html__( 'Zigzag', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLOPLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'zigzag', 'content' ];
	}

	public function get_icon(): string {
		return 'eicon-ehp-zigzag';
	}

	public function get_style_depends(): array {
		return array_merge( [ 'helloplus-zigzag' ], Utils::get_widgets_depends() );
	}

	public function get_script_depends(): array {
		return [ 'helloplus-zigzag-fe' ];
	}

	public function get_custom_help_url(): string {
		return 'https://go.elementor.com/zigzag-help';
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
		$this->add_style_layout_section();
		$this->add_style_text_section();
		$this->add_style_cta_section();
		$this->add_style_image_section();
		$this->add_style_icon_section();
		$this->add_style_box_section();
		$this->add_style_alternate_section();
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
						'url' => Elementor_Utils::get_placeholder_image_src(),
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
				'label' => esc_html__( 'CTA Button', 'hello-plus' ),
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

	private function add_style_layout_section() {
		$this->start_controls_section(
			'style_layout_section',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'first_zigzag_direction',
			[
				'label' => esc_html__( 'Align First Graphic', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-order-' . ( is_rtl() ? 'end' : 'start' ),
					],
					'end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-order-' . ( is_rtl() ? 'start' : 'end' ),
					],
				],
				'default' => 'start',
				'description' => esc_html__( 'Zigzag content will be stacked on smaller screens', 'hello-plus' ),
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
			]
		);

		$this->end_controls_section();
	}

	private function add_style_image_section() {
		$this->start_controls_section(
			'style_image_section',
			[
				'label' => esc_html__( 'Image', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$defaults = [
			'has_image_width_slider' => false,
			'has_image_width_dropdown' => true,
		];

		$image = new Ehp_Image( $this, [ 'widget_name' => $this->get_name() ], $defaults );
		$image->add_style_controls();

		$this->end_controls_section();
	}

	private function add_style_icon_section() {
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
			]
		);

		$this->end_controls_section();
	}

	private function add_style_text_section() {
		$this->start_controls_section(
			'style_text_section',
			[
				'label' => esc_html__( 'Text', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
				'separator' => 'before',
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

		$this->end_controls_section();
	}

	private function add_style_cta_section() {
		$this->start_controls_section(
			'style_cta_section',
			[
				'label' => esc_html__( 'CTA Button', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$defaults = [
			'has_secondary_cta' => false,
			'button_default_type' => 'link',
		];

		$button = new Ehp_Button( $this, [ 'widget_name' => $this->get_name() ], $defaults );
		$button->add_button_type_controls(
			[
				'type' => 'primary',
				'ignore_icon_value_condition' => true,
			]
		);

		$this->end_controls_section();
	}

	private function add_style_box_section() {
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
				'selector' => '{{WRAPPER}} .ehp-zigzag__item-wrapper',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => esc_html__( 'Column Gap', 'hello-plus' ),
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
				'default' => [
					'size' => 100,
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
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-column-gap: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => esc_html__( 'Row Gap', 'hello-plus' ),
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
				'default' => [
					'size' => 120,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 40,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 32,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$padding = new Ehp_Padding( $this, [
			'widget_name' => $this->get_name(),
			'container_prefix' => 'box',
			'default_padding' => [
				'top' => 60,
				'right' => 0,
				'bottom' => 60,
				'left' => 0,
				'unit' => 'px',
			],
		] );
		$padding->add_style_controls();

		$this->add_control(
			'animation_label',
			[
				'label' => esc_html__( 'Motion Effects', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'zigzag_animation',
			[
				'label' => esc_html__( 'Sequenced Entrance Animation', 'hello-plus' ),
				'type' => Control_Zig_Zag_Animation::CONTROL_TYPE,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'zigzag_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'slow' => esc_html__( 'Slow', 'hello-plus' ),
					'normal' => esc_html__( 'Normal', 'hello-plus' ),
					'fast' => esc_html__( 'Fast', 'hello-plus' ),
				],
				'default' => 'normal',
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-animation-duration: var(--zigzag-animation-duration-{{VALUE}});',
				],
				'condition' => [
					'zigzag_animation!' => '',
				],
			]
		);
		$this->add_control(
			'animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'hello-plus' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'min' => 0,
				'step' => 100,
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-animation-delay: {{VALUE}}ms;',
				],
				'condition' => [
					'zigzag_animation!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_alternate_section() {
		$this->start_controls_section(
			'style_alternate_section',
			[
				'label' => esc_html__( 'Alternate Row Style', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'has_alternate_text_styles',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'alternate_title_color',
			[
				'label' => esc_html__( 'Heading Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag__item-wrapper:nth-child(even) .ehp-zigzag__title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'has_alternate_text_styles' => 'yes',
				],
			]
		);

		$this->add_control(
			'alternate_description_color',
			[
				'label' => esc_html__( 'Description Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag__item-wrapper:nth-child(even) .ehp-zigzag__description' => 'color: {{VALUE}}',
				],
				'condition' => [
					'has_alternate_text_styles' => 'yes',
				],
			]
		);

		$this->add_control(
			'has_alternate_button_styles',
			[
				'label' => esc_html__( 'CTA Button', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs(
			'alternate_button_tabs',
			[
				'condition' => [
					'has_alternate_button_styles' => 'yes',
				],
			]
		);

		$this->start_controls_tab(
			'alternate_button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->add_control(
			'alternate_button_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-button-primary-text-color-alternate: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'zigzag_button_alternate_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'condition' => [
					'primary_button_type' => 'button',
					'has_alternate_button_styles' => 'yes',
				],
				'selector' => '{{WRAPPER}} .ehp-zigzag__item-wrapper:nth-child(even) .is-type-button.ehp-zigzag__button--primary',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'alternate_button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
			]
		);

		$this->add_control(
			'alternate_button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-button-primary-text-color-hover-alternate: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'zigzag_button_alternate_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'condition' => [
					'primary_button_type' => 'button',
					'has_alternate_button_styles' => 'yes',
				],
				'selector' => '{{WRAPPER}} .ehp-zigzag__item-wrapper:nth-child(even) .is-type-button.ehp-zigzag__button--primary:hover, {{WRAPPER}} .ehp-zigzag__item-wrapper:nth-child(even) .is-type-button.ehp-zigzag__button--primary:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'has_alternate_button_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'primary_button_type' => 'button',
					'has_alternate_button_styles' => 'yes',
				],
			]
		);

		$this->add_control(
			'alternate_button_border_width',
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
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-button-primary-border-width-alternate: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'primary_button_type' => 'button',
					'has_alternate_button_styles' => 'yes',
					'has_alternate_button_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'alternate_button_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-button-primary-border-color-alternate: {{VALUE}}',
				],
				'condition' => [
					'primary_button_type' => 'button',
					'has_alternate_button_styles' => 'yes',
					'has_alternate_button_border' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'alternate_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-zigzag__item-wrapper:nth-child(even) .is-type-button.ehp-zigzag__button--primary',
				'condition' => [
					'primary_button_type' => 'button',
					'has_alternate_button_styles' => 'yes',
				],
			]
		);

		$this->add_control(
			'has_alternate_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_alternate_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag' => '--zigzag-icon-color-alternate: {{VALUE}}',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'condition' => [
					'has_alternate_icon_color' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_alternate_background',
			[
				'label' => esc_html__( 'Background', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'alternate_background',
				'types' => [ 'classic', 'gradient' ],
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

		$this->add_control(
			'has_alternate_padding',
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'alternate_padding_horizontal',
			[
				'label' => esc_html__( 'Horizontal Padding', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-zigzag__item-wrapper:nth-child(even)' => '--zigzag-box-padding-inline-start: {{SIZE}}{{UNIT}}; --zigzag-box-padding-inline-end: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'has_alternate_padding' => 'yes',
				],
			]
		);

		$this->add_control(
			'animation_alternate',
			[
				'label' => esc_html__( 'Motion Effects', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'zigzag_animation',
							'operator' => '===',
							'value' => 'fadeInLeft',
						],
						[
							'name' => 'zigzag_animation',
							'operator' => '===',
							'value' => 'fadeInRight',
						],
						[
							'name' => 'zigzag_animation',
							'operator' => '===',
							'value' => 'bounceInLeft',
						],
						[
							'name' => 'zigzag_animation',
							'operator' => '===',
							'value' => 'bounceInRight',
						],
						[
							'name' => 'zigzag_animation',
							'operator' => '===',
							'value' => 'slideInLeft',
						],
						[
							'name' => 'zigzag_animation',
							'operator' => '===',
							'value' => 'slideInRight',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'zigzag_animation_alternate',
			[
				'label' => esc_html__( 'Sequenced Entrance Animation', 'hello-plus' ),
				'type' => Control_Zig_Zag_Animation::CONTROL_TYPE,
				'frontend_available' => true,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'animation_alternate',
							'operator' => '===',
							'value' => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'zigzag_animation',
									'operator' => '===',
									'value' => 'fadeInLeft',
								],
								[
									'name' => 'zigzag_animation',
									'operator' => '===',
									'value' => 'fadeInRight',
								],
								[
									'name' => 'zigzag_animation',
									'operator' => '===',
									'value' => 'bounceInLeft',
								],
								[
									'name' => 'zigzag_animation',
									'operator' => '===',
									'value' => 'bounceInRight',
								],
								[
									'name' => 'zigzag_animation',
									'operator' => '===',
									'value' => 'slideInLeft',
								],
								[
									'name' => 'zigzag_animation',
									'operator' => '===',
									'value' => 'slideInRight',
								],
							],
						],
					],
				],
			]
		);

		$this->end_controls_section();
	}
}
