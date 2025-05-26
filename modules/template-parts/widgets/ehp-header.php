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
	Repeater
};
use Elementor\Core\Kits\Documents\Tabs\{
	Global_Typography,
	Global_Colors,
};

use HelloPlus\Includes\Utils as General_Utils;
use HelloPlus\Modules\TemplateParts\Classes\Render\Widget_Header_Render;
use HelloPlus\Modules\Content\Classes\Choose_Img_Control;

use HelloPlus\Modules\Theme\Module as Theme_Module;
use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Shapes,
	Ehp_Padding,
	Ehp_Social_Platforms,
};
use HelloPlus\Includes\Utils;

use HelloPlus\Modules\Content\Traits\Widget_Repeater_Editable;

class Ehp_Header extends Ehp_Widget_Base {

	use Widget_Repeater_Editable;

	public function get_name(): string {
		return 'ehp-header';
	}

	public function get_title(): string {
		return esc_html__( 'Hello+ Header', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLOPLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'header' ];
	}

	public function get_icon(): string {
		return 'eicon-single-page';
	}

	public function get_style_depends(): array {
		return array_merge( [ 'helloplus-header' ], Utils::get_widgets_depends() );
	}

	public function get_script_depends(): array {
		return [ 'helloplus-header-fe' ];
	}

	public function get_custom_help_url(): string {
		return 'https://go.elementor.com/biz-header-help';
	}

	protected function get_upsale_data(): array {
		return [
			'condition' => ! General_Utils::has_pro(),
			'image' => esc_url( HELLOPLUS_IMAGES_URL . 'go-pro.svg' ),
			'image_alt' => esc_attr__( 'Upgrade Now', 'hello-plus' ),
			'title' => esc_html__( 'Create custom headers', 'hello-plus' ),
			'description' => esc_html__( 'Add mega menus, search bars, login buttons, and more with Elementor Pro.', 'hello-plus' ),
			'upgrade_url' => esc_url( 'https://go.elementor.com/helloplus-header-pro' ),
			'upgrade_text' => esc_html__( 'Upgrade Now', 'hello-plus' ),
		];
	}

	protected function render(): void {
		$render_strategy = new Widget_Header_Render( $this );

		$this->add_inline_editing_attributes( 'primary_cta_button_text', 'none' );
		$this->add_inline_editing_attributes( 'secondary_cta_button_text', 'none' );

		$render_strategy->render();
	}

	protected function register_controls() {
		$this->add_content_tab();
		$this->add_style_tab();
		$this->add_advanced_tab();
	}

	protected function add_content_tab() {
		$this->add_content_layout_section();
		$this->add_content_site_logo_section();
		$this->add_content_navigation_section();
		$this->add_content_contact_buttons_section();
		$this->add_content_cta_section();
	}

	protected function add_style_tab() {
		$this->add_style_site_identity_section();
		$this->add_style_navigation_section();
		$this->add_style_contact_button_section();
		$this->add_style_cta_section();
		$this->add_style_box_section();
	}

	public function add_custom_advanced_sections(): void {
		$this->add_advanced_behavior_section();
	}

	protected function add_content_layout_section() {
		$this->start_controls_section(
			'layout_section',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout_preset_select',
			[
				'label' => esc_html__( 'Preset', 'hello-plus' ),
				'type' => Choose_Img_Control::CONTROL_NAME,
				'default' => 'navigate',
				'label_block' => true,
				'columns' => 2,
				'options' => [
					'identity' => [
						'title' => wp_kses_post( "Identity:\nSpotlight your brand\nwith your logo or site name\nin the center." ),
						'image' => HELLOPLUS_IMAGES_URL . 'header-identity.svg',
						'hover_image' => true,
					],
					'navigate' => [
						'title' => wp_kses_post( "Navigate:\nGuide visitors with a\ncentered menu." ),
						'image' => HELLOPLUS_IMAGES_URL . 'header-navigate.svg',
						'hover_image' => true,
					],
					'connect' => [
						'title' => wp_kses_post( "Connect:\nFocus on direct interaction\nwith clear contact options." ),
						'image' => HELLOPLUS_IMAGES_URL . 'header-connect.svg',
						'hover_image' => true,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_site_logo_section() {
		$this->start_controls_section(
			'site_logo_label',
			[
				'label' => esc_html__( 'Site Identity', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_content_brand_controls();

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
				'label' => esc_html__( 'Accessible Name', 'hello-plus' ),
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
						sprintf( '<a href="%s" target="_blank">', self_admin_url( 'nav-menus.php' ) ),
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
					'recommended' => [
						'fa-solid' => [
							'ellipsis-v',
							'ellipsis-h',
							'bars',
						],
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
						'none' => 'None',
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
					'recommended' => [
						'fa-solid' => [
							'caret-down',
							'chevron-down',
							'angle-down',
							'chevron-circle-down',
							'caret-square-down',
						],
						'fa-regular' => [
							'caret-square-down',
						],
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
						sprintf( '<a href="%s" target="_blank">', self_admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
						'</a>'
					),
					'separator' => 'after',
				]
			);
		}

		$this->end_controls_section();
	}

	protected function add_content_contact_buttons_section() {
		$this->start_controls_section(
			'contact_buttons',
			[
				'label' => esc_html__( 'Contact Button', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'contact_buttons_show',
			[
				'label' => esc_html__( 'Show', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'layout_preset_select!' => 'connect',
				],
			]
		);

		$this->add_control(
			'contact_buttons_show_connect',
			[
				'label' => esc_html__( 'Show', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'layout_preset_select' => 'connect',
				],
			]
		);

		$defaults = [
			'icon_default' => [
				'value' => 'fas fa-map-marker-alt',
				'library' => 'fa-solid',
			],
			'label_default' => esc_html__( 'Visit', 'hello-plus' ),
			'platform_default' => 'map',
		];

		$repeater = new Repeater();

		$social_platforms = new Ehp_Social_Platforms( $this, [
			'prefix_attr' => 'contact_buttons',
			'repeater' => $repeater,
		], $defaults );

		$social_platforms->add_repeater_controls();

		$this->add_control(
			'contact_buttons_repeater',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'prevent_empty' => true,
				'button_text' => esc_html__( 'Add Item', 'hello-plus' ),
				'title_field' => '{{{ contact_buttons_label }}}',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout_preset_select',
									'operator' => '!==',
									'value' => 'connect',
								],
								[
									'name' => 'contact_buttons_show',
									'operator' => '==',
									'value' => 'yes',
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout_preset_select',
									'operator' => '==',
									'value' => 'connect',
								],
								[
									'name' => 'contact_buttons_show_connect',
									'operator' => '==',
									'value' => 'yes',
								],
							],
						],
					],
				],
				'default' => [
					[
						'contact_buttons_label' => esc_html__( 'Visit', 'hello-plus' ),
						'selected_icon' => [
							'value' => 'fas fa-map-marker-alt',
							'library' => 'fa-solid',
						],
						'contact_buttons_platform' => 'map',
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_cta_section() {
		$defaults = [
			'secondary_cta_show' => 'no',
		];
		$button = new Ehp_Button( $this, [ 'widget_name' => 'header' ], $defaults );
		$button->add_content_section();
	}

	protected function add_style_site_identity_section() {
		$this->start_controls_section(
			'section_site_identity',
			[
				'label' => esc_html__( 'Site Identity', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_style_brand_controls( 'header' );

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

		$this->add_control(
			'style_align_menu',
			[
				'label' => esc_html__( 'Align Menu', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-' . ( is_rtl() ? 'end' : 'start' ) . '-h',
					],
					'end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-align-' . ( is_rtl() ? 'start' : 'end' ) . '-h',
					],
				],
				'default' => 'start',
				'tablet_default' => 'start',
				'mobile_default' => 'start',
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-align-menu: {{VALUE}}',
				],
				'condition' => [
					'layout_preset_select' => 'connect',
				],
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
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
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
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
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
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
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
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
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
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
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
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
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
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
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
			]
		);

		$shapes = new Ehp_Shapes( $this, [
			'widget_name' => 'header',
			'container_prefix' => 'submenu',
			'control_prefix' => 'style',
			'is_responsive' => false,
		] );
		$shapes->add_style_controls();

		$this->add_control(
			'style_responsive_menu_label',
			[
				'label' => esc_html__( 'Responsive Menu', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'style_responsive_menu_alert',
			[
				'type' => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'content' => esc_html__( 'To preview, select a responsive viewport icon.', 'hello-plus' ),
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
						'icon' => 'eicon-align-' . ( is_rtl() ? 'end' : 'start' ) . '-h',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
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
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
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
					'{{WRAPPER}} .ehp-header' => '--header-dropdown-divider-width: {{SIZE}}{{UNIT}};',
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
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
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
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-toggle-icon-color-active: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function add_style_contact_button_section() {
		$this->start_controls_section(
			'style_contact_button',
			[
				'label' => esc_html__( 'Contact Button', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'contact_buttons_show' => 'yes',
				],
			]
		);

		$this->add_control(
			'contact_buttons_link_type',
			[
				'label' => esc_html__( 'Link Type', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'icon' => esc_html__( 'Icon', 'hello-plus' ),
					'label' => esc_html__( 'Label', 'hello-plus' ),
				],
				'default' => 'icon',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'contact_buttons_typography',
				'selector' => '{{WRAPPER}} .ehp-header__contact-button-label',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'condition' => [
					'contact_buttons_link_type' => 'label',
				],
			]
		);

		$this->start_controls_tabs(
			'contact_button_tabs'
		);

		$this->start_controls_tab(
			'contact_button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->add_control(
			'contact_buttons_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-contact-button-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'contact_button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
			]
		);

		$this->add_control(
			'contact_buttons_color_hover',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-contact-button-color-hover: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'contact_button_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'contact_buttons_size',
			[
				'label' => esc_html__( 'Size', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 16,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-contact-button-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'contact_buttons_link_type' => 'icon',
				],
			]
		);

		$this->add_responsive_control(
			'contact_buttons_spacing',
			[
				'label' => esc_html__( 'Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 12,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-contact-button-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'contact_buttons_responsive_display',
			[
				'label' => esc_html__( 'Responsive Display', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'navbar' => esc_html__( 'Navbar', 'hello-plus' ),
					'dropdown' => esc_html__( 'Dropdown', 'hello-plus' ),
				],
				'default' => 'navbar',
				'separator' => 'before',
			]
		);

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

		$button = new Ehp_Button( $this, [ 'widget_name' => 'header' ] );
		$button->add_style_controls();

		$this->add_control(
			'cta_responsive_width',
			[
				'label' => esc_html__( 'Responsive Width', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'hello-plus' ),
					'stretch' => esc_html__( 'Stretch', 'hello-plus' ),
				],
				'separator' => 'before',
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
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ehp-header, {{WRAPPER}} .ehp-header .ehp-header__dropdown, {{WRAPPER}} .ehp-header .ehp-header__navigation',
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

		$this->add_responsive_control(
			'box_element_spacing',
			[
				'label' => __( 'Element Spacing', 'hello-plus' ),
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
					'{{WRAPPER}} .ehp-header' => '--header-element-spacing: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_preset_select' => 'connect',
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
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
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

		$padding = new Ehp_Padding( $this, [
			'widget_name' => 'header',
			'container_prefix' => 'box',
			'default_padding' => [
				'top' => 16,
				'right' => 32,
				'bottom' => 16,
				'left' => 32,
				'unit' => 'px',
			],
		] );
		$padding->add_style_controls();

		$this->end_controls_section();
	}

	private function add_advanced_behavior_section(): void {
		$this->start_controls_section(
			'advanced_behavior_section',
			[
				'label' => esc_html__( 'Behavior', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
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

		$shapes = new Ehp_Shapes( $this, [
			'widget_name' => 'header',
			'container_prefix' => 'float',
			'control_prefix' => 'behavior',
			'condition' => [
				'behavior_float' => 'yes',
			],
		] );
		$shapes->add_style_controls();

		$this->add_control(
			'behavior_onscroll_label',
			[
				'label' => esc_html__( 'On Scroll', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

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
				'label' => esc_html__( 'Scale Site Name', 'hello-plus' ),
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
				'selector' => '{{WRAPPER}} header.ehp-header.scroll-down, {{WRAPPER}} header.ehp-header.scroll-down .ehp-header__dropdown',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
				'condition' => [
					'behavior_sticky_change_bg' => 'yes',
				],
			]
		);

		$this->add_control(
			'blur_background',
			[
				'label' => esc_html__( 'Blur Background', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'blur_background_info',
			[
				'type' => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'content' => esc_html__( 'Add ', 'hello-plus' ) . ' <a href="https://elementor.com/help/choose-color/" target="_blank">' . esc_html__( 'transparency', 'hello-plus' ) . '</a>' . esc_html__( ' to both the Box and On Scroll background colors for Blur Background to take effect.', 'hello-plus' ),
				'condition' => [
					'blur_background' => 'yes',
				],
			]
		);

		$this->add_control(
			'blur_background_level',
			[
				'label' => esc_html__( 'Blur Level', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 15,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 7,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-header' => '--header-blur-background-level: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'blur_background' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}
}
