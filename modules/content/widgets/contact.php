<?php

namespace HelloPlus\Modules\Content\Widgets;

use HelloPlus\Modules\Content\Classes\Choose_Img_Control;
use HelloPlus\Modules\Content\Classes\Render\Widget_Contact_Render;
use HelloPlus\Modules\Theme\Module as Theme_Module;
use HelloPlus\Includes\Utils;

use Elementor\{
	Controls_Manager,
	Repeater
};
use Elementor\Widget_Base;

class Contact extends Widget_Base {
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
		return 'eicon-ehp-cta';
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
		// $this->add_map_content_section();
	}

	protected function add_style_section() {
		// $this->add_layout_style_section();
		// $this->add_text_style_section();
		// $this->add_contact_details_style_section();
		// $this->add_map_style_section();
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
				'default' => 'focus',
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
						'image' => HELLOPLUS_IMAGES_URL . 'contact-touchpoint.svg',
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

		$this->end_controls_section();
	}

	protected function add_group_controls( $group_number ) {
		$this->add_control(
			'group_' . $group_number . '_heading',
			[
				'label' => esc_html__( 'Group ' . $group_number, 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

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
				'default' => 'contact-links',
			]
		);

		$this->add_contact_links_controls( $group_number );

		$this->add_text_controls( $group_number );

		$this->add_social_controls( $group_number );
	}

	protected function add_contact_links_controls( $group_number ) {
		$this->add_control(
			'group_' . $group_number . '_links_subheading',
			[
				'label' => esc_html__( 'Subheading', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Let\'s talk', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'group_' . $group_number . '_type' => 'contact-links',
				],
			]
		);

		$this->add_control(
			'group_' . $group_number . '_links_heading',
			[
				'label' => esc_html__( 'Links', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'group_' . $group_number . '_type' => 'contact-links',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'group_' . $group_number . '_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
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
				'default' => 'map',
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

		$this->add_control(
			'group_' . $group_number . '_repeater',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'prevent_empty' => true,
				'button_text' => esc_html__( 'Add Item', 'hello-plus' ),
				'title_field' => '{{{ group_' . $group_number . '_label }}}',
				'condition' => [
					'group_' . $group_number . '_type' => 'contact-links',
				],
				'default' => [
					[
						'contact_buttons_label' => esc_html__( 'Call', 'hello-plus' ),
						'selected_icon' => [
							'value' => 'fas fa-map-marker-alt',
							'library' => 'fa-solid',
						],
						'contact_buttons_platform' => 'telephone',
					],
				],
			]
		);
	}

	protected function add_text_controls( $group_number ) {
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
				'condition' => [
					'group_' . $group_number . '_type' => 'text',
				],
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
				'condition' => [
					'group_' . $group_number . '_type' => 'text',
				],
			]
		);
	}

	protected function add_social_controls( $group_number ) {
		$repeater = new Repeater();

		$repeater->add_control(
			'group_' . $group_number . '_social_subheading',
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
				'placeholder' => esc_html__( 'https://www.instagram.com', 'hello-plus' ),
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
				'condition' => [
					'group_' . $group_number . '_type' => 'social-icons',
				],
				'default' => [
					[
						'group_' . $group_number . '_social_subheading' => esc_html__( 'Instagram', 'hello-plus' ),
						'group_' . $group_number . '_social_icon' => [
							'value' => 'fab fa-instagram',
							'library' => 'fa-brands',
						],
					],
					[
						'group_' . $group_number . '_social_subheading' => esc_html__( 'Tiktok', 'hello-plus' ),
						'group_' . $group_number . '_social_icon' => [
							'value' => 'fab fa-tiktok',
							'library' => 'fa-brands',
						],
					],
					[
						'group_' . $group_number . '_social_subheading' => esc_html__( 'X (Twitter)', 'hello-plus' ),
						'group_' . $group_number . '_social_icon' => [
							'value' => 'fab fa-x-twitter',
							'library' => 'fa-brands',
						],
					],
				],
			]
		);
	}
}