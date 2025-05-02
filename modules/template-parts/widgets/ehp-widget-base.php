<?php

namespace HelloPlus\Modules\TemplateParts\Widgets;

use HelloPlus\Includes\Utils as Theme_Utils;

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

abstract class Ehp_Widget_Base extends Widget_Base {
	const LAYOUT_PREFIX = 'ehp-';

	public function get_stack( $with_common_controls = true ): array {
		return parent::get_stack( false );
	}

	public function show_in_panel(): bool {
		return apply_filters( 'hello-plus/template-parts/widgets/panel/show', false );
	}

	public function hide_on_search(): bool {
		return ! $this->show_in_panel();
	}

	protected function add_advanced_tab(): void {
		$advanced_tab_id = Controls_Manager::TAB_ADVANCED;

		Controls_Manager::add_tab(
			$advanced_tab_id,
			esc_html__( 'Advanced', 'hello-plus' )
		);

		$this->add_custom_advanced_sections();
		$this->add_basic_css_controls_section();

		$elementor_plugin = Theme_Utils::elementor();
		$elementor_plugin->controls_manager->add_custom_css_controls( $this, $advanced_tab_id );
		$elementor_plugin->controls_manager->add_custom_attributes_controls( $this, $advanced_tab_id );
	}

	abstract public function add_custom_advanced_sections(): void;

	protected function add_basic_css_controls_section(): void {
		$this->start_controls_section(
			'advanced_responsive_section',
			[
				'label' => esc_html__( 'Responsive', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'responsive_description',
			[
				'raw' => __( 'Responsive visibility will take effect only on preview mode or live page, and not while editing in Elementor.', 'hello-plus' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		$this->add_hidden_device_controls();

		$this->end_controls_section();

		$this->start_controls_section(
			'advanced_custom_controls_section',
			[
				'label' => esc_html__( 'CSS', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'advanced_custom_css_id',
			[
				'label' => esc_html__( 'CSS ID', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'ai' => [
					'active' => false,
				],
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'hello-plus' ),
				'style_transfer' => false,
			]
		);

		$this->add_control(
			'advanced_custom_css_classes',
			[
				'label' => esc_html__( 'CSS Classes', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'ai' => [
					'active' => false,
				],
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'hello-plus' ),
			]
		);

		$this->end_controls_section();
	}

	public function add_content_brand_controls() {
		$this->add_control(
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

		$this->add_control(
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

		$this->add_control(
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

		$this->add_control(
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

		$this->add_control(
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

	public function add_style_brand_controls( $widget_name ) {
		$this->add_control(
			'style_logo_heading',
			[
				'label' => esc_html__( 'Logo', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->add_control(
			'style_title_heading',
			[
				'label' => esc_html__( 'Site Name', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-logo-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_title_typography',
				'selector' => '{{WRAPPER}} .ehp-' . $widget_name . '__site-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .ehp-' . $widget_name . '__site-title',
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->start_controls_tabs(
			'style_site_identity_tabs'
		);

		$this->start_controls_tab(
			'style_site_identity_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->add_control(
			'style_title_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-site-title-color: {{VALUE}}',
				],
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'logo_css_filter',
				'selector' => '{{WRAPPER}} .ehp-' . $widget_name . '__site-logo',
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_site_identity_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
			]
		);

		$this->add_control(
			'style_title_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-site-title-color-hover: {{VALUE}}',
				],
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_hover_css_filters',
				'selector' => '{{WRAPPER}} .ehp-' . $widget_name . '__site-logo:hover',
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->add_control(
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
					'{{WRAPPER}} .ehp-' . $widget_name . '__site-logo' => 'transition-duration: {{SIZE}}s',
				],
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->add_control(
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
					'{{WRAPPER}} .ehp-' . $widget_name . '__site-title' => 'transition-duration: {{SIZE}}s',
				],
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->add_control(
			'style_logo_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
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

		$this->add_control(
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
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-logo-border-width: {{SIZE}}{{UNIT}};',
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

		$this->add_control(
			'logo_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-' . $widget_name => '--' . $widget_name . '-logo-border-color: {{VALUE}}',
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

		$shapes = new Ehp_Shapes( $this, [
			'widget_name' => $widget_name,
			'container_prefix' => 'logo',
			'condition' => [
				'site_logo_brand_select' => 'logo',
			],
		] );
		$shapes->add_style_controls();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'logo_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-' . $widget_name . '__site-logo',
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

	public function get_header_attachment_image_html_filter( $html ) {
		return $this->get_attachment_image_html_filter( $html, 'header' );
	}

	public function get_footer_attachment_image_html_filter( $html ) {
		return $this->get_attachment_image_html_filter( $html, 'footer' );
	}

	public function get_flex_footer_attachment_image_html_filter( $html ) {
		return $this->get_attachment_image_html_filter( $html, 'flex-footer' );
	}

	public function get_attachment_image_html_filter( $html, $widget_name ) {
		$layout_classname = self::LAYOUT_PREFIX . $widget_name;
		$settings = $this->get_settings_for_display();

		$logo_classnames = [
			$layout_classname . '__site-logo',
		];

		if ( ! empty( $settings['show_logo_border'] ) && 'yes' === $settings['show_logo_border'] ) {
			$logo_classnames[] = 'has-border';
		}

		$shapes = new Ehp_Shapes( $this, [
			'container_prefix' => 'logo',
			'widget_name' => $widget_name,
		] );

		$logo_classnames = array_merge( $logo_classnames, $shapes->get_shape_classnames() );

		$html = str_replace( '<img ', '<img class="' . esc_attr( implode( ' ', $logo_classnames ) ) . '" ', $html );
		return $html;
	}

	public function render_site_link( $widget_name ): void {
		$layout_classname = self::LAYOUT_PREFIX . $widget_name;
		$settings = $this->get_settings_for_display();

		$site_logo_brand_select = $settings['site_logo_brand_select'];
		$hover_animation = $settings['style_logo_hover_animation'] ?? '';
		$site_link_classnames = [ $layout_classname . '__site-link' ];

		if ( ! empty( $hover_animation ) ) {
			$site_link_classnames[] = 'elementor-animation-' . $hover_animation;
		}

		$this->add_render_attribute( 'site-link', [
			'class' => $site_link_classnames,
		] );

		$site_link = $this->get_link_url();

		if ( $site_link ) {
			$this->add_link_attributes( 'site-link', $site_link );
		}

		if ( ! empty( $settings['site_logo_image'] ) ) {
			$settings['site_logo_image'] = $this->add_site_logo_if_present( $settings['site_logo_image'] );
		}

		$this->add_render_attribute( 'site-link-container', 'class', $layout_classname . '__site-link-container' );

		$site_title_classname = $layout_classname . '__site-title';

		?>
		<div <?php $this->print_render_attribute_string( 'site-link-container' ); ?>>
			<a <?php $this->print_render_attribute_string( 'site-link' ); ?>>
				<?php if ( 'logo' === $site_logo_brand_select ) {

					if ( 'header' === $widget_name ) {
						add_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_header_attachment_image_html_filter' ], 10, 4 );
						Group_Control_Image_Size::print_attachment_image_html( $settings, 'site_logo_image' );
						remove_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_header_attachment_image_html_filter' ], 10 );
					} elseif ( 'footer' === $widget_name ) {
						add_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_footer_attachment_image_html_filter' ], 10, 4 );
						Group_Control_Image_Size::print_attachment_image_html( $settings, 'site_logo_image' );
						remove_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_footer_attachment_image_html_filter' ], 10 );
					} elseif ( 'flex-footer' === $widget_name ) {
						add_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_flex_footer_attachment_image_html_filter' ], 10, 4 );
						Group_Control_Image_Size::print_attachment_image_html( $settings, 'site_logo_image' );
						remove_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_flex_footer_attachment_image_html_filter' ], 10 );
					}
				} ?>
				<?php if ( 'title' === $site_logo_brand_select ) {
					Widget_Utils::maybe_render_text_html( $this, 'header_site_title', $site_title_classname, $this->get_site_title(), $settings['site_logo_title_tag'] ?? 'h2' );
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
		$settings = $this->get_settings_for_display();

		$advanced_css_id = $settings['advanced_custom_css_id'];
		$advanced_css_classes = $settings['advanced_custom_css_classes'];

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
		$this->add_render_attribute( '_wrapper', $wrapper_render_attributes );
	}
}
