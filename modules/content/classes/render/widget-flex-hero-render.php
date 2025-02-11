<?php
namespace HelloPlus\Modules\Content\Classes\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Utils;
use HelloPlus\Modules\Content\Widgets\Flex_Hero;
use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Image,
	Ehp_Shapes,
};

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

	public function maybe_add_layout_responsive_classes( &$layout_classes ) {
		$layout_image_position_mobile = $this->settings['layout_image_position_mobile'];
		$layout_image_position_tablet = $this->settings['layout_image_position_tablet'];

		if ( ! empty( $layout_image_position_mobile ) ) {
			$layout_classes[] = 'has-image-position-sm-' . $layout_image_position_mobile;
		}

		if ( ! empty( $layout_image_position_tablet ) ) {
			$layout_classes[] = 'has-image-position-md-' . $layout_image_position_tablet;
		}
	}

	public function render(): void {
		$layout_classnames = [ self::LAYOUT_CLASSNAME ];
		$layout_full_height_controls = $this->settings['box_full_screen_height_controls'] ?? '';
		$layout_preset = $this->settings['layout_preset'];
		$image_stretch = $this->settings['image_stretch'];
		$layout_image_position = $this->settings['layout_image_position'];
		$has_border = $this->settings['show_box_border'];
		// $box_shape = $this->settings['box_shape'];
		// $box_shape_mobile = $this->settings['box_shape_mobile'];
		// $box_shape_tablet = $this->settings['box_shape_tablet'];

		if ( ! empty( $layout_full_height_controls ) ) {
			foreach ( $layout_full_height_controls as $breakpoint ) {
				$layout_classnames[] = 'is-full-height-' . $breakpoint;
			}
		}

		if ( ! empty( $layout_preset ) ) {
			$layout_classnames[] = 'has-layout-preset-' . $layout_preset;
		}

		if ( 'yes' === $image_stretch ) {
			$layout_classnames[] = 'has-image-stretch';
		}

		if ( 'yes' === $has_border ) {
			$layout_classnames[] = 'has-border';
		}

		if ( ! empty( $layout_image_position ) ) {
			$layout_classnames[] = 'has-image-position-' . $layout_image_position;

			// pass by reference:
			$this->maybe_add_layout_responsive_classes( $layout_classnames );
		}

		$shapes = new Ehp_Shapes( $this->widget, [
			'container_type' => 'box',
			'widget_name' => 'flex-hero',
			'render_attribute' => 'layout',
		] );
		$shapes->render_shape_classnames();

		// if ( ! empty( $box_shape ) ) {
		// 	$layout_classnames[] = 'has-shape-' . $box_shape;

		// 	if ( ! empty( $box_shape_mobile ) ) {
		// 		$layout_classnames[] = 'has-shape-sm-' . $box_shape_mobile;
		// 	}

		// 	if ( ! empty( $box_shape_tablet ) ) {
		// 		$layout_classnames[] = 'has-shape-md-' . $box_shape_tablet;
		// 	}
		// }

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<?php
				$this->render_content_container();
				$this->render_image_container();
			?>
		</div>
		<?php
	}

	public function render_content_container() {
		?>
			<div class="ehp-flex-hero__overlay"></div>
			<div class="ehp-flex-hero__content-container">
				<?php
					$this->render_text_container();
					$this->render_ctas_container();
				?>
			</div>
		<?php
	}

	public function render_text_container() {
		$this->maybe_render_text_html( 'intro_text', 'ehp-flex-hero__intro', $this->settings['intro_text'], $this->settings['intro_tag'] );
		$this->maybe_render_text_html( 'heading_text', 'ehp-flex-hero__heading', $this->settings['heading_text'], $this->settings['heading_tag'] );
		$this->maybe_render_text_html( 'subheading_text', 'ehp-flex-hero__subheading', $this->settings['subheading_text'], $this->settings['subheading_tag'] );
	}

	public function maybe_render_text_html( $render_key, $css_class, $settings_text, $settings_tag ) {
		if ( '' !== $settings_text ) {
			$this->widget->add_render_attribute( $render_key, 'class', $css_class );

			$element = wp_kses_post( $settings_text );

			$element_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $settings_tag ), $this->widget->get_render_attribute_string( $render_key ), $element );

			// PHPCS - the variable $element_html holds safe data.
			echo $element_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	protected function render_ctas_container() {
		$primary_cta_button_text = $this->settings['primary_cta_button_text'];
		$secondary_cta_button_text = $this->settings['secondary_cta_button_text'];
		$has_primary_button = ! empty( $primary_cta_button_text );
		$has_secondary_button = ! empty( $secondary_cta_button_text );

		$ctas_container_classnames = [ self::CTAS_CONTAINER_CLASSNAME ];

		$this->widget->add_render_attribute( 'ctas-container', [
			'class' => $ctas_container_classnames,
		] );
		?>
			<div <?php $this->widget->print_render_attribute_string( 'ctas-container' ); ?>>
				<?php if ( $has_primary_button ) {
					$this->render_button( 'primary' );
				} ?>
				<?php if ( $has_secondary_button ) {
					$this->render_button( 'secondary' );
				} ?>
			</div>
		<?php
	}

	protected function render_button( $type ) {
		$button = new Ehp_Button( $this->widget, [
			'type' => $type,
			'widget_name' => 'flex-hero',
		] );
		$button->render();
	}

	protected function render_image_container() {
		$image = new Ehp_Image( $this->widget, [
			'widget_name' => 'flex-hero',
		] );
		$image->render();
	}
}
