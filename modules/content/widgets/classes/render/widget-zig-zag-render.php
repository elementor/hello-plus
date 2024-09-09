<?php

namespace HelloPlus\Modules\Content\Widgets\Classes\Render;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;

use HelloPlus\Modules\Content\Widgets\Classes\Base\Widget_Zig_Zag_Base;

class Widget_Zig_Zag_Render {
	protected Widget_Zig_Zag_Base $widget;

	protected array $settings;

	public function __construct( Widget_Zig_Zag_Base $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}


	public function render(): void {
		// $settings = $this->get_settings_for_display();

/*		// Custom Attributes
		$custom_attributes = $settings['zigzag_custom_attributes'];
		$custom_attribute_string = '';
		if (!empty($custom_attributes)) {
			$custom_attributes = explode("\n", $custom_attributes);
			$custom_attributes = array_filter($custom_attributes);
			foreach ($custom_attributes as $attribute) {
				if (strpos($attribute, '|') !== false) {
					list($key, $value) = explode('|', $attribute);
					$custom_attribute_string .= ' ' . $key . '="' . esc_attr($value) . '"';
				}
			}
		}
*/
/*
		if ( 'yes' === $settings['zigzag_hide_on_desktop'] ) {
			$wrapper_classes .= ' elementor-hidden-desktop';
		}

		if ('yes' === $settings['zigzag_hide_on_tablet']) {
			$wrapper_classes .= ' elementor-hidden-tablet';
		}

		if ('yes' === $settings['zigzag_hide_on_mobile']) {
			$wrapper_classes .= ' elementor-hidden-mobile';
		}
*/
		$wrapper_classes = 'elementor-widget-zigzag__wrapper';
		$has_border = $this->settings['show_widget_border'];
		$animation_styles = '';

		if ( ! empty( $this->settings['zigzag_entrance_animation'] ) ) {
			$wrapper_classes .= ' animated ' . esc_attr( $this->settings['zigzag_entrance_animation'] );

			$animation_duration_map = [
				'slow' => 2000,
				'normal' => 1000,
				'fast' => 500,
			];

			$animation_duration_setting = $this->settings['zigzag_animation_duration'];

			if ( is_numeric( $animation_duration_setting ) ) {
				if ( $animation_duration_setting <= 500 ) {
					$animation_duration_setting = 'fast';
				} elseif ( $animation_duration_setting <= 1000 ) {
					$animation_duration_setting = 'normal';
				} else {
					$animation_duration_setting = 'slow';
				}
			}

			$animation_duration = $animation_duration_map[ $animation_duration_setting ] ?? $animation_duration_map['normal'];
			$animation_delay = isset( $this->settings['zigzag_animation_delay'] ) ? intval( $this->settings['zigzag_animation_delay'] ) : 0;

			$wrapper_classes .= sprintf( ' style="animation-duration: %dms; animation-delay: %dms;"', $animation_duration, $animation_delay );
		}

		if ( 'yes' === $has_border ) {
			$wrapper_classes .= ' has-border';
		}

		$this->widget->add_render_attribute( 'wrapper', [
			'class' => $wrapper_classes,
		] );
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'wrapper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php

			$first_block_direction = $this->settings['first_block_direction'];

			foreach ( $this->settings['block_items'] as $key => $item ) {
				$is_odd = 0 !== $key % 2;

				$item_class = 'elementor-widget-zigzag__item-container ';

				$item_class .= $first_block_direction . ( $is_odd ? '-odd' : '-even' );

				$this->widget->add_render_attribute( 'block-item-' . $key, [
					'class' => $item_class,
				] );
				?>
				<div <?php echo $this->widget->get_render_attribute_string( 'block-item-' . $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php
						$this->render_graphic_element_container( $item );
						$this->render_text_element_container( $item );
					?>
				</div>
				<?php
			} ?>
			</div>
		<?php
	}

	private function render_graphic_element_container( $item ) {
		if ( 'icon' === $item['graphic_element'] ) {
			$this->widget->add_render_attribute( 'graphic_element', 'class', 'elementor-widget-zigzag__graphic-element'
			);
		}
		?>
		<div class="elementor-widget-zigzag__graphic-element-container">
			<div class="elementor-widget-zigzag__image-container">
				<?php if ( 'image' === $item['graphic_element'] && ! empty( $item['graphic_image']['url'] ) ) : ?>
					<div class="elementor-widget-zigzag__graphic-image">
						<?php Group_Control_Image_Size::print_attachment_image_html( $item, 'graphic_image' ); ?>
					</div>
				<?php elseif ( 'icon' === $item['graphic_element'] && ( ! empty( $item['graphic_icon'] ) ) ) : ?>
					<div class="elementor-widget-zigzag__graphic-icon">
						<?php Icons_Manager::render_icon( $item['graphic_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	private function render_text_element_container( $item ) {
		$button_text = $item['button_text'] ?? '';
		$button_link = $item['button_link'] ?? '';
		$button_icon = $item['button_icon'] ?? '';
		$icon_color  = $item['icon_color'] ?? '';

		$button_classnames = 'elementor-widget-zigzag__button';
		$button_hover_animation = $this->settings['button_hover_animation'] ?? '';
		$button_has_border = $this->settings['show_border'];

		$this->widget->add_render_attribute( 'title', [
			'class' => 'elementor-widget-zigzag__title',
		] );

		$this->widget->add_render_attribute( 'description', [
			'class' => 'elementor-widget-zigzag__description',
		] );

		if ( $button_hover_animation ) {
			$button_classnames .= ' elementor-animation-' . $button_hover_animation;
		}

		if ( 'yes' === $button_has_border ) {
			$button_classnames .= ' has-border';
		}

		$this->widget->add_render_attribute( 'button-link', [
			'class' => $button_classnames,
		] );

		if ( ! empty( $button_link ) ) {
			$this->widget->add_link_attributes( 'button-link', $button_link );
		}
		?>
		<div class="elementor-widget-zigzag__text-container">
			<h2 <?php echo $this->widget->get_render_attribute_string( 'title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( $item['title'], 'hello-plus' ); ?></h2>
			<p <?php echo $this->widget->get_render_attribute_string( 'description' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( $item['description'], 'hello-plus' ); ?></p>

			<?php if ( ! empty( $button_text ) ) { ?>
			<div class="elementor-widget-zigzag__button-container">
				<a <?php echo $this->widget->get_render_attribute_string( 'button-link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php Icons_Manager::render_icon( $button_icon, [
						'aria-hidden' => 'true',
						'class' => 'elementor-widget-zigzag__button-icon'
					] ); ?>
					<?php echo esc_html( $button_text ); ?>
				</a>
			</div>
			<?php } ?>
		</div>
		<?php
	}
}


















































































