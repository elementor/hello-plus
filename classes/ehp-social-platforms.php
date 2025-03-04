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
			case 'skype':
				$formatted_link = ! empty( $link['username'] ) ? 'skype:' . $link['username'] . '?chat' : '';
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

	public function __construct( Widget_Base $widget, $context = [], $defaults = [] ) {
		$this->widget = $widget;
		$this->context = $context;
		$this->defaults = $defaults;
	}
}
