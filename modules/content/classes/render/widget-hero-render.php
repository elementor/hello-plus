<?php
namespace HelloPlus\Modules\Content\Classes\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Utils;
use HelloPlus\Modules\Content\Widgets\Hero;
use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Image,
};

class Widget_Hero_Render {
	protected Hero $widget;
	const LAYOUT_CLASSNAME = 'ehp-hero';
	const TEXT_CONTAINER_CLASSNAME = 'ehp-hero__text-container';
	const BUTTON_CLASSNAME = 'ehp-hero__button';
	const IMAGE_CLASSNAME = 'ehp-hero__image';

	protected array $settings;

	public function __construct( Hero $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = self::LAYOUT_CLASSNAME;
		$layout_full_height_controls = $this->settings['box_full_screen_height_controls'] ?? '';

		if ( ! empty( $layout_full_height_controls ) ) {
			foreach ( $layout_full_height_controls as $breakpoint ) {
				$layout_classnames .= ' is-full-height-' . $breakpoint;
			}
		}

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<?php
				$this->render_text_container();
				$this->render_cta_button();
				$this->render_image_container();
			?>
		</div>
		<?php
	}

	public function render_text_container() {
		$heading_text = $this->settings['heading_text'];
		$heading_tag = $this->settings['heading_tag'];
		$has_heading = '' !== $heading_text;

		$subheading_text = $this->settings['subheading_text'];
		$subheading_tag = $this->settings['subheading_tag'];
		$has_subheading = '' !== $subheading_text;

		$text_container_classnames = self::TEXT_CONTAINER_CLASSNAME;

		$this->widget->add_render_attribute( 'text-container', [
			'class' => $text_container_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'text-container' ); ?>>
			<?php if ( $has_heading ) {
				$heading_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $heading_tag ), 'class="ehp-hero__heading"', esc_html( $heading_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $heading_output );
			} ?>
			<?php if ( $has_subheading ) {
				$subheading_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $subheading_tag ), 'class="ehp-hero__subheading"', esc_html( $subheading_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $subheading_output );
			} ?>
		</div>
		<?php
	}

	public function render_cta_button() {
		$button = new Ehp_Button( $this->widget, [
			'type' => 'primary',
			'widget_name' => $this->widget->get_name(),
		] );
		?>
		<div class="ehp-hero__button-container">
			<?php $button->render(); ?>
		</div>
		<?php
	}

	protected function render_image_container() {
		$image = new Ehp_Image( $this->widget, [
			'widget_name' => $this->widget->get_name(),
		] );
		$image->render();
	}
}
