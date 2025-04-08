<?php

namespace HelloPlus\Modules\Content\Classes\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Classes\Widget_Utils;
use Elementor\{
	Icons_Manager,
};

use HelloPlus\Modules\Content\Widgets\Zig_Zag;
use HelloPlus\Classes\{
	Ehp_Image,
	Ehp_Shapes,
};

class Widget_Zig_Zag_Render {

	protected Zig_Zag $widget;

	const LAYOUT_CLASSNAME = 'ehp-zigzag';

	protected array $settings;

	public function __construct( Zig_Zag $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$has_entrance_animation = ! empty( $this->settings['zigzag_animation'] ) && 'none' !== $this->settings['zigzag_animation'];
		$layout_classnames = [
			self::LAYOUT_CLASSNAME,
			'has-direction-' . $this->settings['first_zigzag_direction'],
		];

		if ( $has_entrance_animation ) {
			$layout_classnames[] = 'has-entrance-animation';
		}

		if ( 'yes' === $this->settings['image_stretch'] ) {
			$layout_classnames[] = 'has-image-stretch';
		}

		if ( 'yes' === $this->settings['has_alternate_button_styles'] ) {
			$layout_classnames[] = 'has-alternate-button-styles';
		}

		if ( 'yes' === $this->settings['has_alternate_icon_color'] ) {
			$layout_classnames[] = 'has-alternate-icon-color';
		}

		if ( 'yes' === $this->settings['has_alternate_button_border'] ) {
			$layout_classnames[] = 'has-alternate-button-border-styles';
		}

		if ( 'yes' === $this->settings['animation_alternate'] ) {
			$layout_classnames[] = 'has-alternate-animation';
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
							$this->render_text_element_container( $item, $key, $repeater );
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

		$is_graphic_image = 'image' === $graphic_element;
		$is_graphic_icon = 'icon' === $graphic_element;
		$text_container_classnames = [
			self::LAYOUT_CLASSNAME . '__text-container',
		];

		$zigzag_item_title_setting_key = $this->widget->public_get_repeater_setting_key(
			$graphic_element . '_title',
			$graphic_element . '_zigzag_items',
			$key
		);

		$zigzag_item_description_setting_key = $this->widget->public_get_repeater_setting_key(
			$graphic_element . '_description',
			$graphic_element . '_zigzag_items',
			$key
		);

		$this->widget->public_add_inline_editing_attributes( $zigzag_item_title_setting_key, 'none' );
		$this->widget->public_add_inline_editing_attributes( $zigzag_item_description_setting_key, 'none' );

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

			Widget_Utils::maybe_render_text_html(
				$this->widget,
				$zigzag_item_title_setting_key,
				$title_classname,
				$item[ $graphic_element . '_title' ],
				$this->settings['zigzag_title_tag']
			);

			Widget_Utils::maybe_render_text_html(
				$this->widget,
				$zigzag_item_description_setting_key,
				$description_classname,
				$item[ $graphic_element . '_description' ]
			);

			if ( ! empty( $item[ $graphic_element . '_button_text' ] ) ) {
				$this->render_cta_button( $item, $key );
			}
			?>
		</div>
		<?php
	}

	public function render_cta_button( $item, $key ) {
		$graphic_element = $this->settings['graphic_element'];

		$this->widget->add_render_attribute( 'button-container', 'class', self::LAYOUT_CLASSNAME . '__button-container' );

		$button_classnames = [
			'ehp-button',
			'ehp-button--primary',
			self::LAYOUT_CLASSNAME . '__button',
			self::LAYOUT_CLASSNAME . '__button--primary',
		];

		if ( ! empty( $this->settings['primary_button_type'] ) ) {
			$button_classnames[] = 'is-type-' . $this->settings['primary_button_type'];
		}

		if ( $this->settings['primary_button_hover_animation'] ) {
			$button_classnames[] = 'elementor-animation-' . $this->settings['primary_button_hover_animation'];
		}

		if ( 'yes' === $this->settings['primary_show_button_border'] ) {
			$button_classnames[] = 'has-border';
		}

		$button_render_attr = $graphic_element . '-primary-button-' . $key;

		$shapes = new Ehp_Shapes( $this->widget, [
			'widget_name' => $this->widget->get_name(),
			'container_prefix' => 'button',
			'type_prefix' => 'primary',
			'render_attribute' => $button_render_attr,
			'key' => $key,
		] );
		$shapes_classnames = $shapes->get_shape_classnames();

		$button_classnames = array_merge( $button_classnames, $shapes_classnames );

		$this->widget->add_render_attribute( $button_render_attr, [
			'class' => $button_classnames,
		] );

		$this->widget->add_link_attributes( $button_render_attr, $item[ $graphic_element . '_button_link' ], true );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'button-container' ); ?>>
			<a <?php $this->widget->print_render_attribute_string( $button_render_attr ); ?>>
				<?php
				Icons_Manager::render_icon( $item[ $graphic_element . '_button_icon' ], [
					'aria-hidden' => 'true',
					'class' => 'ehp-button__icon',
					self::LAYOUT_CLASSNAME . '__button-icon',
				] );

				$this->render_button_text( $item, $key );
				?>
			</a>
		</div>
		<?php
	}

	protected function render_button_text( $item, $key ) {
		$graphic_element = $this->settings['graphic_element'];

		$render_attr = $graphic_element . '_button_text';

		$this->widget->add_render_attribute( $render_attr, [
			'class' => self::LAYOUT_CLASSNAME . '__button-text',
		] );

		$zigzag_item_button_setting_key = $this->widget->public_get_repeater_setting_key(
			$graphic_element . '_button_text',
			$graphic_element . '_zigzag_items',
			$key
		);

		$this->widget->public_add_inline_editing_attributes( $zigzag_item_button_setting_key, 'none' );

		Widget_Utils::maybe_render_text_html(
			$this->widget,
			$zigzag_item_button_setting_key,
			self::LAYOUT_CLASSNAME . '__button-text',
			$item[ $graphic_element . '_button_text' ],
			'span',
		);
		?>
		<?php
	}
}
