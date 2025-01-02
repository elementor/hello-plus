<?php

namespace HelloPlus\Modules\Content\Classes\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;

use HelloPlus\Modules\Content\Widgets\Zig_Zag;

class Widget_Zig_Zag_Render {
	protected Zig_Zag $widget;
	const LAYOUT_CLASSNAME = 'ehp-zigzag';
	const ITEM_CLASSNAME = 'ehp-zigzag__item-container';
	const GRAPHIC_ELEMENT_CLASSNAME = 'ehp-zigzag__graphic-element-container';
	const BUTTON_CLASSNAME = 'ehp-zigzag__button';

	protected array $settings;

	public function __construct( Zig_Zag $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = self::LAYOUT_CLASSNAME;
		$first_zigzag_direction = $this->settings['first_zigzag_direction'];
		$has_alternate_icon_color = $this->settings['has_alternate_icon_color'];
		$entrance_animation = $this->settings['zigzag_animation'] ?? '';
		$has_entrance_animation = ! empty( $entrance_animation ) && 'none' !== $entrance_animation;
		$has_alternate_animation = 'yes' === $this->settings['animation_alternate'];

		if ( $has_entrance_animation ) {
			$layout_classnames .= ' has-entrance-animation';
		}

		$layout_classnames .= ' has-direction-' . $first_zigzag_direction;

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );

		?>
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<?php
			$remainder = 'left' === $first_zigzag_direction ? 1 : 0;

			$graphic_element = $this->settings['graphic_element'];
			$repeater = 'image' === $graphic_element ? $this->settings['image_zigzag_items'] : $this->settings['icon_zigzag_items'];

			foreach ( $repeater as $key => $item ) {
				$is_even = $remainder === $key % 2;

				$item_class = self::ITEM_CLASSNAME;

				$item_class .= ' row' . ( $is_even ? '-even' : '-odd' );

				$is_even_index = 0 === ($key + 1) % 2;

				if ( $has_alternate_icon_color && $is_even_index ) {
					$item_class .= ' has-alternate-icon-color';
				}

				$wrapper_classnames = 'ehp-zigzag__item-wrapper';

				if ( $has_entrance_animation ) {
					$wrapper_classnames .= ' has-entrance-animation';

					if ( $has_alternate_animation && $is_even_index ) {
						$wrapper_classnames .= ' has-alternate-animation';
					}
				}

				$this->widget->add_render_attribute( 'zigzag-item-wrapper-' . $key, [
					'class' => $wrapper_classnames,
				] );

				$this->widget->add_render_attribute( 'zigzag-item-' . $key, [
					'class' => $item_class,
				] );
				?>
				<div <?php $this->widget->print_render_attribute_string( 'zigzag-item-wrapper-' . $key ); ?>>
					<div <?php $this->widget->print_render_attribute_string( 'zigzag-item-' . $key ); ?>>
						<?php
							$this->render_graphic_element_container( $item, $key );
							$this->render_text_element_container( $item, $key );
						?>
					</div>
				</div>
				<?php
			} ?>
			</div>
		<?php
	}

	private function render_graphic_element_container( $item, $key ) {
		$graphic_element = $this->settings['graphic_element'];

		$graphic_element_classnames = self::GRAPHIC_ELEMENT_CLASSNAME;

		$is_icon = 'icon' === $graphic_element && ! empty( $item['icon_graphic_icon'] );
		$is_image = 'image' === $graphic_element && ! empty( $item['image_graphic_image']['url'] );

		if ( $is_icon ) {
			$graphic_element_classnames .= ' has-icon';
		} elseif ( $is_image ) {
			$graphic_element_classnames .= ' has-image';
		}

		$this->widget->add_render_attribute( 'graphic-element-container-' . $key, [
			'class' => $graphic_element_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'graphic-element-container-' . $key ); ?>>
			<?php if ( $is_image ) : ?>
				<?php Group_Control_Image_Size::print_attachment_image_html( $item, 'image_graphic_image' ); ?>
			<?php elseif ( $is_icon ) : ?>
				<?php Icons_Manager::render_icon( $item['icon_graphic_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	private function render_text_element_container( $item, $key ) {
		$graphic_element = $this->settings['graphic_element'];

		$button_text = $item[ $graphic_element . '_button_text' ] ?? '';
		$button_link = $item[ $graphic_element . '_button_link' ] ?? '';
		$button_icon = $item[ $graphic_element . '_button_icon' ] ?? '';
		$has_button = ! empty( $button_text );

		$title_tag = $this->settings['zigzag_title_tag'] ?? 'h2';
		$title_text = $item[ $graphic_element . '_title' ] ?? '';
		$has_title = ! empty( $title_text );

		$description_text = $item[ $graphic_element . '_description' ] ?? '';
		$has_description = ! empty( $description_text );

		$button_classnames = self::BUTTON_CLASSNAME;
		$button_hover_animation = $this->settings['button_hover_animation'] ?? '';
		$button_has_border = $this->settings['show_button_border'];
		$button_corner_shape = $this->settings['button_shape'] ?? '';
		$button_type = $this->settings['button_type'] ?? '';

		$is_graphic_image = 'image' === $graphic_element;
		$is_graphic_icon = 'icon' === $graphic_element;
		$text_container_classnames = 'ehp-zigzag__text-container';

		$this->widget->add_render_attribute( 'description-' . $key, [
			'class' => 'ehp-zigzag__description',
		] );

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

		$this->widget->add_render_attribute( 'button-link-' . $key, [
			'class' => $button_classnames,
		] );

		if ( ! empty( $button_link ) ) {
			$this->widget->add_link_attributes( 'button-link-' . $key, $button_link );
		}

		if ( $is_graphic_icon ) {
			$text_container_classnames .= ' is-graphic-icon';
		} elseif ( $is_graphic_image ) {
			$text_container_classnames .= ' is-graphic-image';
		}

		$this->widget->add_render_attribute( 'text-container-' . $key, [
			'class' => $text_container_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'text-container-' . $key ); ?>>
			<?php if ( $has_title ) {
				$title_output = sprintf( '<%1$s %2$s %3$s>%4$s</%1$s>', Utils::validate_html_tag( $title_tag ), $this->widget->get_render_attribute_string( 'heading' ), 'class="ehp-zigzag__title"', esc_html( $title_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $title_output );
			} ?>
			<?php if ( $has_description ) { ?>
				<p <?php $this->widget->print_render_attribute_string( 'description-' . $key ); ?>><?php echo esc_html( $description_text ); ?></p>
			<?php } ?>
			<?php if ( $has_button ) { ?>
				<div class="ehp-zigzag__button-container">
					<a <?php $this->widget->print_render_attribute_string( 'button-link-' . $key ); ?>>
					<?php
						Icons_Manager::render_icon( $button_icon,
							[
								'aria-hidden' => 'true',
								'class' => 'ehp-zigzag__button-icon',
							]
						);
					?>
					<?php echo esc_html( $button_text ); ?>
					</a>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}
