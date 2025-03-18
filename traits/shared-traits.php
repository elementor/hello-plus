<?php

namespace HelloPlus\Traits;

use HelloPlus\Includes\Utils as Theme_Utils;

use Elementor\Utils;

trait Shared_Traits {

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
