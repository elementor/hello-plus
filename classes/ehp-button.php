<?php

namespace HelloPlus\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	Group_Control_Background,
	Group_Control_Box_Shadow,
	Group_Control_Typography,
	Icons_Manager,
	Widget_Base
};
use Elementor\Core\Kits\Documents\Tabs\{
	Global_Colors,
	Global_Typography
};

class Ehp_Button {
	private $context = [];
	private $defaults = [];
	private ?Widget_Base $widget = null;

	const EHP_PREFIX = 'ehp-';
	const CLASSNAME_BUTTON = 'ehp-button';
	const CLASSNAME_BUTTON_TYPE_PREFIX = 'ehp-button--';

	public function set_context( array $context ) {
		$this->context = $context;
	}

	public function render() {
		$settings = $this->widget->get_settings_for_display();
		$type = $this->context['type'] ?? '';
		$widget_name = $this->context['widget_name'];

		$button_text = $this->defaults['button_text'] ?? $settings[ $type . '_cta_button_text' ] ?? '';
		$button_link = $this->defaults['button_link'] ?? $settings[ $type . '_cta_button_link' ] ?? [];
		$button_icon = $this->defaults['button_icon'] ?? $settings[ $type . '_cta_button_icon' ] ?? '';
		$button_hover_animation = $this->defaults['button_hover_animation'] ?? $settings[ $type . '_button_hover_animation' ] ?? '';
		$button_has_border = $this->defaults['show_button_border'] ?? $settings[ $type . '_show_button_border' ] ?? '';
		$button_corner_shape = $this->defaults['button_shape'] ?? $settings[ $type . '_button_shape' ] ?? '';
		$button_corner_shape_mobile = $this->defaults['button_shape_mobile'] ?? $settings[ $type . '_button_shape_mobile' ] ?? '';
		$button_corner_shape_tablet = $this->defaults['button_shape_tablet'] ?? $settings[ $type . '_button_shape_tablet' ] ?? '';
		$button_type = $this->defaults['button_type'] ?? $settings[ $type . '_button_type' ] ?? '';

		$button_classnames = [
			self::CLASSNAME_BUTTON,
			self::CLASSNAME_BUTTON_TYPE_PREFIX . $type,
			self::EHP_PREFIX . $widget_name . '__button',
			self::EHP_PREFIX . $widget_name . '__button--' . $type,
		];

		if ( ! empty( $button_type ) ) {
			$button_classnames[] = 'is-type-' . $button_type;
		}

		if ( $button_hover_animation ) {
			$button_classnames[] = 'elementor-animation-' . $button_hover_animation;
		}

		if ( 'yes' === $button_has_border ) {
			$button_classnames[] = 'has-border';
		}

		if ( ! empty( $button_corner_shape ) ) {
			$button_classnames[] = 'has-shape-' . $button_corner_shape;

			if ( ! empty( $button_corner_shape_mobile ) ) {
				$button_classnames[] = 'has-shape-sm-' . $button_corner_shape_mobile;
			}

			if ( ! empty( $button_corner_shape_tablet ) ) {
				$button_classnames[] = 'has-shape-md-' . $button_corner_shape_tablet;
			}
		}

		$this->widget->add_render_attribute( $type . '-button', [
			'class' => $button_classnames,
		] );

		if ( ! empty( $button_link['url'] ) ) {
			$this->widget->add_link_attributes( $type . '-button', $button_link, true );
		}

		?>
		<a <?php $this->widget->print_render_attribute_string( $type . '-button' ); ?>>
			<?php
			Icons_Manager::render_icon( $button_icon, [
				'aria-hidden' => 'true',
				'class' => 'ehp-button__icon',
				'ehp-' . $widget_name . '__button-icon',
			] );
			?>
			<?php echo esc_html( $button_text ); ?>
		</a>
		<?php
	}

	public function add_content_section() {
		$defaults = [
			'secondary_cta_show' => $this->defaults['secondary_cta_show'] ?? 'yes',
			'has_secondary_cta' => $this->defaults['has_secondary_cta'] ?? true,
			'primary_cta_button_text_placeholder' => $this->defaults['primary_cta_button_text_placeholder'] ?? esc_html__( 'Schedule Now', 'hello-plus' ),
		];

		$this->widget->start_controls_section(
			'content_cta',
			[
				'label' => esc_html__( 'CTA Button', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		if ( $defaults['has_secondary_cta'] ) {
			$this->widget->add_control(
				'primary_cta_heading',
				[
					'label' => esc_html__( 'Primary CTA', 'hello-plus' ),
					'type' => Controls_Manager::HEADING,
				]
			);
		}

		$this->widget->add_control(
			'primary_cta_button_text',
			[
				'label' => esc_html__( 'Text', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => $defaults['primary_cta_button_text_placeholder'],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->widget->add_control(
			'primary_cta_button_link',
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

		$this->widget->add_control(
			'primary_cta_button_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
			]
		);

		if ( $defaults['has_secondary_cta'] ) {
			$this->widget->add_control(
				'secondary_cta_show',
				[
					'label' => esc_html__( 'Secondary CTA', 'hello-plus' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'hello-plus' ),
					'label_off' => esc_html__( 'Hide', 'hello-plus' ),
					'return_value' => 'yes',
					'default' => $defaults['secondary_cta_show'],
					'separator' => 'before',
				]
			);

			$this->widget->add_control(
				'secondary_cta_button_text',
				[
					'label' => esc_html__( 'Text', 'hello-plus' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Contact Us', 'hello-plus' ),
					'dynamic' => [
						'active' => true,
					],
					'condition' => [
						'secondary_cta_show' => 'yes',
					],
				]
			);

			$this->widget->add_control(
				'secondary_cta_button_link',
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
					'condition' => [
						'secondary_cta_show' => 'yes',
					],
				]
			);

			$this->widget->add_control(
				'secondary_cta_button_icon',
				[
					'label' => esc_html__( 'Icon', 'hello-plus' ),
					'type' => Controls_Manager::ICONS,
					'label_block' => false,
					'skin' => 'inline',
					'condition' => [
						'secondary_cta_show' => 'yes',
					],
				]
			);
		}

		$this->widget->end_controls_section();
	}

	public function add_style_controls() {
		$widget_name = $this->context['widget_name'];
		$defaults = [
			'has_secondary_cta' => $this->defaults['has_secondary_cta'] ?? true,
		];

		$this->add_button_type_controls(
			[
				'type' => 'primary',
			]
		);

		if ( $defaults['has_secondary_cta'] ) {
			$this->add_button_type_controls(
				[
					'type' => 'secondary',
					'add_condition' => true,
				]
			);

			$this->widget->add_responsive_control(
				'cta_space_between',
				[
					'label' => esc_html__( 'Space Between', 'hello-plus' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
					'range' => [
						'px' => [
							'max' => 200,
						],
						'%' => [
							'max' => 100,
						],
					],
					'default' => [
						'size' => 16,
						'unit' => 'px',
					],
					'tablet_default' => [
						'size' => 16,
						'unit' => 'px',
					],
					'mobile_default' => [
						'size' => 16,
						'unit' => 'px',
					],
					'separator' => 'before',
					'selectors' => [
						'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-buttons-space-between: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'secondary_cta_show' => 'yes',
					],
				]
			);
		}
	}

	public function add_button_type_controls( array $options = [] ) {
		$defaults = [
			'has_secondary_cta' => $this->defaults['has_secondary_cta'] ?? true,
			'button_default_type' => $this->defaults['button_default_type'] ?? 'button',
		];
		$type = $options['type'];
		$add_condition = $options['add_condition'] ?? [];

		$widget_name = $this->context['widget_name'];

		$is_primary = 'primary' === $type;
		$label = $is_primary ? esc_html__( 'Primary CTA', 'hello-plus' ) : esc_html__( 'Secondary CTA', 'hello-plus' );
		$show_button_border_default = $is_primary ? 'no' : 'yes';
		$background_color_default = $is_primary ? Global_Colors::COLOR_ACCENT : '';

		$add_type_condition = $add_condition ? [
			$type . '_cta_show' => 'yes',
		] : [];

		if ( isset( $options[ 'ignore_icon_value_condition' ] ) && $options[ 'ignore_icon_value_condition' ] === true ) {
			$icon_condition = $add_type_condition;
		} else {
			$icon_condition = array_merge( [
				$type . '_cta_button_icon[value]!' => '',
			], $add_type_condition);
		}

		if ( $defaults['has_secondary_cta'] ) {
			$this->widget->add_control(
				$type . '_button_label',
				[
					'label' => $label,
					'type' => Controls_Manager::HEADING,
					'condition' => $add_type_condition,
					'separator' => 'primary' === $type ? 'before' : '',
				]
			);
		}

		$this->widget->add_control(
			$type . '_button_type',
			[
				'label' => esc_html__( 'Type', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => $defaults['button_default_type'],
				'options' => [
					'button' => esc_html__( 'Button', 'hello-plus' ),
					'link' => esc_html__( 'Link', 'hello-plus' ),
				],
				'condition' => $add_type_condition,
			]
		);

		$this->widget->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => $type . '_button_typography',
				'selector' => '{{WRAPPER}} .ehp-' . $widget_name . '__button--' . $type,
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'condition' => $add_type_condition,
			]
		);

		$this->widget->add_responsive_control(
			$type . '_button_icon_position',
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
					'{{WRAPPER}} .ehp-' . $widget_name . '__button--' . $type => 'flex-direction: {{VALUE}};',
				],
				'condition' => $icon_condition,
			]
		);

		$this->widget->add_control(
			$type . '_button_icon_spacing',
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
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-button-' . $type . '-icon-spacing: {{SIZE}}{{UNIT}};',
				],
				'condition' => $icon_condition,
			]
		);

		$this->widget->start_controls_tabs(
			$type . '_button_style'
		);

		$this->widget->start_controls_tab(
			$type . '_button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
				'condition' => $add_type_condition,
			],
		);

		$this->widget->add_control(
			$type . '_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-button-' . $type . '-text-color: {{VALUE}}',
				],
				'condition' => $add_type_condition,
			]
		);

		$this->widget->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => $type . '_button_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .is-type-button.ehp-' . $widget_name . '__button--' . $type,
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => $background_color_default,
						],
					],
				],
				'condition' => array_merge([
					$type . '_button_type' => 'button',
				], $add_type_condition),
			]
		);

		$this->widget->end_controls_tab();

		$this->widget->start_controls_tab(
			$type . '_button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
				'condition' => $add_type_condition,
			],
		);

		$this->widget->add_control(
			$type . '_hover_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-button-' . $type . '-text-color-hover: {{VALUE}}',
				],
				'condition' => $add_type_condition,
			]
		);

		$this->widget->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => $type . '_button_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .is-type-button.ehp-' . $widget_name . '__button--' . $type . ':hover, {{WRAPPER}} .is-type-button.ehp-' . $widget_name . '__button--' . $type . ':focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => $background_color_default,
						],
					],
				],
				'condition' => array_merge([
					$type . '_button_type' => 'button',
				], $add_type_condition),
			]
		);

		$this->widget->add_control(
			$type . '_button_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'condition' => $add_type_condition,
			]
		);

		$this->widget->end_controls_tab();

		$this->widget->end_controls_tabs();

		$this->widget->add_control(
			$type . '_show_button_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => $show_button_border_default,
				'separator' => 'before',
				'condition' => array_merge([
					$type . '_button_type' => 'button',
				], $add_type_condition),
			]
		);

		$this->widget->add_control(
			$type . '_button_border_width',
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
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-button-' . $type . '-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => array_merge([
					$type . '_show_button_border' => 'yes',
				], $add_type_condition),
			]
		);

		$this->widget->add_control(
			$type . '_button_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-button-' . $type . '-border-color: {{VALUE}}',
				],
				'condition' => array_merge([
					$type . '_show_button_border' => 'yes',
				], $add_type_condition),
			]
		);

		$this->widget->add_control(
			$type . '_button_shape',
			[
				'label' => esc_html__( 'Shape', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
					'sharp' => esc_html__( 'Sharp', 'hello-plus' ),
					'rounded' => esc_html__( 'Rounded', 'hello-plus' ),
					'round' => esc_html__( 'Round', 'hello-plus' ),
					'oval' => esc_html__( 'Oval', 'hello-plus' ),
					'custom' => esc_html__( 'Custom', 'hello-plus' ),
				],
				'condition' => array_merge([
					$type . '_button_type' => 'button',
				], $add_type_condition),
			]
		);

		$this->widget->add_responsive_control(
			$type . '_button_shape_custom',
			[
				'label' => esc_html__( 'Border Radius', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-button-' . $type . '-border-radius-block-end: {{BOTTOM}}{{UNIT}}; --' . $widget_name . '-button-' . $type . '-border-radius-block-start: {{TOP}}{{UNIT}}; --' . $widget_name . '-button-' . $type . '-border-radius-inline-end: {{RIGHT}}{{UNIT}}; --' . $widget_name . '-button-' . $type . '-border-radius-inline-start: {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => array_merge([
					$type . '_button_shape' => 'custom',
				], $add_type_condition),
			]
		);

		$this->widget->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => $type . '_button_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-' . $widget_name . '__button--' . $type,
				'condition' => array_merge([
					$type . '_button_type' => 'button',
				], $add_type_condition),
			]
		);

		$this->widget->add_responsive_control(
			$type . '_button_padding',
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-button-' . $type . '-padding-block-end: {{BOTTOM}}{{UNIT}}; --' . $widget_name . '-button-' . $type . '-padding-block-start: {{TOP}}{{UNIT}}; --' . $widget_name . '-button-' . $type . '-padding-inline-end: {{RIGHT}}{{UNIT}}; --' . $widget_name . '-button-' . $type . '-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => array_merge([
					$type . '_button_type' => 'button',
				], $add_type_condition),
			]
		);
	}

	public function __construct( Widget_Base $widget, $context = [], $defaults = [] ) {
		$this->widget = $widget;
		$this->context = $context;
		$this->defaults = $defaults;
	}
}
