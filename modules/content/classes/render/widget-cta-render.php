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

	protected array $settings;

	public function __construct( CTA $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = [
			self::LAYOUT_CLASSNAME,
			'has-preset-' . $this->settings['layout_preset'],
		];
		$layout_full_height_controls = $this->settings['box_full_screen_height_controls'] ?? '';

		$show_image = 'storytelling' === $this->settings['layout_preset'] || 'showcase' === $this->settings['layout_preset'];
		$image_stretch = $this->settings['image_stretch'];
		$has_border = $this->settings['show_box_border'];

		$box_shape = $this->settings['box_shape'];
		$box_shape_mobile = $this->settings['box_shape_mobile'];
		$box_shape_tablet = $this->settings['box_shape_tablet'];

		if ( ! empty( $layout_full_height_controls ) ) {
			foreach ( $layout_full_height_controls as $breakpoint ) {
				$layout_classnames[] = ' is-full-height-' . $breakpoint;
			}
		}

		if ( 'yes' === $image_stretch ) {
			$layout_classnames[] = 'has-image-stretch';
		}

		if ( 'yes' === $has_border ) {
			$layout_classnames[] = 'has-border';
		}

		if ( ! empty( $box_shape ) ) {
			$layout_classnames[] = 'has-shape-' . $box_shape;

			if ( ! empty( $box_shape_mobile ) ) {
				$layout_classnames[] = 'has-shape-sm-' . $box_shape_mobile;
			}

			if ( ! empty( $box_shape_tablet ) ) {
				$layout_classnames[] = 'has-shape-md-' . $box_shape_tablet;
			}
		}

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );

		$elements_container_classnames = [
			'ehp-cta__elements-container',
		];
		$image_position = $this->settings['image_horizontal_position'];
		$image_position_tablet = $this->settings['image_horizontal_position_tablet'];
		$image_position_mobile = $this->settings['image_horizontal_position_mobile'];

		if ( ! empty( $image_position ) ) {
			$elements_container_classnames[] = 'has-image-position-' . $image_position;

			if ( ! empty( $image_position_tablet ) ) {
				$elements_container_classnames[] = 'has-image-position-md-' . $image_position_tablet;
			}

			if ( ! empty( $image_position_mobile ) ) {
				$elements_container_classnames[] = 'has-image-position-sm-' . $image_position_mobile;
			}
		}

		$this->widget->add_render_attribute( 'elements-container', [
			'class' => $elements_container_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<div class="ehp-cta__overlay"></div>
			<div <?php $this->widget->print_render_attribute_string( 'elements-container' ); ?>>
				<?php
					if ( $show_image ) {
						$this->render_image_container();
					}
					$this->render_text_container();
					
					if ( 'showcase' !== $this->settings['layout_preset'] ) {
						$this->render_ctas_container();
					}
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

		$text_container_classnames = [ 'ehp-cta__text-container' ];

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

			<?php
			if ( 'showcase' === $this->settings['layout_preset'] ) {
				$this->render_ctas_container();
			} ?>
		</div>
		<?php
	}

	protected function render_ctas_container() {
		$buttons_width = $this->settings['cta_width'];
		$buttons_width_tablet = $this->settings['cta_width_tablet'];
		$buttons_width_mobile = $this->settings['cta_width_mobile'];

		$buttons_wrapper_classnames = [ 'ehp-cta__buttons-wrapper' ];

		if ( $buttons_width ) {
			$buttons_wrapper_classnames[] = 'has-cta-width-' . $buttons_width;

			if ( $buttons_width_tablet ) {
				$buttons_wrapper_classnames[] = 'has-cta-width-md-' . $buttons_width_tablet;
			}

			if ( $buttons_width_mobile ) {
				$buttons_wrapper_classnames[] = 'has-cta-width-sm-' . $buttons_width_mobile;
			}
		}

		$this->widget->add_render_attribute( 'buttons-wrapper', [
			'class' => $buttons_wrapper_classnames,
		] );
		?>
			<div class="ehp-cta__ctas-container">
				<div <?php $this->widget->print_render_attribute_string( 'buttons-wrapper' ); ?>>
					<?php if ( ! empty( $this->settings['primary_cta_button_text'] ) ) {
						$this->render_button( 'primary' );
					} ?>
					<?php if ( ! empty( $this->settings['secondary_cta_button_text'] ) ) {
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

	public function get_attachment_image_html_filter( $html ) {
		$image_classnames = 'ehp-flex-hero__img';
		$image_shape = $this->settings['image_shape'];
		$image_shape_mobile = $this->settings['image_shape_mobile'];
		$image_shape_tablet = $this->settings['image_shape_tablet'];

		$has_border = $this->settings['show_image_border'];

		if ( 'yes' === $has_border ) {
			$image_classnames .= ' has-border';
		}

		if ( ! empty( $image_shape ) ) {
			$image_classnames .= ' has-shape-' . $image_shape;

			if ( ! empty( $image_shape_mobile ) ) {
				$image_classnames .= ' has-shape-sm-' . $image_shape_mobile;
			}

			if ( ! empty( $image_shape_tablet ) ) {
				$image_classnames .= ' has-shape-md-' . $image_shape_tablet;
			}
		}

		$html = str_replace( '<img ', '<img class="' . esc_attr( $image_classnames ) . '" ', $html );
		return $html;
	}

	public function render_image_container() {
		$image = $this->settings['image'];
		$has_image = ! empty( $image['url'] );
		$image_wrapper_classnames = 'ehp-cta__image-container';

		$this->widget->add_render_attribute( 'image', [
			'class' => $image_wrapper_classnames,
		] );

		if ( $has_image ) :
			?>
			<div <?php $this->widget->print_render_attribute_string( 'image' ); ?>>
				<?php
					add_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_attachment_image_html_filter' ], 10, 4 );
					Group_Control_Image_Size::print_attachment_image_html( $this->settings, 'image' );
					remove_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'get_attachment_image_html_filter' ], 10 );
				?>
			</div>
			<?php
		endif; //has_image
	}
}
