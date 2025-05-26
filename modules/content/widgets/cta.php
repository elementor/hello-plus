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

use HelloPlus\Modules\Theme\Module as Theme_Module;
use HelloPlus\Modules\Content\Classes\{
	Choose_Img_Control,
	Render\Widget_CTA_Render,
};
use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Column_Structure,
	Ehp_Full_Height,
	Ehp_Image,
	Ehp_Padding,
	Ehp_Shapes,
};
use HelloPlus\Includes\Utils;

class CTA extends Widget_Base {

	public function get_name(): string {
		return 'cta';
	}

	public function get_title(): string {
		return esc_html__( 'CTA', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLOPLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'cta' ];
	}

	public function get_icon(): string {
		return 'eicon-ehp-cta';
	}

	public function get_style_depends(): array {
		return array_merge( [ 'helloplus-cta' ], Utils::get_widgets_depends() );
	}

	public function get_custom_help_url(): string {
		return 'https://go.elementor.com/cta-widget-help';
	}

	protected function render(): void {
		$render_strategy = new Widget_CTA_Render( $this );

		$this->add_inline_editing_attributes( 'heading_text', 'none' );
		$this->add_inline_editing_attributes( 'description_text', 'none' );
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
		$this->add_content_image_section();
		$this->add_content_text_section();
		$this->add_content_cta_section();
	}

	protected function add_style_section() {
		$this->add_style_section_layout();
		$this->add_style_section_image();
		$this->add_style_section_text();
		$this->add_style_section_cta();
		$this->add_style_box_section();
	}

	protected function add_content_layout_section() {
		$this->start_controls_section(
			'layout',
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
				'default' => 'focus',
				'label_block' => true,
				'toggle' => false,
				'columns' => 2,
				'options' => [
					'focus' => [
						'title' => wp_kses_post( "Focus:\nHighlight a single, full-width\nCTA to maximize impact." ),
						'image' => HELLOPLUS_IMAGES_URL . 'cta-focus.svg',
						'hover_image' => true,
					],
					'streamline' => [
						'title' => wp_kses_post( "Streamline:\nPair alongside other CTAs\nand elements for a seamless flow." ),
						'image' => HELLOPLUS_IMAGES_URL . 'cta-streamline.svg',
						'hover_image' => true,
					],
					'showcase' => [
						'title' => wp_kses_post( "Showcase:\nHighlight key concepts\nwith a balanced layout." ),
						'image' => HELLOPLUS_IMAGES_URL . 'cta-showcase.svg',
						'hover_image' => true,
					],
					'storytelling' => [
						'title' => wp_kses_post( "Storytelling:\nFocus on a narrative\nwith supporting visuals." ),
						'image' => HELLOPLUS_IMAGES_URL . 'cta-storytelling.svg',
						'hover_image' => true,
					],
				],
				'frontend_available' => true,
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
				'condition' => [
					'layout_preset' => [ 'showcase', 'storytelling' ],
				],
			]
		);

		$image = new Ehp_Image( $this, [ 'widget_name' => $this->get_name() ] );
		$image->add_content_section();

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
				'default' => htmlspecialchars_decode( __( 'Schedule a free consultation with our team and let\'s make things happen!', 'hello-plus' ) ),
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

	protected function add_style_section_layout() {
		$this->start_controls_section(
			'style_layout',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_alignment',
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
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .ehp-cta' => '--cta-content-alignment: {{VALUE}};',
				],
				'condition' => [
					'layout_preset' => [
						'streamline',
						'storytelling',
					],
				],
			]
		);

		$this->add_control(
			'cta_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-start-v',
					],
					'end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-align-end-v',
					],
				],
				'default' => 'start',
				'tablet_default' => 'start',
				'mobile_default' => 'start',
				'selectors' => [
					'{{WRAPPER}} .ehp-cta' => '--cta-buttons-vertical-position: {{VALUE}};',
				],
				'frontend_available' => true,
				'condition' => [
					'layout_preset' => [
						'focus',
					],
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
						'max' => 1200,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-cta' => '--cta-content-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_preset' => [
						'storytelling',
						'focus',
						'streamline',
					],
				],
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
			'image_horizontal_position',
			[
				'label' => esc_html__( 'Image Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'toggle' => false,
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
				'frontend_available' => true,
				'default' => 'start',
				'tablet_default' => 'start',
				'mobile_default' => 'start',
				'separator' => 'before',
				'condition' => [
					'layout_preset' => 'showcase',
				],
			]
		);

		$this->add_responsive_control(
			'content_position_vertical',
			[
				'label' => esc_html__( 'Content Position', 'hello-plus' ),
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
				'default' => 'start',
				'tablet_default' => 'start',
				'mobile_default' => 'start',
				'selectors' => [
					'{{WRAPPER}} .ehp-cta' => '--cta-content-position-vertical: {{VALUE}};',
				],
				'frontend_available' => true,
				'condition' => [
					'layout_preset' => 'showcase',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_section_image() {
		$this->start_controls_section(
			'style_image',
			[
				'label' => esc_html__( 'Image', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_preset' => [ 'showcase', 'storytelling' ],
				],
			]
		);

		$image = new Ehp_Image( $this, [ 'widget_name' => $this->get_name() ] );
		$image->add_style_controls();

		$this->end_controls_section();
	}

	protected function add_content_cta_section() {
		$button = new Ehp_Button( $this, [ 'widget_name' => $this->get_name() ] );
		$button->add_content_section();
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
					'{{WRAPPER}} .ehp-cta' => '--cta-heading-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .ehp-cta__heading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'description_label',
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
					'{{WRAPPER}} .ehp-cta' => '--cta-description-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .ehp-cta__description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
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

		$button = new Ehp_Button( $this, [ 'widget_name' => $this->get_name() ] );
		$button->add_style_controls();

		$this->add_responsive_control(
			'cta_width',
			[
				'label' => esc_html__( 'Width', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
					'stretch' => esc_html__( 'Stretch', 'hello-plus' ),
				],
				'default' => 'default',
				'default_tablet' => 'default',
				'default_mobile' => 'default',
				'frontend_available' => true,
				'condition' => [
					'layout_preset' => [
						'streamline',
						'storytelling',
					],
				],
			]
		);

		$this->add_responsive_control(
			'cta_position',
			[
				'label' => esc_html__( 'Position', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
					'end' => esc_html__( 'End', 'hello-plus' ),
				],
				'default' => 'default',
				'default_tablet' => 'default',
				'default_mobile' => 'default',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .ehp-cta' => '--cta-text-container-flex-grow: var(--cta-text-container-flex-grow-{{VALUE}});',
				],
				'condition' => [
					'layout_preset' => [
						'streamline',
						'storytelling',
					],
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
				'selector' => '{{WRAPPER}} .ehp-cta',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#F6F7F8',
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
				'selector' => '{{WRAPPER}} .ehp-cta__overlay',
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
					'{{WRAPPER}} .ehp-cta' => '--cta-overlay-opacity: {{SIZE}};',
				],
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
						'max' => 150,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 40,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-cta' => '--cta-elements-spacing: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$padding = new Ehp_Padding( $this, [
			'widget_name' => $this->get_name(),
			'container_prefix' => 'box',
		] );
		$padding->add_style_controls();

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
					'{{WRAPPER}} .ehp-cta' => '--cta-box-border-width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .ehp-cta' => '--cta-box-border-color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .ehp-cta',
			]
		);

		$ehp_full_height = new Ehp_Full_Height( $this );
		$ehp_full_height->add_style_controls();

		$this->end_controls_section();
	}
}
