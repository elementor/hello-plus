<?php

namespace HelloPlus\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	Group_Control_Css_Filter,
	Group_Control_Box_Shadow,
	Group_Control_Image_Size,
	Widget_Base
};

use Elementor\Core\Kits\Documents\Tabs\{
	Global_Typography,
	Global_Colors,
};

use Elementor\Utils as Elementor_Utils;

use HelloPlus\Classes\Ehp_Shapes;

class Ehp_Image {
	private $context = [];
	private $defaults = [];
	private ?Widget_Base $widget = null;

	const EHP_PREFIX = 'ehp-';
	const CLASSNAME_IMAGE = 'ehp-image';

	public function set_context( array $context ) {
		$this->context = $context;
	}

	public function get_attachment_image_html_filter( $html ) {
		$settings = $this->widget->get_settings_for_display();
		$widget_name = $this->context['widget_name'];

		$image_classnames = [
			self::CLASSNAME_IMAGE . '__img',
			self::EHP_PREFIX . $widget_name . '__img',
		];

		if ( ! empty( $settings['show_image_border'] ) && 'yes' === $settings['show_image_border'] ) {
			$image_classnames[] = 'has-border';
		}

		$shapes = new Ehp_Shapes( $this->widget, [
			'container_prefix' => 'image',
			'widget_name' => $widget_name,
		] );
		$image_classnames = array_merge( $image_classnames, $shapes->get_shape_classnames() );

		$html = str_replace( '<img ', '<img class="' . esc_attr( implode( ' ', $image_classnames ) ) . '" ', $html );
		return $html;
	}

	public function render() {
		$settings = $this->widget->get_settings_for_display();
		$widget_name = $this->context['widget_name'];

		$image = $this->defaults['image'] ?? $settings['image'];

		$has_image = ! empty( $image['url'] );
		$image_wrapper_classnames = [
			self::CLASSNAME_IMAGE,
			self::EHP_PREFIX . $widget_name . '__image-container',
		];

		$this->widget->add_render_attribute( 'image', [
			'class' => $image_wrapper_classnames,
		] );

		$settings_control_value = $this->defaults['settings'] ?? $settings;
		$image_key = $this->defaults['image_key'] ?? 'image';

		if ( $has_image ) :
			?>
			<div <?php $this->widget->print_render_attribute_string( 'image' ); ?>>
				<?php
					add_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_attachment_image_html_filter' ], 10, 4 );
					Group_Control_Image_Size::print_attachment_image_html( $settings_control_value, $image_key );
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
				'dynamic' => [
					'active' => true,
				],
			]
		);
	}

	public function add_style_controls() {
		$widget_name = $this->context['widget_name'];
		$defaults = [
			'has_image_width_slider' => $this->defaults['has_image_width_slider'] ?? true,
			'has_image_width_dropdown' => $this->defaults['has_image_width_dropdown'] ?? false,
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

		if ( $defaults['has_image_width_slider'] ) {
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
		}

		if ( $defaults['has_image_width_dropdown'] ) {
			$this->widget->add_responsive_control(
				'image_width',
				[
					'label' => esc_html__( 'Width', 'hello-plus' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'50%' => '50%',
						'40%' => '40%',
						'30%' => '30%',
					],
					'default' => '50%',
					'selectors' => [
						'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-image-width: {{VALUE}};',
					],
					'condition' => [
						'image_stretch!' => 'yes',
					],
				]
			);
		}

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
				'selector' => '{{WRAPPER}} .ehp-' . $widget_name . '__image-container img',
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

		$shapes = new Ehp_Shapes( $this->widget, [
			'widget_name' => $widget_name,
			'container_prefix' => 'image',
		] );
		$shapes->add_style_controls();

		$this->widget->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-' . $widget_name . '__image-container img',
			]
		);
	}

	public function __construct( Widget_Base $widget, $context = [], $defaults = [] ) {
		$this->widget = $widget;
		$this->context = $context;
		$this->defaults = $defaults;
	}
}
