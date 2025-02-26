<?php
namespace HelloPlus\Modules\Content\Classes\Render;

use HelloPlus\Modules\Content\Widgets\Contact;
use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Image,
	Ehp_Shapes,
};

use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Contact_Render {
	protected Contact $widget;
	const LAYOUT_CLASSNAME = 'ehp-contact';

	protected array $settings;

	public function __construct( Contact $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = [
			self::LAYOUT_CLASSNAME,
			'has-preset-' . $this->settings['layout_preset'],
		];
		$layout_full_height_controls = $this->settings['box_full_screen_height_controls'] ?? '';
		$show_map = 'quick-info' !== $this->settings['layout_preset'];

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<?php
				$this->render_text_container();
				
				if ( $show_map ) {
					$this->render_map_container();
				}
			?>
		</div>
		<?php
	}

	protected function render_text_container() {
		$heading_text = $this->settings['heading_text'];
		$heading_tag = $this->settings['heading_tag'];

		$description_text = $this->settings['description_text'];
		$description_tag = $this->settings['description_tag'];

		$text_container_classnames = [
			self::LAYOUT_CLASSNAME . '__text-container',
		];
		$heading_classname = self::LAYOUT_CLASSNAME . '__heading';
		$description_classname = self::LAYOUT_CLASSNAME . '__description';

		$this->widget->add_render_attribute( 'text-container', [
			'class' => $text_container_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'text-container' ); ?>>
			<?php if ( '' !== $heading_text ) {
				$heading_output = sprintf( '<%1$s class="%2$s">%3$s</%1$s>', Utils::validate_html_tag( $heading_tag ), $heading_classname, esc_html( $heading_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $heading_output );
			} ?>
			<?php if ( '' !== $description_text ) {
				$description_output = sprintf( '<%1$s class="%2$s">%3$s</%1$s>', Utils::validate_html_tag( $description_tag ), $description_classname, esc_html( $description_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $description_output );
			} ?>
		</div>
		<?php
	}

	protected function render_map_container() {
		$map_container_classnames = [
			self::LAYOUT_CLASSNAME . '__map-container',
		];

		$this->widget->add_render_attribute( 'map-container', [
			'class' => $map_container_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'map-container' ); ?>>
			Map container
		</div>
		<?php
	}
}