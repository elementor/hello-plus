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

use HelloPlus\Modules\Theme\Module as Theme_Module;

class Ehp_Flex_Footer extends Ehp_Widget_Base {

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

		$style_depends[] = 'helloplus-footer';
		$style_depends[] = 'e-apple-webkit';

		return $style_depends;
	}

	protected function render(): void {
		$render_strategy = new Widget_Flex_Footer_Render( $this );

		$render_strategy->render();
	}

	protected function register_controls(): void {
		$this->add_content_section();
		$this->add_style_section();
	}

	public function add_content_section(): void {
		$this->add_content_layout_section();
		$this->add_content_business_details_section();
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
			'layout_preset_select',
			[
				'label' => esc_html__( 'Preset', 'hello-plus' ),
				'type' => Choose_Img_Control::CONTROL_NAME,
				'default' => 'info_hub',
				'label_block' => true,
				'columns' => 2,
				'options' => [
					'info_hub' => [
						'title' => wp_kses_post( "Info Hub:\nOrganize business details in a\nclear structure." ),
						'image' => HELLOPLUS_IMAGES_URL . 'footer-info-hub.svg',
						'hover_image' => true,
					],
					'quick_reference' => [
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
			'business_details_subheading',
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
					'layout_preset_select' => 'info_hub',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'business_details_description',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'placeholder' => esc_html__( 'Helping your business stand out with thoughtful details that drive action.', 'hello-plus' ),
				'separator' => 'before',
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
			$group_number . 'navigation_links_subheading',
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
				] ),
				'separator' => 'before',
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
					'separator' => 'before',
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
			'group_' . $group_number . '_links_subheading',
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
			'group_' . $group_number . '_social_subheading',
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

	public function add_style_section(): void {
		// Add your style controls here
	}

	public function add_custom_advanced_sections(): void {}
}
