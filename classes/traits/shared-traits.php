<?php

namespace HelloPlus\Classes\Traits;

use HelloPlus\Includes\Utils as Theme_Utils;

use Elementor\Utils;

trait Shared_Traits {

	protected function get_configured_breakpoints() {
		$active_devices = Theme_Utils::elementor()->breakpoints->get_active_devices_list( [ 'reverse' => true ] );
		$active_breakpoint_instances = Theme_Utils::elementor()->breakpoints->get_active_breakpoints();

		$devices_options = [];

		foreach ( $active_devices as $device_key ) {
			$device_label = 'desktop' === $device_key ? esc_html__( 'Desktop', 'hello-plus' ) : $active_breakpoint_instances[ $device_key ]->get_label();
			$devices_options[ $device_key ] = $device_label;
		}

		return [
			'active_devices' => $active_devices,
			'devices_options' => $devices_options,
		];
	}

	protected function maybe_render_text_html( $render_key, $css_class, $settings_text, $settings_tag = 'p' ) {
		if ( '' !== $settings_text ) {
			$this->widget->add_render_attribute( $render_key, 'class', $css_class );

			$element = wp_kses_post( $settings_text );

			$element_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $settings_tag ), $this->widget->get_render_attribute_string( $render_key ), $element );

			// PHPCS - the variable $element_html holds safe data.
			echo $element_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
