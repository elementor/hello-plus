<?php
namespace HelloPlus\Modules\Content\Classes\Render;

use HelloPlus\Classes\Widget_Utils;
use HelloPlus\Modules\Content\Widgets\CTA;
use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Column_Structure,
	Ehp_Full_Height,
	Ehp_Image,
	Ehp_Shapes,
};

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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

		$show_image = 'storytelling' === $this->settings['layout_preset'] || 'showcase' === $this->settings['layout_preset'];
		$image_stretch = $this->settings['image_stretch'];
		$has_border = $this->settings['show_box_border'];

		if ( 'yes' === $image_stretch ) {
			$layout_classnames[] = 'has-image-stretch';
		}

		if ( 'yes' === $has_border ) {
			$layout_classnames[] = 'has-border';
		}

		$shapes = new Ehp_Shapes( $this->widget, [
			'container_prefix' => 'box',
			'render_attribute' => 'layout',
			'widget_name' => $this->widget->get_name(),
		] );
		$shapes->add_shape_attributes();

		$ehp_full_height = new Ehp_Full_Height( $this->widget );
		$ehp_full_height->add_full_height_attributes();

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );

		$elements_container_classnames = [
			self::LAYOUT_CLASSNAME . '__elements-container',
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

		$column_structure = new Ehp_Column_Structure( $this->widget, [
			'render_attribute' => 'elements-container',
		] );
		$column_structure->add_column_structure_attributes();

		$this->widget->add_render_attribute( 'elements-container', [
			'class' => $elements_container_classnames,
		] );

		$this->widget->add_render_attribute( 'overlay', 'class', self::LAYOUT_CLASSNAME . '__overlay' );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			<div <?php $this->widget->print_render_attribute_string( 'overlay' ); ?>></div>
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

	protected function render_image_container() {
		$image = new Ehp_Image( $this->widget, [
			'widget_name' => $this->widget->get_name(),
		] );
		$image->render();
	}

	protected function render_text_container() {
		$heading_classname = self::LAYOUT_CLASSNAME . '__heading';
		$description_classname = self::LAYOUT_CLASSNAME . '__description';

		$this->widget->add_render_attribute( 'text-container', [
			'class' => self::LAYOUT_CLASSNAME . '__text-container',
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'text-container' ); ?>>
			<?php
			Widget_Utils::maybe_render_text_html( $this->widget, 'heading_text', $heading_classname, $this->settings['heading_text'], $this->settings['heading_tag'] );
			Widget_Utils::maybe_render_text_html( $this->widget, 'description_text', $description_classname, $this->settings['description_text'], $this->settings['description_tag'] );

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

		$buttons_wrapper_classnames = [ self::LAYOUT_CLASSNAME . '__buttons-wrapper' ];

		$this->widget->add_render_attribute( 'buttons-wrapper', [
			'class' => $buttons_wrapper_classnames,
		] );

		$ctas_container_wrapper = [ self::LAYOUT_CLASSNAME . '__ctas-container' ];

		if ( $buttons_width ) {
			$ctas_container_wrapper[] = 'has-cta-width-' . $buttons_width;

			if ( $buttons_width_tablet ) {
				$ctas_container_wrapper[] = 'has-cta-width-md-' . $buttons_width_tablet;
			}

			if ( $buttons_width_mobile ) {
				$ctas_container_wrapper[] = 'has-cta-width-sm-' . $buttons_width_mobile;
			}
		}

		$this->widget->add_render_attribute( 'ctas-container', [
			'class' => $ctas_container_wrapper,
		] );
		?>
			<div <?php $this->widget->print_render_attribute_string( 'ctas-container' ); ?>>
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
			'widget_name' => $this->widget->get_name(),
		] );
		$button->render();
	}
}
