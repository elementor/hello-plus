<?php
namespace HelloPlus\Modules\TemplateParts\Classes\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Group_Control_Image_Size,
	Icons_Manager,
	Utils
};

use HelloPlus\Modules\TemplateParts\Widgets\Ehp_Flex_Footer;

use HelloPlus\Classes\{
	Ehp_Shapes,
	Widget_Utils,
};

/**
 * class Widget_Flex_Footer_Render
 */
class Widget_Flex_Footer_Render {
	protected Ehp_Flex_Footer $widget;
	const LAYOUT_CLASSNAME = 'ehp-flex-footer';

	protected array $settings;

	protected int $nav_menu_index = 1;

	public function render(): void {
		$layout_classnames = self::LAYOUT_CLASSNAME;
		$box_border = $this->settings['footer_box_border'] ?? '';

		if ( 'yes' === $box_border ) {
			$layout_classnames .= ' has-box-border';
		}

		$render_attributes = [
			'class' => $layout_classnames,
		];

		$this->widget->add_render_attribute( 'layout', $render_attributes );

		// $this->widget->maybe_add_advanced_attributes();

		$this->widget->add_render_attribute( 'row', 'class', self::LAYOUT_CLASSNAME . '__row' );

		?>
		<footer <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			Flex Footer
		</footer>
		<?php
	}

	public function get_and_advance_nav_menu_index(): int {
		return $this->nav_menu_index++;
	}

	public function __construct( Ehp_Flex_Footer $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}
}
