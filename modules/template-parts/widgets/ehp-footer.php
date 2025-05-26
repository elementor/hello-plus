<?php

namespace HelloPlus\Modules\TemplateParts\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	Group_Control_Background,
	Group_Control_Box_Shadow,
	Group_Control_Typography,
	Repeater,
};
use Elementor\Core\Kits\Documents\Tabs\{
	Global_Colors,
	Global_Typography,
};

use HelloPlus\Includes\Utils as General_Utils;

use HelloPlus\Modules\TemplateParts\Classes\Render\Widget_Footer_Render;

use HelloPlus\Classes\Ehp_Padding;

use HelloPlus\Modules\Theme\Module as Theme_Module;

class Ehp_Footer extends Ehp_Widget_Base {

	public function get_name(): string {
		return 'ehp-footer';
	}

	public function get_title(): string {
		return esc_html__( 'Hello+ Footer', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLOPLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'footer' ];
	}

	public function get_icon(): string {
		return 'eicon-single-page';
	}

	public function get_style_depends(): array {
		$style_depends = General_Utils::elementor()->experiments->is_feature_active( 'e_font_icon_svg' )
			? parent::get_style_depends()
			: [ 'elementor-icons-fa-solid', 'elementor-icons-fa-brands', 'elementor-icons-fa-regular' ];

		$style_depends[] = 'helloplus-footer';
		$style_depends[] = 'e-apple-webkit';

		return $style_depends;
	}

	public function get_custom_help_url(): string {
		return 'https://go.elementor.com/biz-footer-help';
	}

	protected function render(): void {
		$render_strategy = new Widget_Footer_Render( $this );

		$this->add_inline_editing_attributes( 'footer_description', 'none' );
		$this->add_inline_editing_attributes( 'footer_contact_heading', 'none' );
		$this->add_inline_editing_attributes( 'footer_contact_information', 'none' );
		$this->add_inline_editing_attributes( 'footer_menu_heading', 'none' );
		$this->add_inline_editing_attributes( 'footer_copyright', 'none' );

		$render_strategy->render();
	}

	protected function register_controls(): void {
		$this->add_content_section();
		$this->add_style_section();
		$this->add_advanced_tab();
	}

	public function add_content_section(): void {
		$this->add_content_brand_section();
		$this->add_content_navigation_section();
		$this->add_content_contact_section();
	}

	public function add_style_section(): void {
		$this->add_style_brand_section();
		$this->add_style_navigation_section();
		$this->add_style_contact_section();
		$this->add_style_box_section();
	}

	public function add_custom_advanced_sections(): void {}

	protected function get_upsale_data(): array {
		return [
			'condition' => ! General_Utils::has_pro(),
			'image' => esc_url( HELLOPLUS_IMAGES_URL . 'go-pro.svg' ),
			'image_alt' => esc_attr__( 'Upgrade Now', 'hello-plus' ),
			'title' => esc_html__( 'Create custom footers', 'hello-plus' ),
			'description' => esc_html__( 'Adjust your footer to include contact forms, sitemaps, and more with Elementor Pro.', 'hello-plus' ),
			'upgrade_url' => esc_url( 'https://go.elementor.com/helloplus-footer-pro' ),
			'upgrade_text' => esc_html__( 'Upgrade Now', 'hello-plus' ),
		];
	}

	public function add_content_brand_section(): void {
		$this->start_controls_section(
			'site_logo_label',
			[
				'label' => esc_html__( 'Brand', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_content_brand_controls();

		$this->add_control(
			'footer_description',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( 'Leveraging data-driven strategies, innovative technologies, and creative content to drive conversions and maximize ROI for our clients.', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'footer_description_tag',
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
					'p' => 'p',
				],
				'default' => 'p',
			]
		);

		$this->add_control(
			'footer_icons_heading',
			[
				'label' => esc_html__( 'Social Icons', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'footer_icon_text',
			[
				'label' => esc_html__( 'Accessible Name', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'default' => esc_html__( 'Instagram', 'hello-plus' ),
				'dynamic' => [
					'active' => false,
				],
			]
		);

		$repeater->add_control(
			'footer_selected_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'footer_icon_link',
			[
				'label' => esc_html__( 'Link', 'hello-plus' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'footer_icons',
			[
				'label' => null,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'footer_icon_text' => esc_html__( 'Instagram', 'hello-plus' ),
						'footer_selected_icon' => [
							'value' => 'fab fa-instagram',
							'library' => 'fa-brands',
						],
					],
					[
						'footer_icon_text' => esc_html__( 'Tiktok', 'hello-plus' ),
						'footer_selected_icon' => [
							'value' => 'fab fa-tiktok',
							'library' => 'fa-brands',
						],
					],
					[
						'footer_icon_text' => esc_html__( 'X (Twitter)', 'hello-plus' ),
						'footer_selected_icon' => [
							'value' => 'fab fa-x-twitter',
							'library' => 'fa-brands',
						],
					],
				],
				'title_field' => '{{{ elementor.helpers.renderIcon( this, footer_selected_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ footer_icon_text }}}',
			]
		);

		$this->add_control(
			'footer_copyright',
			[
				'label' => esc_html__( 'Copyright', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( '©2024 All rights reserved', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'footer_copyright_tag',
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
					'p' => 'p',
				],
				'default' => 'p',
			]
		);

		$this->end_controls_section();
	}

	public function add_content_navigation_section(): void {
		$this->start_controls_section(
			'section_navigation',
			[
				'label' => esc_html__( 'Navigation', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'footer_menu_heading',
			[
				'label' => esc_html__( 'Heading', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( 'Quick Links', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'footer_menu_heading_tag',
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
					'p' => 'p',
				],
				'default' => 'h6',
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'navigation_menu',
				[
					'label' => esc_html__( 'Menu', 'hello-plus' ),
					'type' => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'save_default' => true,
					'separator' => 'before',
					'description' => sprintf(
						/* translators: 1: Link opening tag, 2: Link closing tag. */
						esc_html__( 'Go to the %1$sMenus screen%2$s to manage your menus.', 'hello-plus' ),
						sprintf( '<a href="%s" target="_blank">', self_admin_url( 'nav-menus.php' ) ),
						'</a>'
					),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type' => Controls_Manager::ALERT,
					'alert_type' => 'info',
					'heading' => esc_html__( 'There are no menus in your site.', 'hello-plus' ),
					'content' => sprintf(
						/* translators: 1: Link opening tag, 2: Link closing tag. */
						esc_html__( 'Add and manage menus from %1$sMy menus%2$s ', 'hello-plus' ),
						sprintf( '<a href="%s" target="_blank">', self_admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
						'</a>'
					),
					'separator' => 'before',
				]
			);
		}

		$this->end_controls_section();
	}

	public function add_content_contact_section(): void {
		$this->start_controls_section(
			'contact_section',
			[
				'label' => esc_html__( 'Contact', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'footer_contact_heading',
			[
				'label' => esc_html__( 'Heading', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( 'Get in touch', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'footer_contact_heading_tag',
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
					'p' => 'p',
				],
				'default' => 'h6',
			]
		);

		$this->add_control(
			'footer_contact_information',
			[
				'label' => esc_html__( 'Contact Information', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => wp_kses_post( "contact@company.com\n1.212.555.7979\n360 W 34th St\nNew York, NY 10001\nOpen M-F, 9am-5pm" ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'footer_contact_information_tag',
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
					'p' => 'p',
				],
				'default' => 'p',
			]
		);

		$this->end_controls_section();
	}

	public function add_style_brand_section(): void {
		$this->start_controls_section(
			'style_brand_section',
			[
				'label' => esc_html__( 'Brand', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_style_brand_controls( 'footer' );

		$this->add_control(
			'footer_description_heading',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'footer_description_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-description-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'footer_description_typography',
				'label' => esc_html__( 'Typography', 'hello-plus' ),
				'selector' => '{{WRAPPER}} .ehp-footer__description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'social_icons_heading',
			[
				'label' => esc_html__( 'Social Icons', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'social_icons_size',
			[
				'label' => esc_html__( 'Size', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
					'small' => esc_html__( 'Small', 'hello-plus' ),
					'large' => esc_html__( 'Large', 'hello-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-icon-size: var(--footer-icon-size-{{VALUE}}); --footer-icon-spacing: var(--footer-icon-spacing-{{VALUE}});',
				],
			]
		);

		$this->start_controls_tabs(
			'social_icons_tabs'
		);

		$this->start_controls_tab(
			'social_icons_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->add_control(
			'social_icons_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-icon-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'social_icons_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
			]
		);

		$this->add_control(
			'social_icons_hover_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-icon-color-hover: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_icons_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'footer_copyright_heading',
			[
				'label' => esc_html__( 'Copyright', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'footer_copyright_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-copyright-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'footer_copyright_typography',
				'label' => esc_html__( 'Typography', 'hello-plus' ),
				'selector' => '{{WRAPPER}} .ehp-footer__copyright',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();
	}

	public function add_style_navigation_section(): void {
		$this->start_controls_section(
			'style_navigation_section',
			[
				'label' => esc_html__( 'Navigation', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'footer_menu_heading_heading',
			[
				'label' => esc_html__( 'Menu Heading', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'footer_menu_heading_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-menu-heading-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'footer_menu_heading_typography',
				'label' => esc_html__( 'Typography', 'hello-plus' ),
				'selector' => '{{WRAPPER}} .ehp-footer__menu-heading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_control(
			'footer_menu_items_heading',
			[
				'label' => esc_html__( 'Menu Items', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'footer_menu_items_typography',
				'label' => esc_html__( 'Typography', 'hello-plus' ),
				'selector' => '{{WRAPPER}} .ehp-footer__menu-item',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
			]
		);

		$this->start_controls_tabs(
			'style_navigation_tabs'
		);

		$this->start_controls_tab(
			'navigation_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->add_control(
			'style_navigation_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-menu-item-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'navigation_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
			]
		);

		$this->add_control(
			'style_navigation_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-menu-item-color-hover: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'style_navigation_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'footer_menu_spacing',
			[
				'label' => esc_html__( 'Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-menu-item-spacing: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	public function add_style_contact_section(): void {
		$this->start_controls_section(
			'style_contact_section',
			[
				'label' => esc_html__( 'Contact', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'footer_contact_heading_heading',
			[
				'label' => esc_html__( 'Contact Heading', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'footer_contact_heading_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-contact-heading-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'footer_contact_heading_typography',
				'label' => esc_html__( 'Typography', 'hello-plus' ),
				'selector' => '{{WRAPPER}} .ehp-footer__contact-heading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_control(
			'footer_contact_information_heading',
			[
				'label' => esc_html__( 'Contact Information', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'footer_contact_information_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-contact-information-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'footer_contact_information_typography',
				'label' => esc_html__( 'Typography', 'hello-plus' ),
				'selector' => '{{WRAPPER}} .ehp-footer__contact-information',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->end_controls_section();
	}

	public function add_style_box_section(): void {
		$this->start_controls_section(
			'style_box_section',
			[
				'label' => esc_html__( 'Box', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'footer_background_heading',
			[
				'label' => esc_html__( 'Background', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'footer_background',
				'label' => esc_html__( 'Background', 'hello-plus' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ehp-footer',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'footer_box_border',
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
			'footer_box_border_width',
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
					'{{WRAPPER}} .ehp-footer' => '--footer-box-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'footer_box_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'footer_box_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-box-border-color: {{VALUE}}',
				],
				'condition' => [
					'footer_box_border' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'footer_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'hello-plus' ),
				'selector' => '{{WRAPPER}} .ehp-footer',
			]
		);

		$this->add_responsive_control(
			'footer_text_width',
			[
				'label' => esc_html__( 'Text Width', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'tablet_default' => 'default',
				'mobile_default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
					'narrow' => esc_html__( 'Narrow', 'hello-plus' ),
					'wide' => esc_html__( 'Wide', 'hello-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-text-width: var(--footer-text-width-{{VALUE}});',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'footer_text_vertical_gap',
			[
				'label' => esc_html__( 'Vertical Content Gap', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'tablet_default' => 'default',
				'mobile_default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
					'narrow' => esc_html__( 'Narrow', 'hello-plus' ),
					'wide' => esc_html__( 'Wide', 'hello-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-footer' => '--footer-vertical-gap-content: var(--footer-vertical-gap-content-{{VALUE}}); --footer-vertical-gap-copyright: var(--footer-vertical-gap-copyright-{{VALUE}});',
				],
			]
		);

		$padding = new Ehp_Padding( $this, [
			'widget_name' => 'footer',
			'container_prefix' => 'box',
			'default_padding' => [
				'top' => 100,
				'right' => 100,
				'bottom' => 100,
				'left' => 100,
				'unit' => 'px',
			],
			'tablet_default_padding' => [
				'top' => 60,
				'right' => 60,
				'bottom' => 60,
				'left' => 60,
				'unit' => 'px',
			],
			'mobile_default_padding' => [
				'top' => 40,
				'right' => 40,
				'bottom' => 40,
				'left' => 40,
				'unit' => 'px',
			],
		] );
		$padding->add_style_controls();

		$this->end_controls_section();
	}
}
