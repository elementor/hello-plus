<?php

namespace HelloPlus\Modules\TemplateParts\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Utils as Elementor_Utils;

use Elementor\{
	Controls_Manager,
	Group_Control_Image_Size,
	Group_Control_Box_Shadow,
	Group_Control_Css_Filter,
	Group_Control_Text_Shadow,
	Group_Control_Typography,
	Widget_Base,
};
use Elementor\Core\Kits\Documents\Tabs\{
	Global_Typography,
	Global_Colors,
};
use HelloPlus\Classes\{
	Ehp_Shapes,
	Widget_Utils,
};
use HelloPlus\Modules\TemplateParts\Classes\{
	Control_Media_Preview,
};

class Ehp_Shared_Template_Parts {
	private $context = [];
	private ?Widget_Base $widget = null;

	const EHP_PREFIX = 'ehp-';

	private $widget_name = '';
	private $layout_classname = '';

	public function set_context( array $context ) {
		$this->context = $context;
	}

	public function add_content_brand_controls() {
		$this->widget->add_control(
			'site_logo_brand_select',
			[
				'label' => esc_html__( 'Brand', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'logo' => esc_html__( 'Site Logo', 'hello-plus' ),
					'title' => esc_html__( 'Site Name', 'hello-plus' ),
				],
				'default' => 'logo',
				'tablet_default' => 'logo',
				'mobile_default' => 'logo',
			]
		);

		$this->widget->add_control(
			'site_logo_image',
			[
				'label' => esc_html__( 'Site Logo', 'hello-plus' ),
				'type' => Control_Media_Preview::CONTROL_TYPE,
				'src' => $this->get_site_logo_url(),
				'default' => [
					'url' => $this->get_site_logo_url(),
				],
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			],
			[
				'recursive' => true,
			]
		);

		$this->widget->add_control(
			'change_logo_cta',
			[
				'type' => Controls_Manager::BUTTON,
				'label_block' => true,
				'show_label' => false,
				'button_type' => 'default elementor-button-center',
				'text' => esc_html__( 'Change Site Logo', 'hello-plus' ),
				'event' => 'helloPlusLogo:change',
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			],
			[
				'position' => [
					'of' => 'image',
					'type' => 'control',
					'at' => 'after',
				],
			]
		);

		$this->widget->add_control(
			'site_logo_title_alert',
			[
				'type' => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'content' => esc_html__( 'Go to', 'hello-plus' ) . ' <a href="#" onclick="templatesModule.openSiteIdentity( event )" >' . esc_html__( 'Site Identity > Site Name', 'hello-plus' ) . '</a>' . esc_html__( ' to edit the Site Name', 'hello-plus' ),
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->widget->add_control(
			'site_logo_title_tag',
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
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);
	}

	public function add_style_brand_controls() {
		$this->widget->add_responsive_control(
			'style_logo_width',
			[
				'label' => __( 'Logo Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 68,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 68,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 68,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $this->widget_name => '--' . $this->widget_name . '-logo-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->widget->add_control(
			'style_title_heading',
			[
				'label' => esc_html__( 'Site Name', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->widget->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_title_typography',
				'selector' => '{{WRAPPER}} .ehp-' . $this->widget_name . '__site-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->widget->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .ehp-' . $this->widget_name . '__site-title',
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->widget->start_controls_tabs(
			'style_site_identity_tabs'
		);

		$this->widget->start_controls_tab(
			'style_site_identity_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->widget->add_control(
			'style_title_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $this->widget_name => '--' . $this->widget_name . '-site-title-color: {{VALUE}}',
				],
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->widget->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'logo_css_filter',
				'selector' => '{{WRAPPER}} .ehp-' . $this->widget_name . '__site-logo',
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->widget->end_controls_tab();

		$this->widget->start_controls_tab(
			'style_site_identity_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
			]
		);

		$this->widget->add_control(
			'style_title_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $this->widget_name => '--' . $this->widget_name . '-site-title-color-hover: {{VALUE}}',
				],
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->widget->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_hover_css_filters',
				'selector' => '{{WRAPPER}} .ehp-' . $this->widget_name . '__site-logo:hover',
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->widget->add_control(
			'style_logo_hover_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration (s)', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $this->widget_name . '__site-logo' => 'transition-duration: {{SIZE}}s',
				],
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->widget->add_control(
			'style_title_hover_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration (s)', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $this->widget_name . '__site-title' => 'transition-duration: {{SIZE}}s',
				],
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->widget->add_control(
			'style_logo_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->widget->end_controls_tab();

		$this->widget->end_controls_tabs();

		$this->widget->add_control(
			'show_logo_border',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->widget->add_control(
			'logo_border_width',
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
					'{{WRAPPER}} .ehp-' . $this->widget_name => '--' . $this->widget_name . '-logo-border-width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'show_logo_border',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'site_logo_brand_select',
							'operator' => '==',
							'value' => 'logo',
						],
					],
				],
			]
		);

		$this->widget->add_control(
			'logo_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $this->widget_name => '--' . $this->widget_name . '-logo-border-color: {{VALUE}}',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'show_logo_border',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'site_logo_brand_select',
							'operator' => '==',
							'value' => 'logo',
						],
					],
				],
			]
		);

		$shapes = new Ehp_Shapes( $this->widget, [
			'widget_name' => $this->widget_name,
			'container_prefix' => 'logo',
			'condition' => [
				'site_logo_brand_select' => 'logo',
			],
		] );
		$shapes->add_style_controls();

		$this->widget->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'logo_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-' . $this->widget_name . '__site-logo',
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);
	}

	public function get_link_url(): array {
		return [ 'url' => $this->get_site_url() ];
	}

	public function get_site_url(): string {
		return site_url();
	}

	public function get_attachment_image_html_filter( $html ) {
		$this->settings = $this->widget->get_settings_for_display();

		$logo_classnames = [
			$this->layout_classname . '__site-logo',
		];

		if ( ! empty( $this->settings['show_logo_border'] ) && 'yes' === $this->settings['show_logo_border'] ) {
			$logo_classnames[] = 'has-border';
		}

		$shapes = new Ehp_Shapes( $this->widget, [
			'container_prefix' => 'logo',
			'widget_name' => $this->widget_name,
		] );

		$logo_classnames = array_merge( $logo_classnames, $shapes->get_shape_classnames() );

		$html = str_replace( '<img ', '<img class="' . esc_attr( implode( ' ', $logo_classnames ) ) . '" ', $html );
		return $html;
	}

	public function render_site_link(): void {
		$this->settings = $this->widget->get_settings_for_display();

		$site_logo_brand_select = $this->settings['site_logo_brand_select'];
		$hover_animation = $this->settings['style_logo_hover_animation'] ?? '';
		$site_link_classnames = [ $this->layout_classname . '__site-link' ];

		if ( ! empty( $hover_animation ) ) {
			$site_link_classnames[] = 'elementor-animation-' . $hover_animation;
		}

		$this->widget->add_render_attribute( 'site-link', [
			'class' => $site_link_classnames,
		] );

		$site_link = $this->get_link_url();

		if ( $site_link ) {
			$this->widget->add_link_attributes( 'site-link', $site_link );
		}

		if ( $this->settings['site_logo_image'] ) {
			$this->settings['site_logo_image'] = $this->add_site_logo_if_present( $this->settings['site_logo_image'] );
		}

		$this->widget->add_render_attribute( 'site-link-container', 'class', $this->layout_classname . '__site-link-container' );

		$site_title_classname = $this->layout_classname . '__site-title';

		?>
		<div <?php $this->widget->print_render_attribute_string( 'site-link-container' ); ?>>
			<a <?php $this->widget->print_render_attribute_string( 'site-link' ); ?>>
				<?php if ( 'logo' === $site_logo_brand_select ) {
					add_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_attachment_image_html_filter' ], 10, 4 );
					Group_Control_Image_Size::print_attachment_image_html( $this->settings, 'site_logo_image' );
					remove_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_attachment_image_html_filter' ], 10 );
				} ?>
				<?php if ( 'title' === $site_logo_brand_select ) {
					Widget_Utils::maybe_render_text_html( $this->widget, 'header_site_title', $site_title_classname,  $this->get_site_title(), $this->settings['site_logo_title_tag'] ?? 'h2' );
				} ?>
			</a>
		</div>
		<?php
	}

	public function get_site_title(): string {
		return get_bloginfo( 'name' );
	}

	public function get_site_logo_url(): string {
		if ( ! has_custom_logo() ) {
			return Elementor_Utils::get_placeholder_image_src();
		}

		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		return $image[0] ?? Elementor_Utils::get_placeholder_image_src();
	}

	public function get_available_menus(): array {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	public function add_site_logo_if_present( array $site_logo_image ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );

		if ( $custom_logo_id ) {
			$site_logo_image['url'] = $this->get_site_logo_url();
			$site_logo_image['id'] = $custom_logo_id;
		}

		return $site_logo_image;
	}

	public function maybe_add_advanced_attributes() {
		$this->settings = $this->widget->get_settings_for_display();

		$advanced_css_id = $this->settings['advanced_custom_css_id'];
		$advanced_css_classes = $this->settings['advanced_custom_css_classes'];

		$wrapper_render_attributes = [];
		if ( ! empty( $advanced_css_classes ) ) {
			$wrapper_render_attributes['class'] = $advanced_css_classes;
		}

		if ( ! empty( $advanced_css_id ) ) {
			$wrapper_render_attributes['id'] = $advanced_css_id;
		}
		if ( empty( $wrapper_render_attributes ) ) {
			return;
		}
		$this->widget->add_render_attribute( '_wrapper', $wrapper_render_attributes );
	}

	public function __construct( Widget_Base $widget, $context = [], $defaults = [] ) {
		$this->widget = $widget;
		$this->context = $context;

		$this->widget_name = $this->context['widget_name'];
		$this->layout_classname = self::EHP_PREFIX . $this->widget_name;
	}
}