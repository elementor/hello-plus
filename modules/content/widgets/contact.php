<?php

namespace HelloPlus\Modules\Content\Widgets;

use HelloPlus\Modules\Content\Classes\Choose_Img_Control;
use HelloPlus\Modules\Content\Classes\Render\Widget_Contact_Render;
use HelloPlus\Modules\Theme\Module as Theme_Module;
use HelloPlus\Includes\Utils;
use HelloPlus\Classes\{
	Ehp_Padding,
	Ehp_Shapes,
	Ehp_Social_Platforms,
};
use HelloPlus\Modules\Content\Base\Traits\Shared_Content_Traits;

use Elementor\{
	Controls_Manager,
	Group_Control_Typography,
	Group_Control_Background,
	Group_Control_Box_Shadow,
	Repeater,
};
use Elementor\Core\Kits\Documents\Tabs\{
	Global_Typography,
	Global_Colors,
};
use Elementor\Widget_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Settings;

class Contact extends Widget_Base {
	use Shared_Content_Traits;

	public function get_name(): string {
		return 'contact';
	}

	public function get_title(): string {
		return esc_html__( 'Contact', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLOPLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'contact' ];
	}

	public function get_icon(): string {
		return 'eicon-email-field';
	}

	public function get_style_depends(): array {
		return array_merge( [ 'helloplus-contact' ], Utils::get_widgets_depends() );
	}

	protected function render(): void {
		$render_strategy = new Widget_Contact_Render( $this );

		$render_strategy->render();
	}

	protected function register_controls() {
		$this->add_content_section();
		$this->add_style_section();
	}

	protected function add_content_section() {
		$this->add_layout_content_section();
		$this->add_text_content_section();
		$this->add_contact_details_content_section();
		$this->add_map_content_section();
	}

	protected function add_style_section() {
		$this->add_layout_style_section();
		$this->add_text_style_section();
		$this->add_contact_details_style_section();
		$this->add_map_style_section();
		$this->add_box_style_section();
	}

	protected function add_layout_content_section() {
		$this->start_controls_section(
			'layout_section',
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
				'default' => 'locate',
				'label_block' => true,
				'columns' => 2,
				'options' => [
					'locate' => [
						'title' => wp_kses_post( "Locate: Highlight your\nlocation to help your\nclients find you." ),
						'image' => HELLOPLUS_IMAGES_URL . 'contact-locate.svg',
						'hover_image' => true,
					],
					'touchpoint' => [
						'title' => wp_kses_post( "Touchpoint:\nEncourage direct\ncontact to help clients\nconnect with you." ),
						'image' => HELLOPLUS_IMAGES_URL . 'contact-touchpoint.svg',
						'hover_image' => true,
					],
					'quick-info' => [
						'title' => wp_kses_post( "Quick info: Share\nessential business\ndetails at a glance for\nfast access." ),
						'image' => HELLOPLUS_IMAGES_URL . 'contact-quick-info.svg',
						'hover_image' => true,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function add_text_content_section() {
		$this->start_controls_section(
			'text_section',
			[
				'label' => esc_html__( 'Text', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'heading_text',
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
			'heading_tag',
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
			]
		);

		$this->add_control(
			'description_text',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => htmlspecialchars_decode( __( 'Have questions or ready to take the next step? Reach out to us—we\'re here to help with your fitness journey. Whether you\'re looking for guidance, scheduling a session, or just want more information, we\'ve got you covered.', 'hello-plus' ) ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'description_tag',
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
				'default' => 'p',
			]
		);

		$this->end_controls_section();
	}

	protected function add_contact_details_content_section() {
		$this->start_controls_section(
			'contact_details_section',
			[
				'label' => esc_html__( 'Contact Details', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_controls( '1' );

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
				'default' => 'h3',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function add_map_content_section() {
		$this->start_controls_section(
			'map_section',
			[
				'label' => esc_html__( 'Map', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		if ( Utils::elementor()->editor->is_edit_mode() ) {
			$api_key = get_option( 'elementor_google_maps_api_key' );

			if ( ! $api_key ) {
				$this->add_control(
					'api_key_notification',
					[
						'type' => Controls_Manager::ALERT,
						'alert_type' => 'info',
						'content' => sprintf(
							/* translators: 1: Integration settings link open tag, 2: Create API key link open tag, 3: Link close tag. */
							esc_html__( 'Set your Google Maps API Key in Elementor\'s %1$sIntegrations Settings%3$s page. Create your key %2$shere.%3$s', 'hello-plus' ),
							'<a href="' . Settings::get_settings_tab_url( 'integrations' ) . '" target="_blank">',
							'<a href="https://developers.google.com/maps/documentation/embed/get-api-key" target="_blank">',
							'</a>'
						),
					]
				);
			}
		}

		$default_address = esc_html__( 'London Eye, London, United Kingdom', 'hello-plus' );
		$this->add_control(
			'map_address',
			[
				'label' => esc_html__( 'Location', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
					],
				],
				'ai' => [
					'active' => false,
				],
				'placeholder' => $default_address,
				'default' => $default_address,
				'label_block' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function add_group_controls( $group_number ) {
		$group_condition = 1 === $group_number ? [] : [
			'group_' . $group_number . '_switcher' => 'yes',
		];

		if ( '1' === $group_number ) {
			$this->add_control(
				'group_' . $group_number . '_heading',
				[
					'label' => sprintf( esc_html__( 'Group %d', 'hello-plus' ), $group_number ),
					'type' => Controls_Manager::HEADING,
				]
			);
		} else {
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
		}

		$group_types = [
			'1' => 'contact-links',
			'3' => 'contact-links',
			'2' => 'text',
			'4' => 'social-icons',
		];

		$this->add_control(
			'group_' . $group_number . '_type',
			[
				'label' => esc_html__( 'Type', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'contact-links' => esc_html__( 'Contact Links', 'hello-plus' ),
					'text' => esc_html__( 'Text', 'hello-plus' ),
					'social-icons' => esc_html__( 'Social Icons', 'hello-plus' ),
				],
				'default' => $group_types[ $group_number ] ?? '',
				'condition' => $group_condition,
			]
		);

		$this->add_contact_links_controls( $group_number, $group_condition );

		$this->add_text_controls( $group_number, $group_condition );

		$this->add_social_controls( $group_number, $group_condition );
	}

	protected function add_contact_links_controls( $group_number, $group_condition ) {
		$group_subheadings = [
			'1' => esc_html__( 'Let\'s talk', 'hello-plus' ),
			'2' => esc_html__( 'Hours', 'hello-plus' ),
			'3' => esc_html__( 'Visit', 'hello-plus' ),
			'4' => esc_html__( 'Follow', 'hello-plus' ),
		];

		$this->add_control(
			'group_' . $group_number . '_links_subheading',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => $group_subheadings[ $group_number ] ?? '',
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => array_merge( $group_condition, [
					'group_' . $group_number . '_type' => 'contact-links',
				] ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'group_' . $group_number . '_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-phone',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'envelope',
						'phone-alt',
						'phone',
						'mobile',
						'mobile-alt',
						'sms',
						'comment-dots',
						'map-marker-alt',
						'map-marker',
						'location-arrow',
						'map',
						'link',
						'globe',
					],
					'fa-regular' => [
						'envelope',
						'comment-dots',
						'map',
					],
					'fa-brands' => [
						'whatsapp',
						'whatsapp-square',
						'skype',
						'facebook-messenger',
						'viber',
						'waze',
					],
				],
			]
		);

		$repeater->add_control(
			'group_' . $group_number . '_label',
			[
				'label' => esc_html__( 'Label', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Call', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'group_' . $group_number . '_platform',
			[
				'label' => esc_html__( 'Platform', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'email' => esc_html__( 'Email', 'hello-plus' ),
					'telephone' => esc_html__( 'Telephone', 'hello-plus' ),
					'sms' => esc_html__( 'SMS', 'hello-plus' ),
					'whatsapp' => esc_html__( 'Whatsapp', 'hello-plus' ),
					'skype' => esc_html__( 'Skype', 'hello-plus' ),
					'messenger' => esc_html__( 'Messenger', 'hello-plus' ),
					'viber' => esc_html__( 'Viber', 'hello-plus' ),
					'map' => esc_html__( 'Map', 'hello-plus' ),
					'waze' => esc_html__( 'Waze', 'hello-plus' ),
					'url' => esc_html__( 'URL', 'hello-plus' ),
				],
				'default' => 'telephone',
			],
		);

		$repeater->add_control(
			'group_' . $group_number . '_mail',
			[
				'label' => esc_html__( 'Email', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
				'label_block' => true,
				'placeholder' => esc_html__( '@', 'hello-plus' ),
				'default' => '',
				'condition' => [
					'group_' . $group_number . '_platform' => 'email',
				],
			],
		);

		$repeater->add_control(
			'group_' . $group_number . '_mail_subject',
			[
				'label' => esc_html__( 'Subject', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => '',
				'condition' => [
					'group_' . $group_number . '_platform' => 'email',
				],
			],
		);

		$repeater->add_control(
			'group_' . $group_number . '_mail_body',
			[
				'label' => esc_html__( 'Message', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'condition' => [
					'group_' . $group_number . '_platform' => 'email',
				],
			]
		);

		$repeater->add_control(
			'group_' . $group_number . '_number',
			[
				'label' => esc_html__( 'Number', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => false,
				],
				'ai' => [
					'active' => false,
				],
				'label_block' => true,
				'placeholder' => esc_html__( '+', 'hello-plus' ),
				'condition' => [
					'group_' . $group_number . '_platform' => [
						'sms',
						'whatsapp',
						'viber',
						'telephone',
					],
				],
			],
		);

		$repeater->add_control(
			'group_' . $group_number . '_username',
			[
				'label' => esc_html__( 'Username', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter your username', 'hello-plus' ),
				'condition' => [
					'group_' . $group_number . '_platform' => [
						'messenger',
						'skype',
					],
				],
			],
		);

		$repeater->add_control(
			'group_' . $group_number . '_url',
			[
				'label' => esc_html__( 'Link', 'hello-plus' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
				'autocomplete' => true,
				'label_block' => true,
				'condition' => [
					'group_' . $group_number . '_platform' => [
						'url',
					],
				],
				'placeholder' => esc_html__( 'https://www.', 'hello-plus' ),
			],
		);

		$repeater->add_control(
			'group_' . $group_number . '_waze',
			[
				'label' => esc_html__( 'Link', 'hello-plus' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
				'autocomplete' => true,
				'label_block' => true,
				'condition' => [
					'group_' . $group_number . '_platform' => [
						'waze',
					],
				],
				'placeholder' => esc_html__( 'https://ul.waze.com/ul?place=', 'hello-plus' ),
			],
		);

		$repeater->add_control(
			'group_' . $group_number . '_map',
			[
				'label' => esc_html__( 'Link', 'hello-plus' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
				'autocomplete' => true,
				'label_block' => true,
				'condition' => [
					'group_' . $group_number . '_platform' => [
						'map',
					],
				],
				'placeholder' => esc_html__( 'https://maps.app.goo.gl', 'hello-plus' ),
			],
		);

		$repeater->add_control(
			'group_' . $group_number . '_action',
			[
				'label' => esc_html__( 'Action', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'chat',
				'dynamic' => [
					'active' => true,
				],
				'options' => [
					'call' => 'Call',
					'chat' => 'Chat',
				],
				'condition' => [
					'group_' . $group_number . '_platform' => [
						'viber',
						'skype',
					],
				],
			]
		);

		$shared_defaults = [
			[
				'group_' . $group_number . '_icon' => [
					'value' => 'fas fa-phone',
					'library' => 'fa-solid',
				],
				'group_' . $group_number . '_label' => esc_html__( 'Call', 'hello-plus' ),
				'group_' . $group_number . '_platform' => 'telephone',
			],
			[
				'group_' . $group_number . '_icon' => [
					'value' => 'fas fa-envelope',
					'library' => 'fa-solid',
				],
				'group_' . $group_number . '_label' => esc_html__( 'Email', 'hello-plus' ),
				'group_' . $group_number . '_platform' => 'email',
			],
		];

		$repeater_defaults = [
			'1' => $shared_defaults,
			'2' => $shared_defaults,
			'3' => [
				'group_' . $group_number . '_icon' => [
					'value' => 'fas fa-map-marker-alt',
					'library' => 'fa-solid',
				],
				'group_' . $group_number . '_label' => esc_html__( 'Visit', 'hello-plus' ),
				'group_' . $group_number . '_platform' => 'telephone',
			],
			'4' => $shared_defaults,
		];

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
				'default' => $repeater_defaults[ $group_number ] ?? [],
			]
		);
	}

	protected function add_text_controls( $group_number, $group_condition ) {
		$this->add_control(
			'group_' . $group_number . '_text_subheading',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hours', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
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
				'default' => esc_html__( 'M-F 9am-6pm', 'hello-plus' ),
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

	protected function add_social_controls( $group_number, $group_condition ) {
		$this->add_control(
			'group_' . $group_number . '_social_subheading',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Follow', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => array_merge( $group_condition, [
					'group_' . $group_number . '_type' => 'social-icons',
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
				'title_field' => '{{{ group_' . $group_number . '_social_subheading }}}',
				'condition' => array_merge( $group_condition, [
					'group_' . $group_number . '_type' => 'social-icons',
				] ),
				'default' => [
					[
						'group_' . $group_number . '_social_subheading' => esc_html__( 'Instagram', 'hello-plus' ),
						'group_' . $group_number . '_social_icon' => [
							'value' => 'fab fa-instagram',
							'library' => 'fa-brands',
						],
						'group_' . $group_number . '_social_link' => [
							'url' => 'https://www.instagram.com/',
						],
					],
					[
						'group_' . $group_number . '_social_subheading' => esc_html__( 'Tiktok', 'hello-plus' ),
						'group_' . $group_number . '_social_icon' => [
							'value' => 'fab fa-tiktok',
							'library' => 'fa-brands',
						],
						'group_' . $group_number . '_social_link' => [
							'url' => 'https://www.tiktok.com/',
						],
					],
					[
						'group_' . $group_number . '_social_subheading' => esc_html__( 'X (Twitter)', 'hello-plus' ),
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

	protected function add_layout_style_section() {
		$this->start_controls_section(
			'layout_style_section',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_alignment_locate',
			[
				'label' => esc_html__( 'Content Alignment', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-start-v',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
						'icon' => 'eicon-align-center-v',
					],
					'end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-align-end-v',
					],
				],
				'default' => 'start',
				'tablet_default' => 'start',
				'mobile_default' => 'start',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-content-position: {{VALUE}};',
				],
				'condition' => [
					'layout_preset' => 'locate',
				],
			]
		);

		$this->add_responsive_control(
			'content_position',
			[
				'label' => esc_html__( 'Content Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-start-h',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
						'icon' => 'eicon-align-center-h',
					],
				],
				'default' => 'center',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-content-position: {{VALUE}};',
				],
				'condition' => [
					'layout_preset' => [
						'touchpoint',
						'quick-info',
					],
				],
			]
		);

		$this->add_responsive_control(
			'content_alignment_reduced',
			[
				'label' => esc_html__( 'Content Alignment', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-start-h',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
						'icon' => 'eicon-align-center-h',
					],
				],
				'default' => 'start',
				'tablet_default' => 'start',
				'mobile_default' => 'start',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-content-alignment: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'layout_preset',
							'operator' => '===',
							'value' => 'quick-info',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'layout_preset',
									'operator' => '===',
									'value' => 'touchpoint',
								],
								[
									'name' => 'content_position',
									'operator' => '===',
									'value' => 'center',
								],
							],
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'content_width',
			[
				'label' => esc_html__( 'Content Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 1200,
					],
					'%' => [
						'max' => 100,
					],
				],
				'frontend_available' => true,
				'default' => [
					'size' => 800,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-content-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_preset' => [
						'quick-info',
						'touchpoint',
					],
				],
			]
		);

		$this->add_control(
			'contact_details_heading',
			[
				'label' => esc_html__( 'Contact Details', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'layout_preset' => [ 'locate', 'touchpoint' ],
				],
			]
		);

		$this->add_responsive_control(
			'contact_details_columns',
			[
				'label' => esc_html__( 'Columns', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => esc_html__( '1', 'hello-plus' ),
					'2' => esc_html__( '2', 'hello-plus' ),
					'3' => esc_html__( '3', 'hello-plus' ),
					'4' => esc_html__( '4', 'hello-plus' ),
				],
				'default' => '1',
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-layout-columns: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'space_between_widgets',
			[
				'label' => esc_html__( 'Gaps', 'hello-plus' ),
				'type' => Controls_Manager::GAPS,
				'default' => [
					'row' => '20',
					'column' => '20',
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-layout-columns-row-gap: {{ROW}}{{UNIT}};--contact-layout-columns-column-gap: {{COLUMN}}{{UNIT}};',
				],
				'validators' => [
					'Number' => [
						'min' => 0,
					],
				],
			]
		);

		$this->add_responsive_control(
			'map_position_horizontal',
			[
				'label' => esc_html__( 'Map Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'toggle' => false,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-h-align-' . ( is_rtl() ? 'right' : 'left' ),
					],
					'end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-h-align-' . ( is_rtl() ? 'left' : 'right' ),
					],
				],
				'frontend_available' => true,
				'default' => 'end',
				'tablet_default' => 'end',
				'mobile_default' => 'end',
				'separator' => 'before',
				'condition' => [
					'layout_preset' => 'locate',
				],
			]
		);

		$this->add_responsive_control(
			'map_position_vertical',
			[
				'label' => esc_html__( 'Map Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-align-start-v',
					],
					'end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-align-end-v',
					],
				],
				'default' => 'end',
				'tablet_default' => 'end',
				'mobile_default' => 'end',
				'frontend_available' => true,
				'condition' => [
					'layout_preset' => 'touchpoint',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_text_style_section() {
		$this->start_controls_section(
			'text_style_section',
			[
				'label' => esc_html__( 'Text', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label' => esc_html__( 'Heading', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-text-heading-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .ehp-contact__heading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'description_label',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-text-description-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .ehp-contact__description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_responsive_control(
			'text_spacing',
			[
				'label' => esc_html__( 'Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 32,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-text-spacing: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function add_contact_details_style_section() {
		$this->start_controls_section(
			'contact_details_style_section',
			[
				'label' => esc_html__( 'Contact Details', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'contact_details_subheading_label',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'contact_details_subheading_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-group-subheading-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'contact_details_subheading_typography',
				'selector' => '{{WRAPPER}} .ehp-contact__group-subheading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_responsive_control(
			'contact_details_text_spacing',
			[
				'label' => esc_html__( 'Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-group-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'style_contact_details_heading',
			[
				'label' => esc_html__( 'Contact Details', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'contact_details_spacing',
			[
				'label' => esc_html__( 'Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 4,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-links-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'contact_details_typography',
				'selector' => '{{WRAPPER}} .ehp-contact__contact-link',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->start_controls_tabs( 'contact_details_tabs' );

		$this->start_controls_tab(
			'contact_details_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->add_control(
			'contact_details_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-link-icon-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'contact_details_text_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-link-label-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'contact_details_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
			]
		);

		$this->add_control(
			'contact_details_icon_hover_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-link-icon-hover-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'contact_details_text_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-link-label-hover-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'contact_details_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 16,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-link-icon-size: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'contact_details_icon_gap',
			[
				'label' => esc_html__( 'Icon Gap', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
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
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-link-icon-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'contact_details_text_heading',
			[
				'label' => esc_html__( 'Text', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'contact_details_text_typography',
				'selector' => '{{WRAPPER}} .ehp-contact__contact-text',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'contact_details_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-contact-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'contact_details_social_heading',
			[
				'label' => esc_html__( 'Social Icons', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'contact_details_social_tabs' );

		$this->start_controls_tab(
			'contact_details_social_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->add_control(
			'contact_details_social_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-social-icon-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'contact_details_social_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
			]
		);

		$this->add_control(
			'contact_details_social_icon_hover_color',
			[
				'label' => esc_html__( 'Icon Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-social-icon-hover-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'contact_details_social_icon_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'contact_details_social_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 16,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-social-icon-size: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'contact_details_social_icon_gap',
			[
				'label' => esc_html__( 'Icon Gap', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-social-icon-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_map_style_section() {
		$this->start_controls_section(
			'map_style_section',
			[
				'label' => esc_html__( 'Map', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'map_zoom',
			[
				'label' => esc_html__( 'Zoom', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'map_stretch',
			[
				'label' => esc_html__( 'Stretch', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_responsive_control(
			'map_width',
			[
				'label' => esc_html__( 'Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-map-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'map_height',
			[
				'label' => esc_html__( 'Height', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'default' => [
					'size' => 540,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 1440,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-map-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'show_map_border',
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
			'map_border_width',
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
					'{{WRAPPER}} .ehp-contact' => '--contact-map-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_map_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'map_border_color',
			[
				'label' => esc_html__( 'Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-map-border-color: {{VALUE}}',
				],
				'condition' => [
					'show_map_border' => 'yes',
				],
			]
		);

		$shapes = new Ehp_Shapes( $this, [
			'widget_name' => $this->get_name(),
			'container_prefix' => 'map',
		] );
		$shapes->add_style_controls();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'map_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-contact__map',
			]
		);

		$this->end_controls_section();
	}

	protected function add_box_style_section() {
		$this->start_controls_section(
			'box_style_section',
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
				'selector' => '{{WRAPPER}} .ehp-contact',
			]
		);

		$this->add_control(
			'box_background_overlay_label',
			[
				'label' => esc_html__( 'Background Overlay', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_overlay',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ehp-contact__overlay',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'background_overlay_opacity',
			[
				'label' => esc_html__( 'Opacity', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 0.5,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-overlay-opacity: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_element_spacing',
			[
				'label' => esc_html__( 'Element Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 150,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 32,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-elements-spacing: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'box_gap',
			[
				'label' => esc_html__( 'Gap', 'hello-plus' ),
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
					'size' => 60,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-box-gap: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
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
					'{{WRAPPER}} .ehp-contact' => '--contact-box-border-width: {{SIZE}}{{UNIT}};',
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
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-contact' => '--contact-box-border-color: {{VALUE}}',
				],
				'condition' => [
					'show_box_border' => 'yes',
				],
			]
		);

		$shapes = new Ehp_Shapes( $this, [
			'widget_name' => $this->get_name(),
			'container_prefix' => 'box',
		] );
		$shapes->add_style_controls();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-contact',
			]
		);

		$padding = new Ehp_Padding( $this, [
			'widget_name' => $this->get_name(),
			'container_prefix' => 'box',
		] );
		$padding->add_style_controls();

		$this->add_control(
			'box_full_screen_height',
			[
				'label' => esc_html__( 'Full Screen Height', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => '',
				'tablet_default' => '',
				'mobile_default' => '',
				'separator' => 'before',
			]
		);

		$configured_breakpoints = $this->get_configured_breakpoints();

		$this->add_control(
			'box_full_screen_height_controls',
			[
				'label' => esc_html__( 'Apply Full Screen Height on', 'hello-plus' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $configured_breakpoints['devices_options'],
				'default' => $configured_breakpoints['active_devices'],
				'condition' => [
					'box_full_screen_height' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}
}
