<?php
namespace HelloPlus\Modules\Content\Classes\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;
use HelloPlus\Modules\Content\Widgets\Hero;

class Widget_Hero_Render {
	protected Hero $widget;

	protected array $settings;

	public function __construct( Hero $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = 'e-hero';
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
		<div <?php echo $this->widget->get_render_attribute_string( 'layout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
				$this->render_text_container();
				$this->render_cta_button();
				$this->render_image();
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

		$text_container_classnames = 'e-hero__text-container';

		$this->widget->add_render_attribute( 'text-container', [
			'class' => $text_container_classnames,
		] );
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'text-container' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( $has_heading ) {
				$heading_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $heading_tag ), 'class="e-hero__heading"', esc_html( $heading_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $heading_output );
			} ?>
			<?php if ( $has_subheading ) {
				$subheading_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $subheading_tag ), 'class="e-hero__subheading"', esc_html( $subheading_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $subheading_output );
			} ?>
		</div>
		<?php
	}

	public function render_cta_button() {
		$button_text = $this->settings['cta_button_text'];
		$button_link = $this->settings['cta_button_link'];
		$button_icon = $this->settings['cta_button_icon'];
		$has_button = '' !== $button_text;
		$button_hover_animation = $this->settings['button_hover_animation'] ?? '';
		$button_has_border = $this->settings['show_button_border'];
		$button_corner_shape = $this->settings['button_shape'] ?? '';
		$button_type = $this->settings['button_type'] ?? '';
		$button_classnames = 'e-hero__button';

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

		$this->widget->add_render_attribute( 'button', [
			'class' => $button_classnames,
		] );
		if ( ! empty( $button_link ) ) {
			$this->widget->add_link_attributes( 'button', $button_link );
		}

		if ( $has_button ) { ?>
			<div class="e-hero__button-container">
				<a <?php echo $this->widget->get_render_attribute_string( 'button' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php
					Icons_Manager::render_icon( $button_icon,
						[
							'aria-hidden' => 'true',
							'class' => 'e-hero__button-icon',
						]
					);
				?>
				<?php echo esc_html( $button_text ); ?>
				</a>
			</div>
		<?php }
	}

	public function render_image() {
		$image = $this->settings['image'];
		$has_image = ! empty( $image['url'] );

		$this->widget->add_render_attribute( 'image', [
			'class' => 'e-hero__image',
		] );
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
			if ( $has_image ) {
				Group_Control_Image_Size::print_attachment_image_html( $this->settings, 'image' );
			}
			?>
		</div>
		<?php
	}
}
