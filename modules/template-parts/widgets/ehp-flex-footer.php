<?php

namespace HelloPlus\Modules\TemplateParts\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	Group_Control_Background,
	Group_Control_Box_Shadow,
	Group_Control_Css_Filter,
	Group_Control_Text_Shadow,
	Group_Control_Typography,
	Repeater,
};
use Elementor\Core\Kits\Documents\Tabs\{
	Global_Colors,
	Global_Typography,
};

use HelloPlus\Includes\Utils as Theme_Utils;

use HelloPlus\Modules\TemplateParts\Classes\{
	Render\Widget_Flex_Footer_Render,
	Control_Media_Preview,
};
use HelloPlus\Modules\Content\Classes\Choose_Img_Control;

use HelloPlus\Classes\{
	Ehp_Padding,
	Ehp_Shapes,
	Ehp_Social_Platforms,
};

use HelloPlus\Modules\Content\Traits\Widget_Repeater_Editable;

use HelloPlus\Modules\Theme\Module as Theme_Module;
use HelloPlus\Includes\Utils;

class Ehp_Flex_Footer extends Ehp_Widget_Base {

	use Widget_Repeater_Editable;

	public function get_name(): string {
		return 'ehp-flex-footer';
	}

	public function get_title(): string {
		return esc_html__( 'Hello+ Flex Footer', 'hello-plus' );
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
		$style_depends = Theme_Utils::elementor()->experiments->is_feature_active( 'e_font_icon_svg' )
			? parent::get_style_depends()
			: [ 'elementor-icons-fa-solid', 'elementor-icons-fa-brands', 'elementor-icons-fa-regular' ];

		$style_depends[] = 'helloplus-flex-footer';
		$style_depends[] = 'e-apple-webkit';

		return $style_depends;
	}

	protected function get_upsale_data(): array {
		return [
			'condition' => ! Utils::has_pro(),
			'image' => esc_url( HELLOPLUS_IMAGES_URL . 'go-pro.svg' ),
			'image_alt' => esc_attr__( 'Upgrade Now', 'hello-plus' ),
			'title' => esc_html__( 'Create custom footers', 'hello-plus' ),
			'description' => esc_html__( 'Adjust your footer to include contact forms, sitemaps and more with Elementor Pro.', 'hello-plus' ),
			'upgrade_url' => esc_url( 'https://go.elementor.com/helloplus-footer-pro' ),
			'upgrade_text' => esc_html__( 'Upgrade Now', 'hello-plus' ),
		];
	}

	public function get_custom_help_url(): string {
		return 'https://go.elementor.com/biz-footer-help';
	}

	protected function render(): void {
		$render_strategy = new Widget_Flex_Footer_Render( $this );
		$this->add_inline_editing_attributes( 'group_1_business_details_subheading', 'none' );
		$this->add_inline_editing_attributes( 'group_1_business_details_description', 'none' );
		$this->add_inline_editing_attributes( 'group_2_navigation_links_subheading', 'none' );
		$this->add_inline_editing_attributes( 'group_2_text_subheading', 'none' );
		$this->add_inline_editing_attributes( 'group_2_text_textarea', 'none' );
		$this->add_inline_editing_attributes( 'group_2_contact_links_subheading', 'none' );
		$this->add_inline_editing_attributes( 'group_2_social_links_subheading', 'none' );
		$this->add_inline_editing_attributes( 'group_3_navigation_links_subheading', 'none' );
		$this->add_inline_editing_attributes( 'group_3_text_subheading', 'none' );
		$this->add_inline_editing_attributes( 'group_3_text_textarea', 'none' );
		$this->add_inline_editing_attributes( 'group_3_contact_links_subheading', 'none' );
		$this->add_inline_editing_attributes( 'group_3_social_links_subheading', 'none' );
		$this->add_inline_editing_attributes( 'group_4_navigation_links_subheading', 'none' );
		$this->add_inline_editing_attributes( 'group_4_text_subheading', 'none' );
		$this->add_inline_editing_attributes( 'group_4_text_textarea', 'none' );
		$this->add_inline_editing_attributes( 'group_4_contact_links_subheading', 'none' );
		$this->add_inline_editing_attributes( 'group_4_social_links_subheading', 'none' );
		$this->add_inline_editing_attributes( 'copyright_text', 'none' );
		$render_strategy->render();
	}

	protected function register_controls(): void {
		$this->add_content_section();
		$this->add_style_section();
		$this->add_advanced_tab();
	}

	public function add_content_section(): void {
		$this->add_content_layout_section();
		$this->add_content_business_details_section();
		$this->add_content_copyright_section();
	}

	public function add_style_section(): void {
		$this->add_style_layout_section();
		$this->add_style_subheadings_section();
		$this->add_style_business_details_section();
		$this->add_style_copyright_section();
		$this->add_box_style_section();
	}

	public function add_content_layout_section(): void {
		$this->start_controls_section(
			'section_layout',
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
				'default' => 'info-hub',
				'label_block' => true,
				'columns' => 2,
				'toggle' => false,
				'options' => [
					'info-hub' => [
						'title' => wp_kses_post( "Info Hub:\nOrganize business details in a\nclear structure." ),
						'image' => HELLOPLUS_IMAGES_URL . 'footer-info-hub.svg',
						'hover_image' => true,
					],
					'quick-reference' => [
						'title' => wp_kses_post( "Quick Reference:\nHighlight key info at a\nglance." ),
						'image' => HELLOPLUS_IMAGES_URL . 'footer-quick-reference.svg',
						'hover_image' => true,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	public function add_content_business_details_section(): void {
		$this->start_controls_section(
			'section_business_details',
			[
				'label' => esc_html__( 'Business Details', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'business_details_heading',
			[
				'label' => esc_html__( 'Group 1 - Brand', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_content_brand_controls();

		$this->add_control(
			'group_1_business_details_subheading',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Built for connection', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => true,
				],
				'condition' => [
					'layout_preset' => 'info-hub',
				],
				'separator' => false,
			]
		);

		$this->add_control(
			'group_1_business_details_description',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => esc_html__( 'Helping your business stand out with thoughtful details that drive action.', 'hello-plus' ),
				'placeholder' => esc_html__( 'Enter your text here.', 'hello-plus' ),
				'separator' => false,
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => true,
				],
			]
		);

		$this->add_group_controls( '2' );

		$this->add_group_controls( '3' );

		$this->add_group_controls( '4' );

		$this->add_control(
			'subheading_tag',
			[
				'label' => esc_html__( 'Subheading HTML Tag', 'hello-plus' ),
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
				'default' => 'h6',
				'separator' => 'before',
				'condition' => [
					'layout_preset' => 'info-hub',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_group_controls( $group_number ) {
		$group_condition = [
			'group_' . $group_number . '_switcher' => 'yes',
		];

		$this->add_control(
			'group_' . $group_number . '_switcher',
			[
				'label' => sprintf( esc_html__( 'Group %d', 'hello-plus' ), $group_number ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'hello-plus' ),
				'label_off' => esc_html__( 'Hide', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$group_types = [
			'2' => 'navigation-links',
			'3' => 'contact-links',
			'4' => 'social-links',
		];

		$this->add_control(
			'group_' . $group_number . '_type',
			[
				'label' => esc_html__( 'Type', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'navigation-links' => esc_html__( 'Navigation Links', 'hello-plus' ),
					'contact-links' => esc_html__( 'Contact Links', 'hello-plus' ),
					'social-links' => esc_html__( 'Social Links', 'hello-plus' ),
					'text' => esc_html__( 'Text', 'hello-plus' ),
				],
				'default' => $group_types[ $group_number ] ?? '',
				'condition' => $group_condition,
			]
		);

		$this->add_navigation_links_controls( $group_number, $group_condition );

		$this->add_contact_links_controls( $group_number, $group_condition );

		$this->add_text_controls( $group_number, $group_condition );

		$this->add_social_links_controls( $group_number, $group_condition );
	}

	public function add_navigation_links_controls( $group_number, $group_condition ): void {
		$this->add_control(
			'group_' . $group_number . '_navigation_links_subheading',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Quick Links', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => true,
				],
				'condition' => array_merge( $group_condition, [
					'group_' . $group_number . '_type' => 'navigation-links',
					'layout_preset' => 'info-hub',
				] ),
				'separator' => false,
			]
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'footer_navigation_menu_' . $group_number,
				[
					'label' => esc_html__( 'Menu', 'hello-plus' ),
					'type' => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'save_default' => true,
					'separator' => false,
					'description' => sprintf(
						/* translators: 1: Link opening tag, 2: Link closing tag. */
						esc_html__( 'Go to the %1$sMenus screen%2$s to manage your menus.', 'hello-plus' ),
						sprintf( '<a href="%s" target="_blank">', self_admin_url( 'nav-menus.php' ) ),
						'</a>'
					),
					'condition' => array_merge( $group_condition, [
						'group_' . $group_number . '_type' => 'navigation-links',
					] ),
				]
			);
		} else {
			$this->add_control(
				'footer_menu_' . $group_number,
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
					'condition' => array_merge( $group_condition, [
						'group_' . $group_number . '_type' => 'navigation-links',
					] ),
				]
			);
		}
	}

	protected function add_contact_links_controls( $group_number, $group_condition ) {
		$this->add_control(
			'group_' . $group_number . '_contact_links_subheading',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Contact', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'condition' => array_merge( $group_condition, [
					'group_' . $group_number . '_type' => 'contact-links',
					'layout_preset' => 'info-hub',
				] ),
			]
		);

		$defaults = [
			'label_default' => esc_html__( 'Call', 'hello-plus' ),
			'platform_default' => 'telephone',
		];

		$repeater = new Repeater();

		$social_platforms = new Ehp_Social_Platforms( $this, [
			'prefix_attr' => 'group_' . $group_number,
			'repeater' => $repeater,
			'show_icon' => false,
		], $defaults );

		$social_platforms->add_repeater_controls();

		$this->add_control(
			'group_' . $group_number . '_repeater',
			[
				'label' => esc_html__( 'Links', 'hello-plus' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'prevent_empty' => true,
				'button_text' => esc_html__( 'Add Item', 'hello-plus' ),
				'title_field' => '{{{ group_' . $group_number . '_label }}}',
				'condition' => array_merge( $group_condition, [
					'group_' . $group_number . '_type' => 'contact-links',
				] ),
				'default' => [
					[
						'group_' . $group_number . '_label' => esc_html__( 'Email', 'hello-plus' ),
						'group_' . $group_number . '_platform' => 'email',
					],
					[
						'group_' . $group_number . '_label' => esc_html__( 'Call', 'hello-plus' ),
						'group_' . $group_number . '_platform' => 'telephone',
					],
					[
						'group_' . $group_number . '_label' => esc_html__( 'Visit', 'hello-plus' ),
						'group_' . $group_number . '_platform' => 'url',
					],
				],
			]
		);
	}

	protected function add_text_controls( $group_number, $group_condition ) {
		$this->add_control(
			'group_' . $group_number . '_text_subheading',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Office', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'condition' => array_merge( $group_condition, [
					'group_' . $group_number . '_type' => 'text',
					'layout_preset' => 'info-hub',
				] ),
			]
		);

		$this->add_control(
			'group_' . $group_number . '_text_textarea',
			[
				'label' => esc_html__( 'Text', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => wp_kses_post( "460 W 34th St\nNew York, NY 10001\n\nOpen M-F, 9am-5pm" ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => array_merge( $group_condition, [
					'group_' . $group_number . '_type' => 'text',
				] ),
			]
		);
	}

	protected function add_social_links_controls( $group_number, $group_condition ) {
		$this->add_control(
			'group_' . $group_number . '_social_links_subheading',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Follow', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'condition' => array_merge( $group_condition, [
					'group_' . $group_number . '_type' => 'social-links',
					'layout_preset' => 'info-hub',
				] ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'group_' . $group_number . '_social_label',
			[
				'label' => esc_html__( 'Accessible Name', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Instagram', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'group_' . $group_number . '_social_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fab fa-instagram',
					'library' => 'fa-brands',
				],
			]
		);

		$repeater->add_control(
			'group_' . $group_number . '_social_link',
			[
				'label' => esc_html__( 'Link', 'hello-plus' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'autocomplete' => true,
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter your URL', 'hello-plus' ),
			]
		);

		$this->add_control(
			'group_' . $group_number . '_social_repeater',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'prevent_empty' => true,
				'button_text' => esc_html__( 'Add Item', 'hello-plus' ),
				'title_field' => '{{{ group_' . $group_number . '_social_label }}}',
				'condition' => array_merge( $group_condition, [
					'group_' . $group_number . '_type' => 'social-links',
				] ),
				'default' => [
					[
						'group_' . $group_number . '_social_label' => esc_html__( 'Instagram', 'hello-plus' ),
						'group_' . $group_number . '_social_icon' => [
							'value' => 'fab fa-instagram',
							'library' => 'fa-brands',
						],
						'group_' . $group_number . '_social_link' => [
							'url' => 'https://www.instagram.com/',
						],
					],
					[
						'group_' . $group_number . '_social_label' => esc_html__( 'Tiktok', 'hello-plus' ),
						'group_' . $group_number . '_social_icon' => [
							'value' => 'fab fa-tiktok',
							'library' => 'fa-brands',
						],
						'group_' . $group_number . '_social_link' => [
							'url' => 'https://www.tiktok.com/',
						],
					],
					[
						'group_' . $group_number . '_social_label' => esc_html__( 'X (Twitter)', 'hello-plus' ),
						'group_' . $group_number . '_social_icon' => [
							'value' => 'fab fa-x-twitter',
							'library' => 'fa-brands',
						],
						'group_' . $group_number . '_social_link' => [
							'url' => 'https://www.twitter.com/',
						],
					],
				],
			]
		);
	}

	public function add_content_copyright_section(): void {
		$this->start_controls_section(
			'section_copyright',
			[
				'label' => esc_html__( 'Copyright', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'current_year_switcher',
			[
				'label' => esc_html__( 'Current Year', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'hello-plus' ),
				'label_off' => esc_html__( 'Hide', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'copyright_text',
			[
				'label' => esc_html__( 'Statement', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'All rights reserved.', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'label_block' => true,
				'ai' => [
					'active' => true,
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	public function add_style_layout_section(): void {
		$this->start_controls_section(
			'section_style_layout',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'style_business_details_heading',
			[
				'label' => esc_html__( 'Business Details', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'layout_preset' => 'info-hub',
				],
			]
		);

		$this->add_responsive_control(
			'style_layout_columns',
			[
				'label' => esc_html__( 'Columns', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => '4',
				'mobile_default' => '1',
				'tablet_default' => '2',
				'options' => [
					'1' => esc_html__( '1', 'hello-plus' ),
					'2' => esc_html__( '2', 'hello-plus' ),
					'3' => esc_html__( '3', 'hello-plus' ),
					'4' => esc_html__( '4', 'hello-plus' ),
				],
				'condition' => [
					'layout_preset' => 'info-hub',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-columns: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'style_layout_gaps',
			[
				'label' => esc_html__( 'Gaps', 'hello-plus' ),
				'type' => Controls_Manager::GAPS,
				'default' => [
					'row' => '60',
					'column' => '40',
					'unit' => 'px',
				],
				'mobile_default' => [
					'row' => '60',
					'column' => '40',
					'unit' => 'px',
				],
				'tablet_default' => [
					'row' => '60',
					'column' => '40',
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'condition' => [
					'layout_preset' => 'info-hub',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-row-gap: {{ROW}}{{UNIT}}; --flex-footer-column-gap: {{COLUMN}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style_layout_align_center_mobile',
			[
				'label' => esc_html__( 'Align Center on Mobile', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
				'condition' => [
					'layout_preset' => 'info-hub',
				],
			]
		);

		$this->add_responsive_control(
			'style_layout_content_alignment',
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
				'condition' => [
					'layout_preset' => 'quick-reference',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-content-alignment: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'style_layout_content_width',
			[
				'label' => esc_html__( 'Content Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'condition' => [
					'layout_preset' => 'quick-reference',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-content-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function add_style_subheadings_section(): void {
		$this->start_controls_section(
			'section_style_subheadings',
			[
				'label' => esc_html__( 'Subheadings', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'layout_preset',
							'operator' => '!==',
							'value' => 'quick-reference',
						],
					],
				],
			]
		);

		$this->add_control(
			'style_subheadings_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-subheading-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_subheadings_typography',
				'label' => esc_html__( 'Typography', 'hello-plus' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .ehp-flex-footer__subheading',
			]
		);

		$this->add_responsive_control(
			'style_subheadings_spacing',
			[
				'label' => esc_html__( 'Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-subheading-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function add_style_business_details_section(): void {
		$this->start_controls_section(
			'section_style_business_details',
			[
				'label' => esc_html__( 'Business Details', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_style_brand_controls( 'flex-footer' );

		$this->add_control(
			'style_business_details_description_heading',
			[
				'label' => esc_html__( 'Descriptions', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'style_business_details_description_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-description-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_business_details_description_typography',
				'label' => esc_html__( 'Typography', 'hello-plus' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .ehp-flex-footer__description',
			]
		);

		$this->add_responsive_control(
			'style_business_details_description_max_width',
			[
				'label' => esc_html__( 'Max Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-description-max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style_business_details_links_heading',
			[
				'label' => esc_html__( 'Links', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => $this->get_conditions_by_type_values( [ 'contact-links', 'social-links', 'navigation-links' ] ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_business_details_links_typography',
				'label' => esc_html__( 'Typography', 'hello-plus' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .ehp-flex-footer__link',
			]
		);

		$this->start_controls_tabs(
			'style_business_details_links_tabs',
			[
				'conditions' => $this->get_conditions_by_type_values( [ 'contact-links', 'social-links', 'navigation-links' ] ),
			]
		);

		$this->start_controls_tab(
			'style_business_details_links_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
				'conditions' => $this->get_conditions_by_type_values( [ 'contact-links', 'social-links', 'navigation-links' ] ),
			]
		);

		$this->add_control(
			'style_business_details_links_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-link-color: {{VALUE}}',
				],
				'conditions' => $this->get_conditions_by_type_values( [ 'contact-links', 'social-links', 'navigation-links' ] ),
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_business_details_links_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
				'conditions' => $this->get_conditions_by_type_values( [ 'contact-links', 'social-links', 'navigation-links' ] ),
			]
		);

		$this->add_control(
			'style_business_details_links_hover_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-link-color-hover: {{VALUE}}',
				],
				'conditions' => $this->get_conditions_by_type_values( [ 'contact-links', 'social-links', 'navigation-links' ] ),
			]
		);

		$this->add_control(
			'style_business_details_links_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'conditions' => $this->get_conditions_by_type_values( [ 'contact-links', 'social-links', 'navigation-links' ] ),
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'style_business_details_links_spacing',
			[
				'label' => esc_html__( 'Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-links-spacing: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $this->get_conditions_by_type_values( [ 'contact-links', 'social-links', 'navigation-links' ] ),
			]
		);

		$this->add_control(
			'style_business_details_icons_heading',
			[
				'label' => esc_html__( 'Icons', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => $this->get_conditions_by_type_values( [ 'social-links' ] ),
			]
		);

		$this->add_responsive_control(
			'style_business_details_icons_alignment',
			[
				'label' => esc_html__( 'Alignment', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'row' => esc_html__( 'Horizontal', 'hello-plus' ),
					'column' => esc_html__( 'Vertical', 'hello-plus' ),
				],
				'default' => 'row',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						$this->get_conditions_by_type_values( [ 'social-links' ] ),
						[
							'name' => 'layout_preset',
							'operator' => '===',
							'value' => 'info-hub',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-icons-alignment: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'style_business_details_icons_size',
			[
				'label' => esc_html__( 'Size', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'conditions' => $this->get_conditions_by_type_values( 'social-links' ),
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-social-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function add_style_copyright_section(): void {
		$this->start_controls_section(
			'copyright_style_section',
			[
				'label' => esc_html__( 'Copyright', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_copyright_typography',
				'label' => esc_html__( 'Typography', 'hello-plus' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer__copyright-text',
					'{{WRAPPER}} .ehp-flex-footer__copyright-prefix',
				],
			]
		);

		$this->add_control(
			'style_copyright_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-copyright-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'style_copyright_alignment',
			[
				'label' => esc_html__( 'Alignment', 'hello-plus' ),
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
					'end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-align-' . ( is_rtl() ? 'start' : 'end' ) . '-h',
					],
				],
				'default' => 'start',
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-copyright-alignment: {{VALUE}};',
				],
				'condition' => [
					'layout_preset' => 'info-hub',
				],
			]
		);

		$this->add_control(
			'style_copyright_separator',
			[
				'label' => esc_html__( 'Copyright Separator', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'hello-plus' ),
					'divider' => esc_html__( 'Divider', 'hello-plus' ),
					'background' => esc_html__( 'Background', 'hello-plus' ),
				],
				'default' => 'none',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'style_copyright_separator_width',
			[
				'label' => esc_html__( 'Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer__copyright-text-container' => 'border-top-width: {{SIZE}}{{UNIT}}; border-top-style: solid;',
				],
				'condition' => [
					'style_copyright_separator' => 'divider',
				],
			]
		);

		$this->add_control(
			'style_copyright_separator_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer__copyright-text-container' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
					'style_copyright_separator' => 'divider',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ehp-flex-footer__copyright',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#F6F7F8',
					],
				],
				'condition' => [
					'style_copyright_separator' => 'background',
				],
			]
		);

		$this->end_controls_section();
	}

	public function add_box_style_section(): void {
		$this->start_controls_section(
			'box_style_section',
			[
				'label' => esc_html__( 'Box', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'style_box_background_label',
			[
				'label' => esc_html__( 'Background', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_box_background',
				'label' => esc_html__( 'Background', 'hello-plus' ),
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ehp-flex-footer',
			]
		);

		$this->add_responsive_control(
			'style_box_element_spacing',
			[
				'label' => esc_html__( 'Element Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 20,
					'unit' => 'px',
				],
				'separator' => 'before',
				'condition' => [
					'layout_preset' => 'quick-reference',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-element-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'style_box_gap',
			[
				'label' => esc_html__( 'Gap', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'separator' => 'before',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 60,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => '--flex-footer-box-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style_box_border',
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
			'style_box_border_width',
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
					'{{WRAPPER}} .ehp-flex-footer' => 'border-top-width: {{SIZE}}{{UNIT}}; border-top-style: solid;',
				],
				'condition' => [
					'style_box_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'style_box_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-flex-footer' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
					'style_box_border' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'hello-plus' ),
				'selector' => '{{WRAPPER}} .ehp-flex-footer',
			]
		);

		$ehp_padding = new Ehp_Padding( $this, [
			'widget_name' => 'flex-footer',
			'container_prefix' => 'box',
			'default_padding' => [
				'top' => '100',
				'right' => '100',
				'bottom' => '100',
				'left' => '100',
			],
			'tablet_default_padding' => [
				'top' => '60',
				'right' => '60',
				'bottom' => '60',
				'left' => '60',
			],
			'mobile_default_padding' => [
				'top' => '32',
				'right' => '32',
				'bottom' => '32',
				'left' => '32',
			],
		] );
		$ehp_padding->add_style_controls();

		$this->end_controls_section();
	}

	public function add_custom_advanced_sections(): void {}

	protected function get_conditions_by_type_values( $type_values ) {
		$type_values = (array) $type_values;
		$terms = [];
		foreach ( [ 2, 3, 4 ] as $group_number ) {
			$group_terms = [
				[
					'name' => 'group_' . $group_number . '_type',
					'operator' => 'in',
					'value' => $type_values,
				],
				[
					'name' => 'group_' . $group_number . '_switcher',
					'operator' => '===',
					'value' => 'yes',
				],
			];
			$terms[] = [
				'relation' => 'and',
				'terms' => $group_terms,
			];
		}
		return [
			'relation' => 'or',
			'terms' => $terms,
		];
	}
}
