<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Render\Header;

use Elementor\Icons_Manager;
use HelloPlus\Classes\Ehp_Social_Platforms;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Classes\Render_Base;

class Render_Contact_Buttons extends Render_Base {

	public function render(): void {
		$this->setup_contact_buttons_container();
		$contact_buttons = $this->settings['contact_buttons_repeater'];
		?>
		<div <?php $this->widget->print_render_attribute_string( 'contact-buttons' ); ?>>
			<?php
			foreach ( $contact_buttons as $key => $contact_button ) {
				$this->render_contact_button( $contact_button, $key );
			}
			?>
		</div>
		<?php
	}

	protected function setup_contact_buttons_container(): void {
		$responsive_display = $this->settings['contact_buttons_responsive_display'];

		$contact_buttons_classnames = [
			$this->get_class_name( '__contact-buttons' ),
			'has-responsive-display-' . $responsive_display,
		];

		$this->widget->add_render_attribute( 'contact-buttons', [
			'class' => $contact_buttons_classnames,
		] );
	}

	protected function render_contact_button( array $contact_button, int $key ): void {
		$this->widget->remove_render_attribute( 'contact-button-' . $key );

		$link = $this->build_contact_button_link( $contact_button );
		$this->setup_contact_button_attributes( $contact_button, $key, $link );

		?>
		<a <?php $this->widget->print_render_attribute_string( 'contact-button-' . $key ); ?>>
			<?php $this->render_contact_button_content( $contact_button, $key ); ?>
		</a>
		<?php
	}

	protected function build_contact_button_link( array $contact_button ): array {
		return [
			'platform' => $contact_button['contact_buttons_platform'],
			'number' => $contact_button['contact_buttons_number'] ?? '',
			'username' => $contact_button['contact_buttons_username'] ?? '',
			'email_data' => [
				'contact_buttons_mail' => $contact_button['contact_buttons_mail'] ?? '',
				'contact_buttons_mail_subject' => $contact_button['contact_buttons_mail_subject'] ?? '',
				'contact_buttons_mail_body' => $contact_button['contact_buttons_mail_body'] ?? '',
			],
			'viber_action' => $contact_button['contact_buttons_viber_action'] ?? '',
			'url' => $contact_button['contact_buttons_url'] ?? '',
			'location' => $contact_button['contact_buttons_waze'] ?? '',
			'map' => $contact_button['contact_buttons_map'] ?? '',
		];
	}

	protected function setup_contact_button_attributes( array $contact_button, int $key, array $link ): void {
		$hover_animation = $this->settings['contact_button_hover_animation'];
		$button_classnames = [ $this->get_class_name( '__contact-button' ) ];

		if ( ! empty( $hover_animation ) ) {
			$button_classnames[] = 'elementor-animation-' . $hover_animation;
		}

		$this->widget->add_render_attribute( 'contact-button-' . $key, [
			'aria-label' => esc_attr( $contact_button['contact_buttons_label'] ),
			'class' => $button_classnames,
		] );

		$ehp_platforms = new Ehp_Social_Platforms( $this->widget );

		if ( $ehp_platforms->is_url_link( $contact_button['contact_buttons_platform'] ) ) {
			$ehp_platforms->render_link_attributes( $link, 'contact-button-' . $key );
		} else {
			$formatted_link = $ehp_platforms->get_formatted_link( $link, 'contact_icon' );

			$this->widget->add_render_attribute( 'contact-button-' . $key, [
				'href' => $formatted_link,
				'rel' => 'noopener noreferrer',
				'target' => '_blank',
			] );
		}
	}

	protected function render_contact_button_content( array $contact_button, int $key ): void {
		$link_type = $this->settings['contact_buttons_link_type'];
		$icon = $contact_button['contact_buttons_icon'];

		if ( 'icon' === $link_type ) {
			Icons_Manager::render_icon( $icon,
				[
					'aria-hidden' => 'true',
					'class' => $this->get_class_name( '__contact-button-icon' ),
				]
			);
		}

		if ( 'label' === $link_type ) {
			$this->render_contact_button_text( $contact_button, $key );
		}
	}

	protected function render_contact_button_text( $contact_button, $key ) {
		$label_repeater_key = $this->widget->public_get_repeater_setting_key(
			'contact_buttons_label',
			'contact_buttons_repeater',
			$key
		);

		$this->widget->remove_render_attribute( $label_repeater_key );

		$this->widget->public_add_inline_editing_attributes( $label_repeater_key, 'none' );

		Widget_Utils::maybe_render_text_html(
			$this->widget,
			$label_repeater_key,
			$this->get_class_name( '__contact-button-label' ),
			$contact_button['contact_buttons_label'],
			'span'
		);
		?>
		<?php
	}
}