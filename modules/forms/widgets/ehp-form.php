<?php
namespace HelloPlus\Modules\Forms\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\{
	Global_Colors,
	Global_Typography,
};

use Elementor\{
	Group_Control_Background,
	Group_Control_Box_Shadow,
	Group_Control_Typography,
};

use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Modules\Promotions\Controls\Promotion_Control;
use Elementor\Repeater;
use HelloPlus\Includes\Utils;

use HelloPlus\Modules\Forms\{
	Classes\Form_Base,
	Classes\Render\Widget_Form_Render,
	Components\Ajax_Handler,
	Controls\Fields_Repeater,
	Module,
};

use HelloPlus\Modules\Content\Classes\{
	Choose_Img_Control,
};

use HelloPlus\Classes\{
	Ehp_Full_Height,
	Ehp_Image,
	Ehp_Shapes,
	Ehp_Column_Structure,
};

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Ehp_Form extends Form_Base {

	public function get_name() {
		return 'ehp-form';
	}

	public function get_title() {
		return esc_html__( 'Form Lite', 'hello-plus' );
	}

	public function get_icon() {
		return 'eicon-ehp-forms';
	}

	public function get_keywords() {
		return [ 'form', 'forms', 'field', 'button' ];
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	public function get_custom_help_url(): string {
		return 'https://go.elementor.com/form-lite-help';
	}

	protected function get_upsale_data(): array {
		return [
			'condition' => ! Utils::has_pro(),
			'image' => esc_url( HELLOPLUS_IMAGES_URL . 'go-pro.svg' ),
			'image_alt' => esc_attr__( 'Upgrade Now', 'hello-plus' ),
			'title' => esc_html__( 'Take your forms further', 'hello-plus' ),
			'description' => esc_html__( 'Unlock advanced form customization and field options with Elementor Pro.', 'hello-plus' ),
			'upgrade_url' => esc_url( 'https://go.elementor.com/form-lite-widget-elementor-plugin-pricing/' ),
			'upgrade_text' => esc_html__( 'Upgrade Now', 'hello-plus' ),
		];
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 3.24.0
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends(): array {
		return [ 'helloplus-forms' ];
	}

	public function get_script_depends(): array {
		return [ 'helloplus-forms-fe' ];
	}

	protected function render(): void {
		$render_strategy = new Widget_Form_Render( $this );

		$this->add_inline_editing_attributes( 'text_heading', 'none' );
		$this->add_inline_editing_attributes( 'text_description', 'none' );

		$render_strategy->render();
	}

	protected function register_controls() {
		$this->add_content_layout_section();
		$this->add_content_text_section();
		$this->add_content_image_section();
		$this->add_content_form_fields_section();
		$this->add_content_button_section();
		$this->add_content_actions_after_submit_section();
		$this->add_action_sections();
		$this->add_content_additional_options_section();

		$this->add_style_layout_section();
		$this->add_style_text_section();
		$this->add_style_image_section();
		$this->add_style_form_section();
		$this->add_style_fields_section();
		$this->add_style_buttons_section();
		$this->add_style_messages_section();
		$this->add_style_box_section();
	}

	protected function add_action_sections() {
		$actions = Module::instance()->actions_registrar->get();

		foreach ( $actions as $action ) {
			$action->register_settings_section( $this );
		}
	}

	protected function add_content_layout_section(): void {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
			]
		);

		$this->add_control(
			'layout_preset',
			[
				'label' => esc_html__( 'Preset', 'hello-plus' ),
				'type' => Choose_Img_Control::CONTROL_NAME,
				'default' => 'quick-connect',
				'label_block' => true,
				'columns' => 2,
				'toggle' => false,
				'options' => [
					'quick-connect' => [
						'title' => wp_kses_post( "Quick Connect:\nEncourage fast, direct\ncontact." ),
						'image' => HELLOPLUS_IMAGES_URL . 'form-lite-quick-connect.svg',
						'hover_image' => true,
					],
					'interact' => [
						'title' => wp_kses_post( "Interact: Focus on\nyour messaging to\nstart a conversation." ),
						'image' => HELLOPLUS_IMAGES_URL . 'form-lite-interact.svg',
						'hover_image' => true,
					],
					'engage' => [
						'title' => wp_kses_post( "Engage: Capture\nattention to drive\ninteraction." ),
						'image' => HELLOPLUS_IMAGES_URL . 'form-lite-engage.svg',
						'hover_image' => true,
					],
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_text_section(): void {
		$this->start_controls_section(
			'section_text',
			[
				'label' => esc_html__( 'Text', 'hello-plus' ),
			]
		);

		$this->add_control(
			'text_heading',
			[
				'label' => esc_html__( 'Heading', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Contact Us', 'hello-plus' ),
				'placeholder' => esc_html__( 'Add your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'text_heading_tag',
			[
				'label' => esc_html__( 'Heading HTML Tag', 'hello-plus' ),
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
			'text_description',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Fill out the form below and we will contact you as soon as possible', 'hello-plus' ),
				'placeholder' => esc_html__( 'Add your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_image_section(): void {
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'hello-plus' ),
				'condition' => [
					'layout_preset' => 'engage',
				],
			]
		);

		$image = new Ehp_Image( $this, [ 'widget_name' => 'form' ] );
		$image->add_content_section();

		$this->end_controls_section();
	}

	protected function add_content_form_fields_section(): void {
		$repeater = new Repeater();

		$field_types = [
			'text' => esc_html__( 'Text', 'hello-plus' ),
			'email' => esc_html__( 'Email', 'hello-plus' ),
			'textarea' => esc_html__( 'Textarea', 'hello-plus' ),
			'ehp-tel' => esc_html__( 'Tel', 'hello-plus' ),
			'select' => esc_html__( 'Select', 'hello-plus' ),
			'ehp-acceptance' => esc_html__( 'Acceptance', 'hello-plus' ),
		];

		$repeater->start_controls_tabs( 'form_fields_tabs' );

		$repeater->start_controls_tab( 'form_fields_content_tab', [
			'label' => esc_html__( 'Content', 'hello-plus' ),
		] );

		$repeater->add_control(
			'field_type',
			[
				'label' => esc_html__( 'Type', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			]
		);

		$repeater->add_control(
			'field_label',
			[
				'label' => esc_html__( 'Label', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'ehp-tel',
								'text',
								'email',
								'textarea',
							],
						],
					],
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'required',
			[
				'label' => esc_html__( 'Required', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => '',
			]
		);

		$repeater->add_control(
			'field_options',
			[
				'label' => esc_html__( 'Options', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'description' => esc_html__( 'Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Name|f_name', 'hello-plus' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'select',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'allow_multiple',
			[
				'label' => esc_html__( 'Multiple Selection', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'select_size',
			[
				'label' => esc_html__( 'Rows', 'hello-plus' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'step' => 1,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
						[
							'name' => 'allow_multiple',
							'value' => 'true',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Column Width', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'100' => '100%',
					'50' => '50%',
					'33' => '33%',
				],
				'default' => '100',
				'tablet_default' => '100',
				'mobile_default' => '100',
			]
		);

		$repeater->add_control(
			'rows',
			[
				'label' => esc_html__( 'Rows', 'hello-plus' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'textarea',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'css_classes',
			[
				'label' => esc_html__( 'CSS Classes', 'hello-plus' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'title' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'hello-plus' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'form_fields_advanced_tab',
			[
				'label' => esc_html__( 'Advanced', 'hello-plus' ),
				'condition' => [
					'field_type!' => 'html',
				],
			]
		);

		$repeater->add_control(
			'field_value',
			[
				'label' => esc_html__( 'Default Value', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'text',
								'email',
								'textarea',
								'tel',
								'select',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_id',
			[
				'label' => esc_html__( 'ID', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'description' => sprintf(
					/* translators: %1$s: Opening code tag, %2$s: Closing code tag. */
					esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'hello-plus' ),
					'<code>',
					'</code>'
				),
				'render_type' => 'none',
				'required' => true,
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$shortcode_template = '{{ view.container.settings.get( \'custom_id\' ) }}';

		$repeater->add_control(
			'shortcode',
			[
				'label' => esc_html__( 'Shortcode', 'hello-plus' ),
				'type' => Controls_Manager::RAW_HTML,
				'classes' => 'forms-field-shortcode',
				'raw' => '<input class="elementor-form-field-shortcode" value=\'[field id="' . $shortcode_template . '"]\' readonly />',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => esc_html__( 'Form Fields', 'hello-plus' ),
			]
		);

		$this->add_control(
			'form_name',
			[
				'label' => esc_html__( 'Form Name', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'New Form', 'hello-plus' ),
				'placeholder' => esc_html__( 'Form Name', 'hello-plus' ),
			]
		);

		$this->add_control(
			'form_fields',
			[
				'type' => Fields_Repeater::CONTROL_TYPE,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'custom_id' => 'name',
						'field_type' => 'text',
						'field_label' => esc_html__( 'Name', 'hello-plus' ),
						'placeholder' => esc_html__( 'Name', 'hello-plus' ),
						'width' => '100',
						'dynamic' => [
							'active' => true,
						],
					],
					[
						'custom_id' => 'email',
						'field_type' => 'email',
						'required' => 'true',
						'field_label' => esc_html__( 'Email', 'hello-plus' ),
						'placeholder' => esc_html__( 'Email', 'hello-plus' ),
						'width' => '100',
					],
					[
						'custom_id' => 'message',
						'field_type' => 'textarea',
						'field_label' => esc_html__( 'Message', 'hello-plus' ),
						'placeholder' => esc_html__( 'Message', 'hello-plus' ),
						'width' => '100',
					],
				],
				'title_field' => '{{{ field_label }}}',
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => esc_html__( 'Label', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'hello-plus' ),
				'label_off' => esc_html__( 'Hide', 'hello-plus' ),
				'return_value' => 'true',
				'default' => 'true',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mark_required',
			[
				'label' => esc_html__( 'Required Mark', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'hello-plus' ),
				'label_off' => esc_html__( 'Hide', 'hello-plus' ),
				'default' => '',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_button_section(): void {
		$this->start_controls_section(
			'section_buttons',
			[
				'label' => esc_html__( 'Button', 'hello-plus' ),
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => esc_html__( 'Column Width', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'100' => '100%',
					'50' => '50%',
					'33' => '33%',
				],
				'default' => '100',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Submit', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Send', 'hello-plus' ),
				'placeholder' => esc_html__( 'Send', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'selected_button_icon',
			[
				'label' => esc_html__( 'Icon', 'hello-plus' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label' => esc_html__( 'Button ID', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'ai' => [
					'active' => false,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'hello-plus' ),
				'description' => sprintf(
					/* translators: %1$s: Opening code tag, %2$s: Closing code tag. */
					esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'hello-plus' ),
					'<code>',
					'</code>'
				),
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_actions_after_submit_section(): void {
		$this->start_controls_section(
			'section_integration',
			[
				'label' => esc_html__( 'Actions After Submit', 'hello-plus' ),
			]
		);

		$this->add_control(
			'should_redirect',
			[
				'label' => esc_html__( 'Redirect To Thank You Page', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'true',
				'default' => '',
			]
		);

		$this->add_control(
			'redirect_to',
			[
				'label' => esc_html__( 'Redirect To', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'https://your-link.com', 'hello-plus' ),
				'ai' => [
					'active' => false,
				],
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::TEXT_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'label_block' => true,
				'render_type' => 'none',
				'classes' => 'elementor-control-direction-ltr',
				'condition' => [
					'should_redirect' => 'true',
				],
			]
		);

		$this->add_control(
			'email_heading',
			[
				'label' => esc_html__( 'Email Submissions', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'email_to',
			[
				'label' => esc_html__( 'To', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => get_option( 'admin_email' ),
				'ai' => [
					'active' => false,
				],
				'placeholder' => get_option( 'admin_email' ),
				'label_block' => true,
				'title' => esc_html__( 'Separate emails with commas', 'hello-plus' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		/* translators: %s: Site title. */
		$default_message = sprintf( esc_html__( 'New message from [%s]', 'hello-plus' ), get_bloginfo( 'name' ) );

		$this->add_control(
			'email_subject',
			[
				'label' => esc_html__( 'Subject', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_message,
				'ai' => [
					'active' => false,
				],
				'placeholder' => $default_message,
				'label_block' => true,
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'email_content',
			[
				'label' => esc_html__( 'Message', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '[all-fields]',
				'ai' => [
					'active' => false,
				],
				'placeholder' => '[all-fields]',
				'description' => sprintf(
				/* translators: %s: The [all-fields] shortcode. */
					esc_html__( 'By default, all form fields are sent via %s shortcode. To customize sent fields, copy the shortcode that appears inside each field and paste it above.', 'hello-plus' ),
					'<code>[all-fields]</code>'
				),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$site_domain = Module::get_site_domain();

		$this->add_control(
			'email_from',
			[
				'label' => esc_html__( 'From Email', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'email@' . $site_domain,
				'ai' => [
					'active' => false,
				],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'email_from_name',
			[
				'label' => esc_html__( 'From Name', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => get_bloginfo( 'name' ),
				'ai' => [
					'active' => false,
				],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'email_reply_to',
			[
				'label' => esc_html__( 'Reply-To', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => '',
				],
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'email_to_cc',
			[
				'label' => esc_html__( 'Cc', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'ai' => [
					'active' => false,
				],
				'title' => esc_html__( 'Separate emails with commas', 'hello-plus' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'email_to_bcc',
			[
				'label' => esc_html__( 'Bcc', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'ai' => [
					'active' => false,
				],
				'title' => esc_html__( 'Separate emails with commas', 'hello-plus' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'form_metadata',
			[
				'label' => esc_html__( 'Meta Data', 'hello-plus' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'separator' => 'before',
				'default' => [
					'date',
					'time',
					'page_url',
					'user_agent',
					'remote_ip',
					'credit',
				],
				'options' => [
					'date' => esc_html__( 'Date', 'hello-plus' ),
					'time' => esc_html__( 'Time', 'hello-plus' ),
					'page_url' => esc_html__( 'Page URL', 'hello-plus' ),
					'user_agent' => esc_html__( 'User Agent', 'hello-plus' ),
					'remote_ip' => esc_html__( 'Remote IP', 'hello-plus' ),
					'credit' => esc_html__( 'Credit', 'hello-plus' ),
				],
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'email_content_type',
			[
				'label' => esc_html__( 'Send As', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'html',
				'render_type' => 'none',
				'options' => [
					'html' => esc_html__( 'HTML', 'hello-plus' ),
					'plain' => esc_html__( 'Plain', 'hello-plus' ),
				],
			]
		);

		$this->add_control(
			'submission_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		if ( ! Utils::are_submissions_enabled() ) {
			$this->add_control(
				'collect_submit_promotion',
				[
					'label' => esc_html__( 'Collect Submissions', 'hello-plus' ),
					'type' => Promotion_Control::TYPE,
				]
			);
		} else {
			$this->add_control(
				'submit_actions',
				[
					'label' => esc_html__( 'Save Submissions', 'hello-plus' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'hello-plus' ),
					'label_off' => esc_html__( 'No', 'hello-plus' ),
					'return_value' => 'save-to-database',
					'default' => 'save-to-database',
				]
			);
		}

		$this->end_controls_section();
	}

	protected function add_style_layout_section(): void {
		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => esc_html__( 'Layout', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$ehp_column_structure = new Ehp_Column_Structure( $this, [
			'condition' => [
				'layout_preset' => [
					'interact',
					'engage',
				],
			],
		] );

		$ehp_column_structure->add_style_controls();

		$this->add_responsive_control(
			'form_position',
			[
				'label' => esc_html__( 'Form Position', 'hello-plus' ),
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
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-order: var(--ehp-form-order-{{VALUE}}); --ehp-text-order: var(--ehp-text-order-{{VALUE}});',
				],
				'default' => 'end',
				'condition' => [
					'layout_preset' => [
						'interact',
						'engage',
					],
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_alignment',
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
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-content-alignment: {{VALUE}};',
				],
				'condition' => [
					'layout_preset' => [
						'interact',
						'engage',
					],
				],
			]
		);

		$this->add_responsive_control(
			'content_position',
			[
				'label' => esc_html__( 'Content Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'toggle' => false,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-h-align-' . ( is_rtl() ? 'right' : 'left' ),
					],
					'center' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
						'icon' => 'eicon-h-align-center',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-content-position: {{VALUE}};',
				],
				'default' => 'center',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'condition' => [
					'layout_preset' => 'quick-connect',
				],
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => esc_html__( 'Content Alignment', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'hello-plus' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'hello-plus' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'layout_preset',
							'operator' => '==',
							'value' => 'quick-connect',
						],
						[
							'name' => 'content_position',
							'operator' => '==',
							'value' => 'center',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-text-container-align: {{VALUE}};',
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
						'max' => 1600,
					],
					'%' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 640,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 640,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 320,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-content-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout_preset' => 'quick-connect',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_content_additional_options_section(): void {
		$this->start_controls_section(
			'section_form_options',
			[
				'label' => esc_html__( 'Additional Options', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'form_id',
			[
				'label' => esc_html__( 'Form ID', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'ai' => [
					'active' => false,
				],
				'placeholder' => 'new_form_id',
				'description' => sprintf(
					/* translators: %1$s: Opening code tag, %2$s: Closing code tag. */
					esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'hello-plus' ),
					'<code>',
					'</code>'
				),
				'separator' => 'after',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'custom_messages',
			[
				'label' => esc_html__( 'Custom Messages', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'separator' => 'before',
				'render_type' => 'none',
			]
		);

		$default_messages = Ajax_Handler::get_default_messages();

		$this->add_control(
			'success_message',
			[
				'label' => esc_html__( 'Success Message', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::SUCCESS ],
				'placeholder' => $default_messages[ Ajax_Handler::SUCCESS ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'error_message',
			[
				'label' => esc_html__( 'Form Error', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::ERROR ],
				'placeholder' => $default_messages[ Ajax_Handler::ERROR ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'server_message',
			[
				'label' => esc_html__( 'Server Error', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::SERVER_ERROR ],
				'placeholder' => $default_messages[ Ajax_Handler::SERVER_ERROR ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'invalid_message',
			[
				'label' => esc_html__( 'Invalid Form', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::INVALID_FORM ],
				'placeholder' => $default_messages[ Ajax_Handler::INVALID_FORM ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_text_section(): void {
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Text', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_text',
			[
				'label' => esc_html__( 'Heading', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-heading-color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .ehp-form__heading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label' => esc_html__( 'Description', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-description-color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .ehp-form__description',
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
					'{{WRAPPER}} .ehp-form' => '--ehp-form-text-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_image_section() {
		$this->start_controls_section(
			'style_image',
			[
				'label' => esc_html__( 'Image', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_preset' => 'engage',
				],
			]
		);

		$image = new Ehp_Image( $this, [ 'widget_name' => 'form' ] );
		$image->add_style_controls();

		$this->end_controls_section();
	}

	protected function add_style_form_section(): void {
		$this->start_controls_section(
			'section_form_style',
			[
				'label' => esc_html__( 'Form', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label' => esc_html__( 'Columns Gap', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 32,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'max' => 60,
					],
					'em' => [
						'max' => 6,
					],
					'rem' => [
						'max' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 32,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'max' => 60,
					],
					'em' => [
						'max' => 6,
					],
					'rem' => [
						'max' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label' => esc_html__( 'Label', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => esc_html__( 'Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'max' => 60,
					],
					'em' => [
						'max' => 6,
					],
					'rem' => [
						'max' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-label-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-label-color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_control(
			'mark_required_color',
			[
				'label' => esc_html__( 'Mark Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF0000',
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-mark-color: {{VALUE}};',
				],
				'condition' => [
					'mark_required' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .ehp-form__field-label',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_fields_section(): void {
		$this->start_controls_section(
			'section_field_style',
			[
				'label' => esc_html__( 'Fields', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => esc_html__( 'Input Size', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => esc_html__( 'Extra Small', 'hello-plus' ),
					'sm' => esc_html__( 'Small', 'hello-plus' ),
					'md' => esc_html__( 'Medium', 'hello-plus' ),
					'lg' => esc_html__( 'Large', 'hello-plus' ),
					'xl' => esc_html__( 'Extra Large', 'hello-plus' ),
				],
				'default' => 'sm',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-field-text-color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selector' => '{{WRAPPER}} .ehp-form__field, {{WRAPPER}} .ehp-form__field::placeholder',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => esc_html__( 'Background Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-field-bg-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_switcher',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'options' => [
					'yes' => esc_html__( 'Yes', 'hello-plus' ),
					'no' => esc_html__( 'No', 'hello-plus' ),
				],
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_width',
			[
				'label' => esc_html__( 'Border Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-field-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'field_border_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label' => esc_html__( 'Border Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-field-border-color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'separator' => 'before',
				'condition' => [
					'field_border_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'fields_shape',
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
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_buttons_section(): void {
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_type',
			[
				'label' => esc_html__( 'Type', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'button' => esc_html__( 'Button', 'hello-plus' ),
					'link' => esc_html__( 'Link', 'hello-plus' ),
				],
				'default' => 'button',
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => esc_html__( 'Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'hello-plus' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'center',
				'condition' => [
					'button_width!' => '100',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .ehp-form__button',
			]
		);

		$start = is_rtl() ? 'right' : 'left';
		$end = is_rtl() ? 'left' : 'right';

		$this->add_control(
			'button_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => is_rtl() ? 'row-reverse' : 'row',
				'options' => [
					'row' => [
						'title' => esc_html__( 'Start', 'hello-plus' ),
						'icon' => "eicon-h-align-{$start}",
					],
					'row-reverse' => [
						'title' => esc_html__( 'End', 'hello-plus' ),
						'icon' => "eicon-h-align-{$end}",
					],
				],
				'selectors_dictionary' => [
					'left' => is_rtl() ? 'row-reverse' : 'row',
					'right' => is_rtl() ? 'row' : 'row-reverse',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-button-icon-position: {{VALUE}};',
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'default' => [
					'size' => 8,
					'unit' => 'px',
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-button-icon-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'hello-plus' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-button-text-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .is-type-button.ehp-form__button',
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
					'button_type' => 'button',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'hello-plus' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-button-text-color-hover: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .is-type-button.ehp-form__button:hover, {{WRAPPER}} .is-type-button.ehp-form__button:focus',
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
					'button_type' => 'button',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'hello-plus' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_switcher',
			[
				'label' => esc_html__( 'Border', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'options' => [
					'yes' => esc_html__( 'Yes', 'hello-plus' ),
					'no' => esc_html__( 'No', 'hello-plus' ),
				],
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'hello-plus' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-button-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'button_border_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-button-border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_switcher' => 'yes',
				],
			]
		);

		$shapes = new Ehp_Shapes( $this, [
			'widget_name' => 'form',
			'container_prefix' => 'button',
		] );
		$shapes->add_style_controls();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'map_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-form__button',
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => '8',
					'right' => '40',
					'bottom' => '8',
					'left' => '40',
					'unit' => 'px',
				],
				'mobile_default' => [
					'top' => '8',
					'right' => '40',
					'bottom' => '8',
					'left' => '40',
					'unit' => 'px',
				],
				'tablet_default' => [
					'top' => '8',
					'right' => '40',
					'bottom' => '8',
					'left' => '40',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-button-padding-block-end: {{BOTTOM}}{{UNIT}}; --ehp-form-button-padding-block-start: {{TOP}}{{UNIT}}; --ehp-form-button-padding-inline-end: {{RIGHT}}{{UNIT}}; --ehp-form-button-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_messages_section(): void {
		$this->start_controls_section(
			'section_messages_style',
			[
				'label' => esc_html__( 'Messages', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'message_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .elementor-message',
			]
		);

		$this->add_control(
			'success_message_color',
			[
				'label' => esc_html__( 'Success Message Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-message-success' => 'color: {{COLOR}};',
				],
			]
		);

		$this->add_control(
			'error_message_color',
			[
				'label' => esc_html__( 'Error Message Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-message-danger' => 'color: {{COLOR}};',
				],
			]
		);

		$this->add_control(
			'inline_message_color',
			[
				'label' => esc_html__( 'Inline Message Color', 'hello-plus' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-help-inline' => 'color: {{COLOR}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function add_style_box_section(): void {
		$this->start_controls_section(
			'section_box_style',
			[
				'label' => esc_html__( 'Box', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'box_heading',
			[
				'label' => esc_html__( 'Background', 'hello-plus' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ehp-form',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
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
				'selector' => '{{WRAPPER}} .ehp-form__overlay',
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
					'{{WRAPPER}} .ehp-form' => '--ehp-form-overlay-opacity: {{SIZE}};',
				],
				'condition' => [
					'background_overlay_background!' => '',
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
					'{{WRAPPER}} .ehp-form' => '--ehp-form-elements-spacing: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'layout_preset' => [
						'quick-connect',
						'engage',
					],
				],
			]
		);

		$this->add_responsive_control(
			'box_elements_gap',
			[
				'label' => esc_html__( 'Gap', 'hello-plus' ),
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
					'size' => 60,
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
					'{{WRAPPER}} .ehp-form' => '--ehp-form-elements-gap: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'layout_preset' => [
						'interact',
						'engage',
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
					'{{WRAPPER}} .ehp-form' => '--ehp-form-box-border-width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .ehp-form' => '--ehp-form-box-border-color: {{VALUE}}',
				],
				'condition' => [
					'show_box_border' => 'yes',
				],
			]
		);

		$shapes = new Ehp_Shapes( $this, [
			'widget_name' => 'form',
			'container_prefix' => 'box',
		] );
		$shapes->add_style_controls();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_box_shadow',
				'selector' => '{{WRAPPER}} .ehp-form',
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__( 'Padding', 'hello-plus' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .ehp-form' => '--ehp-form-box-padding-block-end: {{BOTTOM}}{{UNIT}}; --ehp-form-box-padding-block-start: {{TOP}}{{UNIT}}; --ehp-form-box-padding-inline-end: {{RIGHT}}{{UNIT}}; --ehp-form-box-padding-inline-start: {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '60',
					'right' => '60',
					'bottom' => '60',
					'left' => '60',
					'unit' => 'px',
				],
				'separator' => 'before',
			]
		);

		$ehp_full_height = new Ehp_Full_Height( $this );
		$ehp_full_height->add_style_controls();

		$this->end_controls_section();
	}
}
