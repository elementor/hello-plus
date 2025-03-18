<?php

namespace HelloPlus\Modules\Content\Classes\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Icons_Manager;
use Elementor\Utils;

use HelloPlus\Modules\Content\Widgets\Zig_Zag;
use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Image,
};

use HelloPlus\Classes\Traits\Shared_Traits;

class Widget_Zig_Zag_Render {
	protected Zig_Zag $widget;
	const LAYOUT_CLASSNAME = 'ehp-zigzag';

	use Shared_Traits;

	protected array $settings;

	public function __construct( Zig_Zag $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$first_zigzag_direction = $this->settings['first_zigzag_direction'];
		$has_alternate_icon_color = $this->settings['has_alternate_icon_color'] ?? 'no';
		$entrance_animation = $this->settings['zigzag_animation'] ?? '';
		$has_entrance_animation = ! empty( $entrance_animation ) && 'none' !== $entrance_animation;
		$has_alternate_animation = 'yes' === $this->settings['animation_alternate'];
		$image_stretch = $this->settings['image_stretch'];

		$layout_classnames = [
			self::LAYOUT_CLASSNAME,
			'has-direction-' . $first_zigzag_direction,
		];

		if ( 'yes' === $has_alternate_icon_color ) {
			$layout_classnames[] = 'has-alternate-icon-color';
		}

		if ( $has_entrance_animation ) {
			$layout_classnames[] = 'has-entrance-animation';
		}

		if ( $has_alternate_animation ) {
			$layout_classnames[] = 'has-alternate-animation';
		}

		if ( 'yes' === $image_stretch ) {
			$layout_classnames[] = 'has-image-stretch';
		}

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );

		?>
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<?php
			$graphic_element = $this->settings['graphic_element'];
			$repeater = 'image' === $graphic_element ? $this->settings['image_zigzag_items'] : $this->settings['icon_zigzag_items'];

			$wrapper_classnames = [
				self::LAYOUT_CLASSNAME . '__item-wrapper',
			];

			if ( $has_entrance_animation ) {
				$wrapper_classnames[] = 'hidden';
			}

			foreach ( $repeater as $key => $item ) {
				$this->widget->add_render_attribute( 'zigzag-item-wrapper-' . $key, [
					'class' => $wrapper_classnames,
				] );

				$this->widget->add_render_attribute( 'zigzag-item-' . $key, [
					'class' => self::LAYOUT_CLASSNAME . '__item-container',
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

		$graphic_element_classnames = [
			self::LAYOUT_CLASSNAME . '__graphic-element-container',
		];

		$is_icon = 'icon' === $graphic_element && ! empty( $item['icon_graphic_icon'] );
		$is_image = 'image' === $graphic_element && ! empty( $item['image_graphic_image']['url'] );

		if ( $is_icon ) {
			$graphic_element_classnames[] = 'has-icon';
		} elseif ( $is_image ) {
			$graphic_element_classnames[] = 'has-image';
		}

		$this->widget->add_render_attribute( 'graphic-element-container-' . $key, [
			'class' => $graphic_element_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'graphic-element-container-' . $key ); ?>>
			<?php if ( $is_image ) {
				$this->render_image_container( $item, 'image_graphic_image' );
			} elseif ( $is_icon ) {
				Icons_Manager::render_icon( $item['icon_graphic_icon'], [ 'aria-hidden' => 'true' ] );
			} ?>
		</div>
		<?php
	}

	private function render_image_container( $settings, $key ) {
		$defaults = [
			'settings' => $settings,
			'image_key' => $key,
			'image' => $settings[ $key ],
		];

		$image = new Ehp_Image( $this->widget, [
			'widget_name' => $this->widget->get_name(),
		], $defaults );
		$image->render();
	}

	private function render_text_element_container( $item, $key ) {
		$graphic_element = $this->settings['graphic_element'];

		$title_tag = $this->settings['zigzag_title_tag'] ?? 'h2';
		$title_text = $item[ $graphic_element . '_title' ] ?? '';
		$has_title = ! empty( $title_text );

		$description_text = $item[ $graphic_element . '_description' ] ?? '';
		$has_description = ! empty( $description_text );

		$is_graphic_image = 'image' === $graphic_element;
		$is_graphic_icon = 'icon' === $graphic_element;
		$text_container_classnames = [
			self::LAYOUT_CLASSNAME . '__text-container',
		];

		$this->widget->add_render_attribute( 'description-' . $key, [
			'class' => self::LAYOUT_CLASSNAME . '__description',
		] );

		if ( $is_graphic_icon ) {
			$text_container_classnames[] = 'is-graphic-icon';
		} elseif ( $is_graphic_image ) {
			$text_container_classnames[] = 'is-graphic-image';
		}

		$this->widget->add_render_attribute( 'text-container-' . $key, [
			'class' => $text_container_classnames,
		] );

		$title_classname = self::LAYOUT_CLASSNAME . '__title';
		$description_classname = self::LAYOUT_CLASSNAME . '__description';
		?>
		<div <?php $this->widget->print_render_attribute_string( 'text-container-' . $key ); ?>>
			<?php
			$this->maybe_render_text_html( $graphic_element . '_title' . $key, $title_classname, $item[ $graphic_element . '_title' ], $this->settings['zigzag_title_tag'] );
			$this->maybe_render_text_html( $graphic_element . '_description' . $key, $description_classname, $item[ $graphic_element . '_description' ] );

			if ( ! empty( $item[ $graphic_element . '_button_text' ] ) ) {
				$this->render_cta_button( $item, $key );
			}
			?>
		</div>
		<?php
	}

	public function render_cta_button( $item, $key ) {
		$graphic_element = $this->settings['graphic_element'];

		$defaults = [
			'button_text' => $item[ $graphic_element . '_button_text' ] ?? '',
			'button_link' => $item[ $graphic_element . '_button_link' ] ?? '',
			'button_icon' => $item[ $graphic_element . '_button_icon' ] ?? '',
			'button_hover_animation' => $this->settings['primary_button_hover_animation'] ?? '',
			'button_has_border' => $this->settings['primary_show_button_border'],
			'button_type' => $this->settings['primary_button_type'] ?? '',
		];

		$button = new Ehp_Button( $this->widget, [
			'type' => 'primary',
			'widget_name' => $this->widget->get_name(),
			'key' => $key,
		], $defaults );

		$this->widget->add_render_attribute( 'button-container', 'class', self::LAYOUT_CLASSNAME . '__button-container' );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'button-container' ); ?>>
			<?php $button->render(); ?>
		</div>
		<?php
	}
}
