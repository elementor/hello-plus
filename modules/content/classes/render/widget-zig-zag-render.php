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

use HelloPlus\Modules\Content\Widgets\Zig_Zag;

class Widget_Zig_Zag_Render {
	protected Zig_Zag $widget;

	protected array $settings;

	public function __construct( Zig_Zag $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = 'e-zigzag';

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'layout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
			$remainder = 'row' === $this->settings['first_zigzag_direction'] ? 0 : 1;

			$graphic_element = $this->settings['graphic_element'];
			$repeater = 'image' === $graphic_element ? $this->settings['image_zigzag_items'] : $this->settings['icon_zigzag_items'];

			foreach ( $repeater as $key => $item ) {
				$is_odd = $remainder !== $key % 2;

				$item_class = 'e-zigzag__item-container ';

				$item_class .= 'row' . ( $is_odd ? '-odd' : '-even' );

				$this->widget->add_render_attribute( 'zigzag-item-' . $key, [
					'class' => $item_class,
				] );
				?>
				<div class="e-zigzag__item-wrapper">
					<div <?php echo $this->widget->get_render_attribute_string( 'zigzag-item-' . $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
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

		$graphic_element_classnames = 'e-zigzag__graphic-element-container';

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
		<div <?php echo $this->widget->get_render_attribute_string( 'graphic-element-container-' . $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
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

		$title_tag = $item[ $graphic_element . '_title_tag' ] ?? 'h2';
		$title_text = $item[ $graphic_element . '_title' ] ?? '';
		$has_title = ! empty( $title_text );

		$description_text = $item[ $graphic_element . '_description' ] ?? '';
		$has_description = ! empty( $description_text );

		$button_classnames = 'e-zigzag__button';
		$button_hover_animation = $this->settings['button_hover_animation'] ?? '';
		$button_has_border = $this->settings['show_button_border'];
		$button_corner_shape = $this->settings['button_shape'] ?? '';
		$button_type = $this->settings['button_type'] ?? '';

		$is_graphic_image = 'image' === $graphic_element;
		$is_graphic_icon = 'icon' === $graphic_element;
		$text_container_classnames = 'e-zigzag__text-container';

		$this->widget->add_render_attribute( 'description-' . $key, [
			'class' => 'e-zigzag__description',
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
		<div <?php echo $this->widget->get_render_attribute_string( 'text-container-' . $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( $has_title ) {
				$title_output = sprintf( '<%1$s %2$s %3$s>%4$s</%1$s>', Utils::validate_html_tag( $title_tag ), $this->widget->get_render_attribute_string( 'heading' ), 'class="e-zigzag__title"', esc_html( $title_text ) );
				// Escaped above
				Utils::print_unescaped_internal_string( $title_output );
			} ?>
			<?php if ( $has_description ) { ?>
				<p <?php echo $this->widget->get_render_attribute_string( 'description-' . $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $description_text ); ?></p>
			<?php } ?>
			<?php if ( $has_button ) { ?>
				<div class="e-zigzag__button-container">
					<a <?php echo $this->widget->get_render_attribute_string( 'button-link-' . $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php
						Icons_Manager::render_icon( $button_icon,
							[
								'aria-hidden' => 'true',
								'class' => 'e-zigzag__button-icon',
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
