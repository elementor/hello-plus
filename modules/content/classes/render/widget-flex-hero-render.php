<?php
namespace HelloPlus\Modules\Content\Classes\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Utils;
use HelloPlus\Modules\Content\Widgets\Flex_Hero;

class Widget_Flex_Hero_Render {
	protected Flex_Hero $widget;
	const LAYOUT_CLASSNAME = 'ehp-flex-hero';
	const CTAS_CONTAINER_CLASSNAME = 'ehp-flex-hero__ctas-container';
	const BUTTON_CLASSNAME = 'ehp-flex-hero__button';
	const IMAGE_CLASSNAME = 'ehp-flex-hero__image';

	protected array $settings;

	public function __construct( Flex_Hero $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = self::LAYOUT_CLASSNAME;
		$layout_full_height_controls = $this->settings['box_full_screen_height_controls'] ?? '';

		$layout_preset = $this->settings['layout_preset'];

		if ( ! empty( $layout_full_height_controls ) ) {
			foreach ( $layout_full_height_controls as $breakpoint ) {
				$layout_classnames .= ' is-full-height-' . $breakpoint;
			}
		}

		if ( ! empty( $layout_preset ) ) {
			$layout_classnames .= ' has-layout-preset-' . $layout_preset;
		}

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<?php
				$this->render_content_container();
				$this->render_image();
			?>
		</div>
		<?php
	}

	public function render_content_container() {
		?>
			<div class="ehp-flex-hero__content-container">
				<?php
					$this->render_text_container();
					$this->render_ctas_container();
				?>
			</div>
		<?php
	}

	public function render_text_container() {
		$intro_text = $this->settings['intro_text'];
		$intro_tag = $this->settings['intro_tag'];
		$has_intro = '' !== $intro_text;

		$heading_text = $this->settings['heading_text'];
		$heading_tag = $this->settings['heading_tag'];
		$has_heading = '' !== $heading_text;

		$subheading_text = $this->settings['subheading_text'];
		$subheading_tag = $this->settings['subheading_tag'];
		$has_subheading = '' !== $subheading_text;
		?>
		<?php if ( $has_intro ) {
			$intro_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $intro_tag ), 'class="ehp-flex-hero__intro"', esc_html( $intro_text ) );
			// Escaped above
			Utils::print_unescaped_internal_string( $intro_output );
		} ?>
		<?php if ( $has_heading ) {
			$heading_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $heading_tag ), 'class="ehp-flex-hero__heading"', esc_html( $heading_text ) );
			// Escaped above
			Utils::print_unescaped_internal_string( $heading_output );
		} ?>
		<?php if ( $has_subheading ) {
			$subheading_output = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $subheading_tag ), 'class="ehp-flex-hero__subheading"', esc_html( $subheading_text ) );
			// Escaped above
			Utils::print_unescaped_internal_string( $subheading_output );
		} ?>
		<?php
	}

	protected function render_ctas_container() {
		$primary_cta_button_text = $this->settings['primary_cta_button_text'];
		$secondary_cta_button_text = $this->settings['secondary_cta_button_text'];
		$has_primary_button = ! empty( $primary_cta_button_text );
		$has_secondary_button = ! empty( $secondary_cta_button_text );

		$ctas_container_classnames = self::CTAS_CONTAINER_CLASSNAME;

		$this->widget->add_render_attribute( 'ctas-container', [
			'class' => $ctas_container_classnames,
		] );
		?>
			<div <?php $this->widget->print_render_attribute_string( 'ctas-container' ); ?>>
				<div class="ehp-cta__buttons-wrapper">
					<?php if ( $has_primary_button ) {
						$this->render_button( 'primary' );
					} ?>
					<?php if ( $has_secondary_button ) {
						$this->render_button( 'secondary' );
					} ?>
				</div>
			</div>
		<?php
	}

	protected function render_button( $type ) {
		$button_text = $this->settings[ $type . '_cta_button_text' ];
		$button_link = $this->settings[ $type . '_cta_button_link' ];
		$button_icon = $this->settings[ $type . '_cta_button_icon' ];
		$button_hover_animation = $this->settings[ $type . '_button_hover_animation' ] ?? '';
		$button_has_border = $this->settings[ $type . '_show_button_border' ];
		$button_corner_shape = $this->settings[ $type . '_button_shape' ] ?? '';
		$button_type = $this->settings[ $type . '_button_type' ] ?? '';
		$button_classnames = self::BUTTON_CLASSNAME;

		$button_classnames .= ' ehp-flex-hero__button--' . $type;

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

		$this->widget->add_render_attribute( $type . '-button', [
			'class' => $button_classnames,
		] );

		if ( ! empty( $button_link ) ) {
			$this->widget->add_link_attributes( $type . '-button', $button_link );
		}

		?>
		<a <?php $this->widget->print_render_attribute_string( $type . '-button' ); ?>>
			<?php
				Icons_Manager::render_icon( $button_icon,
					[
						'aria-hidden' => 'true',
						'class' => 'ehp-flex-hero__button-icon',
					]
				);
			?>
			<?php echo esc_html( $button_text ); ?>
		</a>
		<?php
	}

	public function render_image() {
		$image = $this->settings['image'];
		$has_image = ! empty( $image['url'] );
		$image_classnames = self::IMAGE_CLASSNAME;

		$this->widget->add_render_attribute( 'image', [
			'class' => $image_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'image' ); ?>>
			<?php
			if ( $has_image ) {
				Group_Control_Image_Size::print_attachment_image_html( $this->settings, 'image' );
			}
			?>
		</div>
		<?php
	}
}