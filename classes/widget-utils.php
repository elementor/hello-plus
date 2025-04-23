<?php

namespace HelloPlus\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Includes\Utils as Theme_Utils;

class Widget_Utils {

	public static function get_configured_breakpoints(): array {
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

	public static function maybe_render_text_html(
		\Elementor\Widget_Base $context,
		string $render_key,
		string $css_class,
		string $settings_text,
		string $settings_tag = 'p'
	): void {
		if ( '' === $settings_text ) {
			return;
		}

		$context->add_render_attribute( $render_key, 'class', $css_class );

		$element_html = sprintf(
			'<%1$s %2$s>%3$s</%1$s>',
			\Elementor\Utils::validate_html_tag( $settings_tag ),
			$context->get_render_attribute_string( $render_key ),
			$settings_text,
		);

		echo wp_kses_post( $element_html );
	}
}
