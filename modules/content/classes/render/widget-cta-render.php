<?php

namespace HelloPlus\Modules\Content\Classes\Render;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;

use HelloPlus\Modules\Content\Widgets\CTA;


class Widget_CTA_Render {
	protected CTA $widget;

	protected array $settings;

	public function __construct( CTA $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = 'e-cta';
		$layout_full_height_controls = $this->settings['box_full_screen_height_controls'] ?? '';
		$elements_position = $this->settings['elements_position'];

		if ( ! empty( $layout_full_height_controls ) ) {
			foreach ( $layout_full_height_controls as $breakpoint ) {
				$layout_classnames .= ' is-full-height-' . $breakpoint;
			}
		}

		if ( ! empty( $elements_position ) ) {
			$layout_classnames .= ' has-elements-position-' . $elements_position;
		}

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'layout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="e-cta__elements-container">
				<?php
					$this->render_text_container();
					$this->render_ctas_container();
				?>
			</div>
		</div>
		<?php
	}

	protected function render_text_container() {
		$heading_text = $this->settings['heading_text'];
		$heading_tag = $this->settings['heading_tag'];
		$has_heading = '' !== $heading_text;

		$description_text = $this->settings['description_text'];
		$description_tag = $this->settings['description_tag'];
		$has_description = '' !== $description_text;

		$text_container_classnames = 'e-cta__text-container';

		$this->widget->add_render_attribute( 'text-container', [
			'class' => $text_container_classnames,
		] );
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'text-container' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( $has_heading ) {
				$heading_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $heading_tag ), 'class="e-cta__heading"', esc_html( $heading_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $heading_output );
			} ?>
			<?php if ( $has_description ) {
				$description_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $description_tag ),  'class="e-cta__description"', esc_html( $description_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $description_output );
			} ?>
		</div>
		<?php
	}

	protected function render_ctas_container() {
		$primary_cta_button_text = $this->settings['primary_cta_button_text'];
		$secondary_cta_button_text = $this->settings['secondary_cta_button_text'];
		$has_primary_button = ! empty( $primary_cta_button_text );
		$has_secondary_button = ! empty( $secondary_cta_button_text );

		$ctas_container_classnames = 'e-cta__ctas-container';

		$this->widget->add_render_attribute( 'ctas-container', [
			'class' => $ctas_container_classnames,
		] );
		?>
			<div <?php echo $this->widget->get_render_attribute_string( 'ctas-container' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( $has_primary_button ) {
				$this->render_button( 'primary' );
			} ?>
			<?php if ( $has_secondary_button ) {
				$this->render_button( 'secondary' );
			} ?>
			</div>
		<?php
	}

	protected function render_button( $type ) {
		$button_text = $this->settings[ $type . '_cta_button_text' ];
		$button_link = $this->settings[ $type . '_cta_button_link' ];
		$button_icon = $this->settings[ $type . '_cta_button_icon' ];
		$button_hover_animation = $this->settings[ $type . '_button_hover_animation' ] ?? '';
		$button_has_border = $this->settings[ $type . '_show_button_border' ];
		$button_corner_shape = $this->settings[ $type . '_button_shape' ] ?? '';
		$button_type = $this->settings[ $type . '_button_type' ] ?? '';
		$button_classnames = 'e-cta__button e-cta__button--' . $type;

		if ( ! empty( $button_type ) ) {
			$button_classnames .= ' is-type-' . $button_type;
		}

		if ( $button_hover_animation ) {
			$button_classnames .= ' elementor-animation-' . $button_hover_animation;
		}

		if ( 'yes' === $button_has_border ) {
			$button_classnames .= ' has-border';
		}

		if ( ! empty( $button_corner_shape ) ) {
			$button_classnames .= ' has-shape-' . $button_corner_shape;
		}

		$this->widget->add_render_attribute(  $type . '-button', [
			'class' => $button_classnames,
		] );

		if ( ! empty( $button_link ) ) {
			$this->widget->add_link_attributes( $type . '-button', $button_link );
		}

		?>
		<a <?php echo $this->widget->get_render_attribute_string( $type . '-button' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
				Icons_Manager::render_icon( $button_icon,
					[
						'aria-hidden' => 'true',
						'class' => 'e-cta__button-icon',
					]
				);
			?>
			<?php echo esc_html( $button_text ); ?>
		</a>
		<?php
	}
}
