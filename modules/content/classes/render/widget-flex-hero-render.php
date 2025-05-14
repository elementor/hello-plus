<?php
namespace HelloPlus\Modules\Content\Classes\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Classes\Widget_Utils;
use HelloPlus\Modules\Content\Widgets\Flex_Hero;
use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Column_Structure,
	Ehp_Full_Height,
	Ehp_Image,
	Ehp_Shapes,
};

class Widget_Flex_Hero_Render {
	protected Flex_Hero $widget;
	const LAYOUT_CLASSNAME = 'ehp-flex-hero';

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
		$layout_preset = $this->settings['layout_preset'];
		$image_stretch = $this->settings['image_stretch'];
		$layout_image_position = $this->settings['layout_image_position'];
		$has_border = $this->settings['show_box_border'];

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
			'container_prefix' => 'box',
			'render_attribute' => 'layout',
			'widget_name' => $this->widget->get_name(),
		] );
		$shapes->add_shape_attributes();

		$ehp_full_height = new Ehp_Full_Height( $this->widget );
		$ehp_full_height->add_full_height_attributes();

		$column_structure = new Ehp_Column_Structure( $this->widget, [
			'render_attribute' => 'layout',
		] );
		$column_structure->add_column_structure_attributes();

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
		$this->widget->add_render_attribute( 'content-container', 'class', self::LAYOUT_CLASSNAME . '__content-container' );
		$this->widget->add_render_attribute( 'overlay', 'class', self::LAYOUT_CLASSNAME . '__overlay' );
		?>
			<div <?php $this->widget->print_render_attribute_string( 'overlay' ); ?>></div>
			<div <?php $this->widget->print_render_attribute_string( 'content-container' ); ?>>
				<?php
					$this->render_text_container();
					$this->render_ctas_container();
				?>
			</div>
		<?php
	}

	public function render_text_container() {
		$intro_classname = self::LAYOUT_CLASSNAME . '__intro';
		$heading_classname = self::LAYOUT_CLASSNAME . '__heading';
		$subheading_classname = self::LAYOUT_CLASSNAME . '__subheading';

		Widget_Utils::maybe_render_text_html( $this->widget, 'intro_text', $intro_classname, $this->settings['intro_text'], $this->settings['intro_tag'] );
		Widget_Utils::maybe_render_text_html( $this->widget, 'heading_text', $heading_classname, $this->settings['heading_text'], $this->settings['heading_tag'] );
		Widget_Utils::maybe_render_text_html( $this->widget, 'subheading_text', $subheading_classname, $this->settings['subheading_text'], $this->settings['subheading_tag'] );
	}

	protected function render_ctas_container() {
		$primary_cta_button_text = $this->settings['primary_cta_button_text'];
		$secondary_cta_button_text = $this->settings['secondary_cta_button_text'];
		$has_primary_button = ! empty( $primary_cta_button_text );
		$has_secondary_button = ! empty( $secondary_cta_button_text );

		$ctas_container_classnames = [ self::LAYOUT_CLASSNAME . '__ctas-container' ];

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
			'widget_name' => $this->widget->get_name(),
		] );
		$button->render();
	}

	protected function render_image_container() {
		$this->widget->add_render_attribute( 'image-wrapper', 'class', self::LAYOUT_CLASSNAME . '__image-wrapper' );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'image-wrapper' ); ?>>
		<?php
			$image = new Ehp_Image( $this->widget, [
				'widget_name' => $this->widget->get_name(),
			] );
			$image->render();
		?>
		</div>
		<?php
	}
}
