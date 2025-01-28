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
use HelloPlus\Classes\Ehp_Button;

class Widget_CTA_Render {
	protected CTA $widget;
	const LAYOUT_CLASSNAME = 'ehp-cta';
	const TEXT_CONTAINER_CLASSNAME = 'ehp-cta__text-container';
	const CTAS_CONTAINER_CLASSNAME = 'ehp-cta__ctas-container';
	const BUTTON_CLASSNAME = 'ehp-cta__button';

	protected array $settings;

	public function __construct( CTA $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = self::LAYOUT_CLASSNAME;
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
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<div class="ehp-cta__elements-container">
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

		$text_container_classnames = self::TEXT_CONTAINER_CLASSNAME;

		$this->widget->add_render_attribute( 'text-container', [
			'class' => $text_container_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'text-container' ); ?>>
			<?php if ( $has_heading ) {
				$heading_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $heading_tag ), 'class="ehp-cta__heading"', esc_html( $heading_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $heading_output );
			} ?>
			<?php if ( $has_description ) {
				$description_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $description_tag ), 'class="ehp-cta__description"', esc_html( $description_text ) );
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

		$ctas_container_classnames = self::CTAS_CONTAINER_CLASSNAME;

		$this->widget->add_render_attribute( 'ctas-container', [
			'class' => $ctas_container_classnames,
		] );
		?>
			<div <?php $this->widget->print_render_attribute_string( 'ctas-container' ); ?>>
				<div class="ehp-cta__buttons-wrapper">
					<?php if ( $has_primary_button ) {
						$this->render_button( 'primary' );
					} ?>
					<?php if ( $has_secondary_button ) {
						$this->render_button( 'secondary' );
					} ?>
				</div>
			</div>
		<?php
	}

	public function render_button( $type ) {
		$button = new Ehp_Button( $this->widget, [
			'type' => $type,
			'widget_name' => 'cta',
		] );
		$button->render();
	}
}
