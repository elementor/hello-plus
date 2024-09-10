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
		$has_border = $this->settings['show_widget_border'];

		if ( 'yes' === $has_border ) {
			$layout_classnames .= ' has-border';
		}

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'layout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
			$remainder = 'row' === $this->settings['first_block_direction'] ? 0 : 1;

			foreach ( $this->settings['block_items'] as $key => $item ) {
				$is_odd = $remainder !== $key % 2;

				$item_class = 'e-zigzag__item-container ';

				$item_class .= 'row' . ( $is_odd ? '-odd' : '-even' );

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
		?>
		<div class="e-zigzag__graphic-element-container">
			<?php if ( 'image' === $item['graphic_element'] && ! empty( $item['graphic_image']['url'] ) ) : ?>
				<?php Group_Control_Image_Size::print_attachment_image_html( $item, 'graphic_image' ); ?>
			<?php elseif ( 'icon' === $item['graphic_element'] && ( ! empty( $item['graphic_icon'] ) ) ) : ?>
				<?php Icons_Manager::render_icon( $item['graphic_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	private function render_text_element_container( $item ) {
		$button_text = $item['button_text'] ?? '';
		$button_link = $item['button_link'] ?? '';
		$button_icon = $item['button_icon'] ?? '';
		$icon_color  = $item['icon_color'] ?? '';

		$button_classnames = 'e-zigzag__button';
		$button_hover_animation = $this->settings['button_hover_animation'] ?? '';
		$button_has_border = $this->settings['show_border'];

		$this->widget->add_render_attribute( 'title', [
			'class' => 'e-zigzag__title',
		] );

		$this->widget->add_render_attribute( 'description', [
			'class' => 'e-zigzag__description',
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
		<div class="e-zigzag__text-container">
			<h2 <?php echo $this->widget->get_render_attribute_string( 'title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $item['title'] ); ?></h2>
			<p <?php echo $this->widget->get_render_attribute_string( 'description' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $item['description'] ); ?></p>

			<?php if ( ! empty( $button_text ) ) { ?>
				<div class="e-zigzag__button-container">
					<a <?php echo $this->widget->get_render_attribute_string( 'button-link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php Icons_Manager::render_icon( $button_icon, [
							'aria-hidden' => 'true',
							'class' => 'e-zigzag__button-icon'
						] ); ?>
						<?php echo esc_html( $button_text ); ?>
					</a>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}
