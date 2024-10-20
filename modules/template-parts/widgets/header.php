<?php

namespace HelloPlus\Modules\TemplateParts\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Widget_Base,
	Controls_Manager,
	Group_Control_Background,
	Group_Control_Box_Shadow,
	Group_Control_Typography
};
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use HelloPlus\Modules\TemplateParts\Classes\{
	Traits\Shared_Header_Traits,
	Render\Widget_Header_Render
};

use HelloPlus\Modules\Theme\Module as Theme_Module;

class Header extends Widget_Base {
	use Shared_Header_Traits;

	public function get_name(): string {
		return 'header';
	}

	public function get_title(): string {
		return esc_html__( 'Header', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLO_PLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'header' ];
	}

	public function get_icon(): string {
		return 'eicon-single-page';
	}

	public function get_style_depends(): array {
		return [ 'hello-plus-header' ];
	}

	protected function render(): void {
		$render_strategy = new Widget_Header_Render( $this );

		$render_strategy->render();
	}

	protected function register_controls() {
		$this->add_content_tab();
		$this->add_style_tab();
	}

	protected function add_content_tab() {
		$this->add_content_site_logo_section();
		$this->add_content_navigation_section();
		$this->add_content_cta_section();
	}

	protected function add_style_tab() {
		$this->add_style_site_identity_section();
		$this->add_style_navigation_section();
		$this->add_style_cta_section();
		$this->add_style_box_section();
		$this->add_style_behavior_section();
	}

	protected function add_content_site_logo_section() {
		$this->start_controls_section(
			'site_logo_label',
			[
				'label' => esc_html__( 'Site Logo', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'site_logo_brand_select',
			[
				'label' => esc_html__( 'Brand', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'logo' => esc_html__( 'Site Logo', 'hello-plus' ),
					'title' => esc_html__( 'Site Title', 'hello-plus' ),
				],
				'default' => 'logo',
				'tablet_default' => 'logo',
				'mobile_default' => 'logo',
			]
		);

		$this->add_control(
			'site_logo_image',
			[
				'label' => esc_html__( 'Choose Image', 'hello-plus' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => $this->get_site_logo_url(),
				],
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
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
				'content' => esc_html__( 'Go to', 'hello-plus' ) . ' <a href="#" onclick="elementorPro.modules.themeBuilder.openSiteIdentity( event )" >' . esc_html__( 'Site Identity > Site Description', 'hello-plus' ) . '</a>' . esc_html__( ' to edit the Site Title', 'hello-plus' ),
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

		$this->end_controls_section();
	}

	protected function add_content_navigation_section() {
		$this->start_controls_section(
			'section_navigation',
			[
				'label' => esc_html__( 'Navigation', 'hello-plus' ),
			]
		);

		$this->add_control(
			'navigation_menu_name',
			[
				'label' => esc_html__( 'Menu Name', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Menu', 'hello-plus' ),
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
					'separator' => 'after',
					'description' => sprintf(
						/* translators: 1: Link opening tag, 2: Link closing tag. */
						esc_html__( 'Go to the %1$sMenus screen%2$s to manage your menus.', 'hello-plus' ),
						sprintf( '<a href="%s" target="_blank">', admin_url( 'nav-menus.php' ) ),
						'</a>'
					),
				]
			);

			$this->add_control(
				'navigation_icon_label',
				[
					'label' => esc_html__( 'Responsive Toggle Icon', 'hello-plus' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'navigation_menu_icon',
				[
					'label' => esc_html__( 'Menu', 'hello-plus' ),
					'type' => Controls_Manager::ICONS,
					'skin' => 'inline',
					'label_block' => false,
					'default' => [
						'value' => 'fas fa-bars',
						'library' => 'fa-solid',
					],
					'exclude_inline_options' => [ 'none' ],
				]
			);

			$this->add_control(
				'navigation_breakpoint',
				[
					'label' => esc_html__( 'Breakpoint', 'hello-plus' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'mobile-portrait' => 'Mobile Portrait (> 767px)',
						'tablet-portrait' => 'Tablet Portrait (> 1024px)',
						'none' => 'none',
					],
					'default' => 'mobile-portrait',
					'separator' => 'after',
				]
			);

			$this->add_control(
				'navigation_menu_submenu_icon',
				[
					'label' => esc_html__( 'Submenu Indicator Icon', 'hello-plus' ),
					'type' => Controls_Manager::ICONS,
					'skin' => 'inline',
					'label_block' => false,
					'default' => [
						'value' => 'fas fa-caret-down',
						'library' => 'fa-solid',
					],
					'exclude_inline_options' => [ 'svg' ],
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
						sprintf( '<a href="%s" target="_blank">', admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
						'</a>'
					),
					'separator' => 'after',
				]
			);
		}

		$this->end_controls_section();
	}

	protected function add_content_cta_section() {
		$this->start_controls_section(
			'content_cta',
			[
				'label' => esc_html__( 'Call to Action', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'primary_cta_button_text',
			[
				'label' => esc_html__( 'Primary CTA', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Schedule Now', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
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

		$this->add_control(
			'primary_cta_button_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
			]
		);

		$this->add_control(
			'secondary_cta_show',
			[
				'label' => esc_html__( 'Secondary CTA', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'hello-plus' ),
				'label_off' => esc_html__( 'Hide', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'secondary_cta_button_text',
			[
				'label' => esc_html__( 'Secondary CTA', 'hello-plus' ),
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

		$this->add_control(
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

		$this->add_control(
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

		$this->end_controls_section();
	}

	protected function add_style_site_identity_section() {
		$this->start_controls_section(
			'section_site_identity',
			[
				'label' => esc_html__( 'Site Identity', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'style_align_logo',
			[
				'label' => esc_html__( 'Align Logo', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-start-h',
					],
					'2' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
						'icon' => 'eicon-align-center-h',
					],
				],
				'default' => '0',
				'tablet_default' => '0',
				'mobile_default' => '0',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-logo-order: {{VALUE}};',
				],
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->add_responsive_control(
			'style_logo_width',
			[
				'label' => __( 'Logo Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
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
					'{{WRAPPER}} .ehp-header' => '--header-logo-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'site_logo_brand_select' => 'logo',
				],
			]
		);

		$this->add_responsive_control(
			'style_align_title',
			[
				'label' => esc_html__( 'Align Site Title', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'0' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-start-h',
					],
					'2' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-align-center-h',
					],
				],
				'default' => '0',
				'tablet_default' => '0',
				'mobile_default' => '0',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-logo-order: {{VALUE}};',
				],
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->add_control(
			'style_title_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0052FF',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-site-title-color: {{VALUE}}',
				],
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_title_typography',
				'selector' => '{{WRAPPER}} .ehp-header__site-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'condition' => [
					'site_logo_brand_select' => 'title',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_navigation_section() {
		$this->start_controls_section(
			'section_navigation_style',
			[
				'label' => esc_html__( 'Navigation', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_navigation_typography',
				'selector' => '{{WRAPPER}} .ehp-header__item',
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
				'default' => '#555963',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-menu-item-color: {{VALUE}}',
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
				'default' => '#555963',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-menu-item-color-hover: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'style_navigation_pointer_hover',
			[
				'label' => esc_html__( 'Pointer', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'hello-plus' ),
					'underline' => esc_html__( 'Underline', 'hello-plus' ),
					'highlight' => esc_html__( 'Highlight', 'hello-plus' ),
				],
			]
		);

		$this->add_control(
			'style_navigation_hover_underline_color',
			[
				'label' => esc_html__( 'Underline Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555963',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-pointer-hover-underline-color: {{VALUE}}',
				],
				'condition' => [
					'style_navigation_pointer_hover' => 'underline',
				],
			]
		);

		$this->add_responsive_control(
			'style_navigation_hover_underline_width',
			[
				'label' => esc_html__( 'Underline Width', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3px',
				'options' => [
					'3px' => esc_html__( 'Default', 'hello-plus' ),
					'1px' => esc_html__( 'Thin', 'hello-plus' ),
					'5px' => esc_html__( 'Thick', 'hello-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-pointer-hover-underline-width: {{VALUE}}',
				],
				'condition' => [
					'style_navigation_pointer_hover' => 'underline',
				],
			]
		);

		$this->add_control(
			'style_navigation_hover_highlight_color',
			[
				'label' => esc_html__( 'Highlight Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E0EAFF',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-pointer-hover-highlight-bg-color: {{VALUE}}',
				],
				'condition' => [
					'style_navigation_pointer_hover' => 'highlight',
				],
			]
		);

		$this->add_responsive_control(
			'style_navigation_hover_highlight_width',
			[
				'label' => esc_html__( 'Highlight Width', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
					'thin' => esc_html__( 'Thin', 'hello-plus' ),
					'thick' => esc_html__( 'Thick', 'hello-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-pointer-hover-highlight-padding-inline: var(--header-pointer-hover-highlight-padding-inline-{{VALUE}}); --header-pointer-hover-highlight-padding-block: var(--header-pointer-hover-highlight-padding-block-{{VALUE}});',
				],
				'condition' => [
					'style_navigation_pointer_hover' => 'highlight',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'navigation_active_tab',
			[
				'label' => esc_html__( 'Active', 'hello-plus' ),
			]
		);

		$this->add_control(
			'style_navigation_text_color_active',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555963',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-menu-item-color-active: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'style_navigation_focus_active',
			[
				'label' => esc_html__( 'Focus Animation', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'hello-plus' ),
					'underline' => esc_html__( 'Underline', 'hello-plus' ),
					'highlight' => esc_html__( 'Highlight', 'hello-plus' ),
				],
			]
		);

		$this->add_control(
			'style_navigation_active_underline_color',
			[
				'label' => esc_html__( 'Underline Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555963',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-focus-active-underline-color: {{VALUE}}',
				],
				'condition' => [
					'style_navigation_focus_active' => 'underline',
				],
			]
		);

		$this->add_responsive_control(
			'style_navigation_active_underline_width',
			[
				'label' => esc_html__( 'Underline Width', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3px',
				'options' => [
					'3px' => esc_html__( 'Default', 'hello-plus' ),
					'1px' => esc_html__( 'Thin', 'hello-plus' ),
					'5px' => esc_html__( 'Thick', 'hello-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-focus-active-underline-width: {{VALUE}}',
				],
				'condition' => [
					'style_navigation_focus_active' => 'underline',
				],
			]
		);

		$this->add_control(
			'style_navigation_active_highlight_color',
			[
				'label' => esc_html__( 'Highlight Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555963',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-focus-active-highlight-bg-color: {{VALUE}}',
				],
				'condition' => [
					'style_navigation_focus_active' => 'highlight',
				],
			]
		);

		$this->add_responsive_control(
			'style_navigation_active_highlight_width',
			[
				'label' => esc_html__( 'Highlight Width', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
					'thin' => esc_html__( 'Thin', 'hello-plus' ),
					'thick' => esc_html__( 'Thick', 'hello-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-focus-active-highlight-padding-inline: var(--header-focus-active-highlight-padding-inline-{{VALUE}}); --header-focus-active-highlight-padding-block: var(--header-focus-active-highlight-padding-block-{{VALUE}});',
				],
				'condition' => [
					'style_navigation_focus_active' => 'highlight',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'menu_item_spacing',
			[
				'label' => __( 'Menu Item Spacing', 'hello-plus' ),
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
					'size' => 32,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 32,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 32,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-menu-item-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style_submenu_label',
			[
				'label' => esc_html__( 'Submenu', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'style_submenu_layout',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'horizontal' => 'Horizontal',
					'vertical' => 'Vertical',
				],
				'default' => 'horizontal',
				'selectors' => [
					'{{WRAPPER}} .ehp-cta' => '--cta-text-heading-width: var(--cta-text-{{VALUE}}-heading); --cta-text-description-width: var(--cta-text-{{VALUE}}-description);',
				],
			]
		);

		$this->add_control(
			'style_submenu_shape',
			[
				'label' => esc_html__( 'Shape', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => 'Default',
					'sharp' => 'Sharp',
					'rounded' => 'Rounded',
					'round' => 'Round',
				],
				'default' => 'default',
				'selectors' => [
					'{{WRAPPER}} .ehp-cta' => '--cta-text-heading-width: var(--cta-text-{{VALUE}}-heading); --cta-text-description-width: var(--cta-text-{{VALUE}}-description);',
				],
			]
		);

		$this->add_control(
			'style_responsive_menu_label',
			[
				'label' => esc_html__( 'Responsive Menu', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'description' => 'To preview, select a responsive viewport icon.',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'style_responsive_menu_align',
			[
				'label' => esc_html__( 'Text Align', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-start-h',
					],
					'center' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-align-center-h',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-dropdown-text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'style_responsive_menu_divider',
			[
				'label' => esc_html__( 'Divider', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'style_responsive_menu_divider_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E0E1E2',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-dropdown-divider-color: {{VALUE}}',
				],
				'condition' => [
					'style_responsive_menu_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'style_responsive_menu_divider_width',
			[
				'label' => esc_html__( 'Weight', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-cta' => '--cta-button-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'style_responsive_menu_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'style_responsive_menu_icon_size',
			[
				'label' => esc_html__( 'Toggle Icon Size', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 22,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 22,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 22,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-toggle-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'style_toggle_icon_tabs'
		);

		$this->start_controls_tab(
			'toggle_icon_tabs_normal',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->add_control(
			'style_toggle_icon_color',
			[
				'label' => esc_html__( 'Toggle Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555963',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-toggle-icon-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_icon_tabs_active',
			[
				'label' => esc_html__( 'Active', 'hello-plus' ),
			]
		);

		$this->add_control(
			'style_toggle_icon_color_active',
			[
				'label' => esc_html__( 'Toggle Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555963',
				'selectors' => [
					'{{WRAPPER}} .ehp-cta' => '--cta-button-text-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function add_style_cta_section() {
		$this->start_controls_section(
			'style_cta',
			[
				'label' => esc_html__( 'Call to Action', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_cta_button_controls( 'primary' );

		$this->add_cta_button_controls( 'secondary', true );

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
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ehp-header, {{WRAPPER}} .ehp-header .ehp-header__dropdown',
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
					'{{WRAPPER}} .ehp-header' => '--header-box-border-width: {{SIZE}}{{UNIT}};',
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
				'default' => '#555963',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-box-border-color: {{VALUE}}',
				],
				'condition' => [
					'show_box_border' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-header',
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'default' => [
					'top' => 16,
					'right' => 32,
					'bottom' => 16,
					'left' => 32,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-box-padding-block-end: {{BOTTOM}}{{UNIT}}; --header-box-padding-block-start: {{TOP}}{{UNIT}}; --header-box-padding-inline-end: {{RIGHT}}{{UNIT}}; --header-box-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_cta_button_controls( string $type, bool $add_condition = false ) {
		$is_primary = 'primary' === $type;
		$label = $is_primary ? esc_html__( 'Primary CTA', 'hello-plus' ) : esc_html__( 'Secondary CTA', 'hello-plus' );
		$show_button_border_default = $is_primary ? 'no' : 'yes';
		$text_color_default = $is_primary ? '#ffffff' : '#555963';
		$background_color_default = $is_primary ? '#0052FF' : '#F6F7F8';

		$add_type_condition = $add_condition ? [
			$type . '_cta_show' => 'yes',
		] : [];

		$this->add_control(
			$type . '_button_label',
			[
				'label' => $label,
				'type' => Controls_Manager::HEADING,
				'condition' => $add_type_condition,
			]
		);

		$this->add_control(
			$type . '_button_type',
			[
				'label' => esc_html__( 'Type', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'button',
				'options' => [
					'button' => esc_html__( 'Button', 'hello-plus' ),
					'link' => esc_html__( 'Link', 'hello-plus' ),
				],
				'condition' => $add_type_condition,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => $type . '_button_typography',
				'selector' => '{{WRAPPER}} .ehp-header__button--' . $type,
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'condition' => $add_type_condition,
			]
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .ehp-header__button--' . $type => 'flex-direction: {{VALUE}};',
				],
				'condition' => array_merge([
					$type . '_cta_button_icon[value]!' => '',
				], $add_type_condition),
			]
		);

		$this->add_control(
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
					'{{WRAPPER}} .ehp-header' => '--header-button-' . $type . '-icon-spacing: {{SIZE}}{{UNIT}};',
				],
				'condition' => array_merge([
					$type . '_cta_button_icon[value]!' => '',
				], $add_type_condition),
			]
		);

		$this->start_controls_tabs(
			$type . '_button_style'
		);

		$this->start_controls_tab(
			$type . '_button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
				'condition' => $add_type_condition,
			]
		);

		$this->add_control(
			$type . '_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => $text_color_default,
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-button-' . $type . '-text-color: {{VALUE}}',
				],
				'condition' => $add_type_condition,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => $type . '_button_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ehp-header__button--' . $type,
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => $background_color_default,
					],
				],
				'condition' => array_merge([
					$type . '_button_type' => 'button',
				], $add_type_condition),
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			$type . '_button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
				'condition' => $add_type_condition,
			]
		);

		$this->add_control(
			$type . '_hover_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => $text_color_default,
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-button-' . $type . '-text-color-hover: {{VALUE}}',
				],
				'condition' => $add_type_condition,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => $type . '_button_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ehp-header__button--' . $type . ':hover, {{WRAPPER}} .ehp-header__button--' . $type . ':focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => $background_color_default,
					],
				],
				'condition' => array_merge([
					$type . '_button_type' => 'button',
				], $add_type_condition),
			]
		);

		$this->add_control(
			$type . '_button_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'condition' => $add_type_condition,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
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

		$this->add_control(
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
					'{{WRAPPER}} .ehp-header' => '--header-button-' . $type . '-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => array_merge([
					$type . '_show_button_border' => 'yes',
				], $add_type_condition),
			]
		);

		$this->add_control(
			$type . '_button_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-button-' . $type . '-border-color: {{VALUE}}',
				],
				'condition' => array_merge([
					$type . '_show_button_border' => 'yes',
				], $add_type_condition),
			]
		);

		$this->add_control(
			$type . '_button_shape',
			[
				'label' => esc_html__( 'Shape', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
					'sharp' => esc_html__( 'Sharp', 'hello-plus' ),
					'round' => esc_html__( 'Round', 'hello-plus' ),
					'rounded' => esc_html__( 'Rounded', 'hello-plus' ),
				],
				'condition' => array_merge([
					$type . '_button_type' => 'button',
				], $add_type_condition),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => $type . '_button_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-header__button--' . $type,
				'condition' => array_merge([
					$type . '_button_type' => 'button',
				], $add_type_condition),
			]
		);

		$this->add_responsive_control(
			$type . '_button_padding',
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-button-' . $type . '-padding-block-end: {{BOTTOM}}{{UNIT}}; --header-button-' . $type . '-padding-block-start: {{TOP}}{{UNIT}}; --header-button-' . $type . '-padding-inline-end: {{RIGHT}}{{UNIT}}; --header-button-' . $type . '-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => array_merge([
					$type . '_button_type' => 'button',
				], $add_type_condition),
			]
		);
	}

	private function add_style_behavior_section(): void {
		$this->start_controls_section(
			'advanced_behavior_section',
			[
				'label' => esc_html__( 'Behavior', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'behavior_float',
			[
				'label' => esc_html__( 'Float', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_responsive_control(
			'behavior_float_offset',
			[
				'label' => esc_html__( 'Offset', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 16,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-float-offset: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'behavior_float' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'behavior_float_width',
			[
				'label' => esc_html__( 'Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 1140,
					],
				],
				'default' => [
					'size' => 1140,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-float-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'behavior_float' => 'yes',
				],
			]
		);

		$this->add_control(
			'behavior_float_shape',
			[
				'label' => esc_html__( 'Shape', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
					'sharp' => esc_html__( 'Sharp', 'hello-plus' ),
					'round' => esc_html__( 'Round', 'hello-plus' ),
					'rounded' => esc_html__( 'Rounded', 'hello-plus' ),
				],
				'condition' => [
					'behavior_float' => 'yes',
				],
			]
		);

		$this->add_control(
			'behavior_onscroll_label',
			[
				'label' => esc_html__( 'On Scroll', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// TODO: check if this needs to be responsive
		$this->add_control(
			'behavior_onscroll_select',
			[
				'label' => esc_html__( 'Sticky', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'scroll-up',
				'options' => [
					'scroll-up' => esc_html__( 'On Scroll Up', 'hello-plus' ),
					'always' => esc_html__( 'Always', 'hello-plus' ),
					'none' => esc_html__( 'None', 'hello-plus' ),
				],
			]
		);

		$this->add_control(
			'behavior_sticky_scale_logo',
			[
				'label' => esc_html__( 'Scale Site Logo', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'behavior_onscroll_select',
							'operator' => '==',
							'value' => 'always',
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

		$this->add_responsive_control(
			'behavior_sticky_width',
			[
				'label' => esc_html__( 'Logo Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 34,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 28,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 24,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-logo-width-sticky: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'behavior_onscroll_select',
							'operator' => '==',
							'value' => 'always',
						],
						[
							'name' => 'behavior_sticky_scale_logo',
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
			'behavior_sticky_scale_title',
			[
				'label' => esc_html__( 'Scale Site Title', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'behavior_onscroll_select',
							'operator' => '==',
							'value' => 'always',
						],
						[
							'name' => 'site_logo_brand_select',
							'operator' => '==',
							'value' => 'title',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'behavior_sticky_scale_title_size',
			[
				'label' => esc_html__( 'Font Size', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-title-size-sticky: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'behavior_onscroll_select',
							'operator' => '==',
							'value' => 'always',
						],
						[
							'name' => 'behavior_sticky_scale_title',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'site_logo_brand_select',
							'operator' => '==',
							'value' => 'title',
						],
					],
				],
			]
		);

		$this->add_control(
			'behavior_sticky_scale_title_weight',
			[
				'label' => esc_html__( 'Font Weight', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => '800',
				'options' => [
					'100' => esc_html__( '100', 'hello-plus' ),
					'200' => esc_html__( '200', 'hello-plus' ),
					'300' => esc_html__( '300', 'hello-plus' ),
					'400' => esc_html__( '400', 'hello-plus' ),
					'500' => esc_html__( '500', 'hello-plus' ),
					'600' => esc_html__( '600', 'hello-plus' ),
					'700' => esc_html__( '700', 'hello-plus' ),
					'800' => esc_html__( '800', 'hello-plus' ),
					'900' => esc_html__( '900', 'hello-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-title-weight-sticky: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'behavior_onscroll_select',
							'operator' => '==',
							'value' => 'always',
						],
						[
							'name' => 'behavior_sticky_scale_title',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'site_logo_brand_select',
							'operator' => '==',
							'value' => 'title',
						],
					],
				],
			]
		);

		$this->add_control(
			'behavior_sticky_change_bg',
			[
				'label' => esc_html__( 'Change Background Color', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'behavior_onscroll_select' => 'always',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'behavior_sticky_bg',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ehp-header.scroll-down, {{WRAPPER}} .ehp-header.scroll-down .ehp-header__dropdown',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#FFFFFF',
					],
				],
				'condition' => [
					'behavior_sticky_change_bg' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}
}
