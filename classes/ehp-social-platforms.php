<?php

namespace HelloPlus\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	Widget_Base
};

class Ehp_Social_Platforms {
	private $context = [];
	private $defaults = [];
	private ?Widget_Base $widget = null;

	// initialized on render:
	private $widget_settings = [];

	public function set_context( array $context ) {
		$this->context = $context;
	}

	public function is_url_link( $platform ) {
		return 'url' === $platform || 'waze' === $platform || 'map' === $platform;
	}

	public function render_link_attributes( array $link, string $key ) {
		switch ( $link['platform'] ) {
			case 'waze':
				if ( empty( $link['location']['url'] ) ) {
					$link['location']['url'] = '#';
				}

				$this->widget->add_link_attributes( $key, $link['location'] );
				break;
			case 'url':
				if ( empty( $link['url']['url'] ) ) {
					$link['url']['url'] = '#';
				}

				$this->widget->add_link_attributes( $key, $link['url'] );
				break;
			case 'map':
				if ( empty( $link['map']['url'] ) ) {
					$link['map']['url'] = '#';
				}

				$this->widget->add_link_attributes( $key, $link['map'] );
				break;
			default:
				break;
		}
	}

	public function get_formatted_link( array $link, string $prefix ): string {

		// Ensure we clear the default link value if the matching type value is empty
		switch ( $link['platform'] ) {
			case 'email':
				$formatted_link = $this->build_email_link( $link['email_data'], $prefix );
				break;
			case 'sms':
				$formatted_link = ! empty( $link['number'] ) ? 'sms:' . $link['number'] : '';
				break;
			case 'messenger':
				$formatted_link = ! empty( $link['username'] ) ?
					$this->build_messenger_link( $link['username'] ) :
					'';
				break;
			case 'whatsapp':
				$formatted_link = ! empty( $link['number'] ) ? 'https://wa.me/' . $link['number'] : '';
				break;
			case 'viber':
				$formatted_link = $this->build_viber_link( $link['viber_action'], $link['number'] );
				break;
			case 'telephone':
				$formatted_link = ! empty( $link['number'] ) ? 'tel:' . $link['number'] : '';
				break;
			default:
				break;
		}

		return esc_html( $formatted_link );
	}

	public static function build_email_link( array $data, string $prefix ) {
		$email = $data[ $prefix . '_mail' ] ?? '';
		$subject = $data[ $prefix . '_mail_subject' ] ?? '';
		$body = $data[ $prefix . '_mail_body' ] ?? '';

		if ( ! $email ) {
			return '';
		}

		$link = 'mailto:' . $email;

		if ( $subject ) {
			$link .= '?subject=' . $subject;
		}

		if ( $body ) {
			$link .= $subject ? '&' : '?';
			$link .= 'body=' . $body;
		}

		return $link;
	}

	public static function build_viber_link( string $action, string $number ) {
		if ( empty( $number ) ) {
			return '';
		}

		return add_query_arg( [
			'number' => rawurlencode( $number ),
		], 'viber://' . $action );
	}

	public static function build_messenger_link( string $username ) {
		return 'https://m.me/' . $username;
	}

	public function add_repeater_controls() {
		$prefix_attr = $this->context['prefix_attr'];
		$repeater = $this->context['repeater'];
		$show_icon = $this->context['show_icon'] ?? true;

		if ( $show_icon ) {
			$repeater->add_control(
				$prefix_attr . '_icon',
				[
					'label' => esc_html__( 'Icon', 'hello-plus' ),
					'type' => Controls_Manager::ICONS,
					'default' => $this->defaults['icon_default'] ?? [
						'value' => 'fas fa-phone-alt',
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
							'facebook-messenger',
							'viber',
							'waze',
						],
					],
				]
			);
		}

		$repeater->add_control(
			$prefix_attr . '_label',
			[
				'label' => esc_html__( 'Label', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => $this->defaults['label_default'] ?? esc_html__( 'Visit', 'hello-plus' ),
				'placeholder' => esc_html__( 'Type your text here', 'hello-plus' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			$prefix_attr . '_platform',
			[
				'label' => esc_html__( 'Platform', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'email' => esc_html__( 'Email', 'hello-plus' ),
					'telephone' => esc_html__( 'Telephone', 'hello-plus' ),
					'sms' => esc_html__( 'SMS', 'hello-plus' ),
					'whatsapp' => esc_html__( 'Whatsapp', 'hello-plus' ),
					'messenger' => esc_html__( 'Messenger', 'hello-plus' ),
					'viber' => esc_html__( 'Viber', 'hello-plus' ),
					'map' => esc_html__( 'Map', 'hello-plus' ),
					'waze' => esc_html__( 'Waze', 'hello-plus' ),
					'url' => esc_html__( 'URL', 'hello-plus' ),
				],
				'default' => $this->defaults['platform_default'] ?? 'telephone',
			],
		);

		$repeater->add_control(
			$prefix_attr . '_mail',
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
					$prefix_attr . '_platform' => 'email',
				],
			],
		);

		$repeater->add_control(
			$prefix_attr . '_mail_subject',
			[
				'label' => esc_html__( 'Subject', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => '',
				'condition' => [
					$prefix_attr . '_platform' => 'email',
				],
			],
		);

		$repeater->add_control(
			$prefix_attr . '_mail_body',
			[
				'label' => esc_html__( 'Message', 'hello-plus' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'condition' => [
					$prefix_attr . '_platform' => 'email',
				],
			]
		);

		$repeater->add_control(
			$prefix_attr . '_number',
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
					$prefix_attr . '_platform' => [
						'sms',
						'whatsapp',
						'viber',
						'telephone',
					],
				],
			],
		);

		$repeater->add_control(
			$prefix_attr . '_username',
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
					$prefix_attr . '_platform' => [
						'messenger',
					],
				],
			],
		);

		$repeater->add_control(
			$prefix_attr . '_url',
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
					$prefix_attr . '_platform' => [
						'url',
					],
				],
				'placeholder' => esc_html__( 'https://', 'hello-plus' ),
			],
		);

		$repeater->add_control(
			$prefix_attr . '_waze',
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
					$prefix_attr . '_platform' => [
						'waze',
					],
				],
				'placeholder' => esc_html__( 'https://ul.waze.com/ul?place=', 'hello-plus' ),
			],
		);

		$repeater->add_control(
			$prefix_attr . '_map',
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
					$prefix_attr . '_platform' => [
						'map',
					],
				],
				'placeholder' => esc_html__( 'https://maps.app.goo.gl', 'hello-plus' ),
			],
		);

		$repeater->add_control(
			$prefix_attr . '_action',
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
					$prefix_attr . '_platform' => [
						'viber',
					],
				],
			]
		);
	}

	public function __construct( Widget_Base $widget, $context = [], $defaults = [] ) {
		$this->widget = $widget;
		$this->context = $context;
		$this->defaults = $defaults;
	}
}
