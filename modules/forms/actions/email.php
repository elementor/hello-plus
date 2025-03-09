<?php
namespace HelloPlus\Modules\Forms\Actions;

use Elementor\Controls_Manager;
use HelloPlus\Modules\Forms\Classes\Action_Base;
use HelloPlus\Modules\Forms\Classes\Form_Record;
use HelloPlus\Modules\Forms\Components\Ajax_Handler;
use HelloPlus\Modules\Forms\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Email extends Action_Base {

	public function get_name(): string {
		return 'ehp-email';
	}

	public function get_label(): string {
		return esc_html__( 'Email', 'hello-plus' );
	}

	public function register_settings_section( $widget ) {
	}

	public function on_export( $element ) {
		$controls_to_unset = [
			'email_to',
			'email_from',
			'email_from_name',
			'email_subject',
			'email_reply_to',
			'email_to_cc',
			'email_to_bcc',
		];

		foreach ( $controls_to_unset as $base_id ) {
			$control_id = $this->get_control_id( $base_id );
			unset( $element['settings'][ $control_id ] );
		}

		return $element;
	}

	public function run( $record, $ajax_handler ) {
		$settings = $record->get( 'form_settings' );
		$send_html = 'plain' !== $settings[ $this->get_control_id( 'email_content_type' ) ];
		$line_break = $send_html ? '<br>' : "\n";

		$fields = [
			'email_to' => get_option( 'admin_email' ),
			/* translators: %s: Site title. */
			'email_subject' => sprintf( esc_html__( 'New message from [%s]', 'hello-plus' ), get_bloginfo( 'name' ) ),
			'email_content' => '[all-fields]',
			'email_from_name' => get_bloginfo( 'name' ),
			'email_from' => get_bloginfo( 'admin_email' ),
			'email_reply_to' => 'noreply@' . Module::get_site_domain(),
			'email_to_cc' => '',
			'email_to_bcc' => '',
		];

		foreach ( $fields as $key => $default ) {
			$setting = trim( $settings[ $this->get_control_id( $key ) ] );
			$setting = $record->replace_setting_shortcodes( $setting );
			if ( ! empty( $setting ) ) {
				$fields[ $key ] = $setting;
			}
		}

		$email_reply_to = $this->get_reply_to( $record, $fields );

		$fields['email_content'] = $this->replace_content_shortcodes( $fields['email_content'], $record, $line_break );

		$email_meta = '';

		$form_metadata_settings = $settings[ $this->get_control_id( 'form_metadata' ) ];

		foreach ( $record->get( 'meta' ) as $id => $field ) {
			if ( in_array( $id, $form_metadata_settings, true ) ) {
				$email_meta .= $this->field_formatted( $field ) . $line_break;
			}
		}

		if ( ! empty( $email_meta ) ) {
			$fields['email_content'] .= $line_break . '---' . $line_break . $line_break . $email_meta;
		}

		$headers = sprintf( 'From: %s <%s>' . "\r\n", $fields['email_from_name'], $fields['email_from'] );
		$headers .= sprintf( 'Reply-To: %s' . "\r\n", $email_reply_to );

		if ( $send_html ) {
			$headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
		}

		$cc_header = '';
		if ( ! empty( $fields['email_to_cc'] ) ) {
			$cc_header = 'Cc: ' . $fields['email_to_cc'] . "\r\n";
		}

		$email_sent = wp_mail(
			$fields['email_to'],
			$fields['email_subject'],
			$fields['email_content'],
			$headers . $cc_header,
		);

		if ( ! empty( $fields['email_to_bcc'] ) ) {
			$bcc_emails = explode( ',', $fields['email_to_bcc'] );
			foreach ( $bcc_emails as $bcc_email ) {
				wp_mail(
					trim( $bcc_email ),
					$fields['email_subject'],
					$fields['email_content'],
					$headers,
				);
			}
		}

		if ( ! $email_sent ) {
			$message = Ajax_Handler::get_default_message( Ajax_Handler::SERVER_ERROR, $settings );

			$ajax_handler->add_error_message( $message );

			throw new \Exception( esc_html( $message ) );
		}
	}

	private function field_formatted( $field ) {
		$formatted = '';
		if ( ! empty( $field['title'] ) ) {
			$formatted = sprintf( '%s: %s', $field['title'], $field['value'] );
		} elseif ( ! empty( $field['value'] ) ) {
			$formatted = sprintf( '%s', $field['value'] );
		}

		return $formatted;
	}

	// Allow overwrite the control_id with a prefix, @see Email2
	protected function get_control_id( $control_id ) {
		return $control_id;
	}

	protected function get_reply_to( $record, $fields ) {
		$email_reply_to  = '';

		if ( ! empty( $fields['email_reply_to'] ) ) {
			$sent_data = $record->get( 'sent_data' );
			foreach ( $record->get( 'fields' ) as $field_index => $field ) {
				if ( $field_index === $fields['email_reply_to'] && ! empty( $sent_data[ $field_index ] ) && is_email( $sent_data[ $field_index ] ) ) {
					$email_reply_to = $sent_data[ $field_index ];
					break;
				}
			}
		}

		return $email_reply_to;
	}

	/**
	 * @param string      $email_content
	 * @param Form_Record $record
	 *
	 * @return string
	 */
	private function replace_content_shortcodes( $email_content, $record, $line_break ) {
		$email_content = do_shortcode( $email_content );
		$all_fields_shortcode = '[all-fields]';

		if ( false !== strpos( $email_content, $all_fields_shortcode ) ) {
			$text = '';
			foreach ( $record->get( 'fields' ) as $field ) {

				$formatted = $this->field_formatted( $field );
				if ( ( 'textarea' === $field['type'] ) && ( '<br>' === $line_break ) ) {
					$formatted = str_replace( [ "\r\n", "\n", "\r" ], '<br />', $formatted );
				}

				$text .= $formatted . $line_break;
			}

			$email_content = str_replace( $all_fields_shortcode, $text, $email_content );

		}

		return $email_content;
	}
}
