<?php
namespace HelloPlus\Modules\Forms\Actions;

use HelloPlus\Modules\Forms\Classes\Action_Base;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Redirect extends Action_Base {

	public function get_name(): string {
		return 'ehp-redirect';
	}

	public function get_label(): string {
		return esc_html__( 'Redirect', 'hello-plus' );
	}

	public function register_settings_section( $widget ) {
	}

	public function on_export( $element ) {
		unset(
			$element['settings']['redirect_to']
		);

		return $element;
	}

	public function run( $record, $ajax_handler ) {
		$redirect_to = $record->get_form_settings( 'redirect_to' );
		if ( empty( $redirect_to ) ) {
			return;
		}

		$redirect_to = $record->replace_setting_shortcodes( $redirect_to, true );

		$redirect_to = esc_url_raw( $redirect_to );

		if ( ! empty( $redirect_to ) && filter_var( $redirect_to, FILTER_VALIDATE_URL ) ) {
			$ajax_handler->add_response_data( 'redirect_url', $redirect_to );
		}
	}
}
