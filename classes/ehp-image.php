<?php

namespace HelloPlus\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	// Group_Control_Background,
	// Group_Control_Box_Shadow,
	// Group_Control_Typography,
	// Icons_Manager,
	Widget_Base
};
use Elementor\Core\Kits\Documents\Tabs\{
	// Global_Colors,
	// Global_Typography
};

use Elementor\Utils as Elementor_Utils;

class Ehp_Image {
	private $context = [];
	private $defaults = [];
	private ?Widget_Base $widget = null;

	const EHP_PREFIX = 'ehp-';
	const CLASSNAME_IMAGE = 'ehp-image';

	public function set_context( array $context ) {
		$this->context = $context;
	}

	public function render() {
		$settings = $this->widget->get_settings_for_display();
		$widget_name = $this->context['widget_name'];

		$image = $settings['image'];
		$has_image = ! empty( $image['url'] );
		$image_wrapper_classnames = [
			self::CLASSNAME_IMAGE,
			self::EHP_PREFIX . $widget_name . '__image',
		];

		$this->widget->add_render_attribute( 'image', [
			'class' => $image_wrapper_classnames,
		] );

		if ( $has_image ) :
			?>
			<div <?php $this->widget->print_render_attribute_string( 'image' ); ?>>
				<?php
					add_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_attachment_image_html_filter' ], 10, 4 );
					Group_Control_Image_Size::print_attachment_image_html( $settings, 'image' );
					remove_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_attachment_image_html_filter' ], 10 );
				?>
			</div>
			<?php
		endif; //has_image
	}

	public function add_content_section() {
		$this->widget->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'hello-plus' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Elementor_Utils::get_placeholder_image_src(),
				],
			]
		);
	}

	public function add_style_section() {
		$widget_name = $this->context['widget_name'];
		$defaults = [
			'has_min_height' => $this->defaults['has_min_height'] || false,
		];

		$this->widget->add_control(
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

		$this->widget->add_responsive_control(
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
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-image-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_stretch!' => 'yes',
				],
			]
		);

		$this->widget->add_responsive_control(
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
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-image-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_stretch!' => 'yes',
				],
			]
		);

		if ( $defaults['has_min_height'] ) {
			$this->widget->add_responsive_control(
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
						'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-image-min-height: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'image_stretch' => 'yes',
					],
				]
			);
		}

		$this->widget->add_responsive_control(
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
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-image-position: {{VALUE}}',
				],
			]
		);

		$this->widget->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .ehp-' . $widget_name . '__image img',
			]
		);

		$this->widget->add_control(
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

		$this->widget->add_control(
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
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-image-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_image_border' => 'yes',
				],
			]
		);

		$this->widget->add_control(
			'image_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-image-border-color: {{VALUE}}',
				],
				'condition' => [
					'show_image_border' => 'yes',
				],
			]
		);

		$this->widget->add_responsive_control(
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

		$this->widget->add_responsive_control(
			'image_shape_custom',
			[
				'label' => esc_html__( 'Border Radius', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-image-border-radius-custom-block-end: {{BOTTOM}}{{UNIT}}; --' . $widget_name . '-image-border-radius-custom-block-start: {{TOP}}{{UNIT}}; --' . $widget_name . '-image-border-radius-custom-inline-end: {{RIGHT}}{{UNIT}}; --' . $widget_name . '-image-border-radius-custom-inline-start: {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'image_shape' => 'custom',
				],
			]
		);

		$this->widget->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-' . $widget_name . '__image img',
			]
		);
	}
}
