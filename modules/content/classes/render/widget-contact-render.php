<?php
namespace HelloPlus\Modules\Content\Classes\Render;

use HelloPlus\Modules\Content\Widgets\Contact;
use HelloPlus\Classes\{
	Ehp_Button,
	Ehp_Image,
	Ehp_Shapes,
};

use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Contact_Render {
	protected Contact $widget;
	const LAYOUT_CLASSNAME = 'ehp-contact';

	protected array $settings;

	public function __construct( Contact $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = [
			self::LAYOUT_CLASSNAME,
			// 'has-preset-' . $this->settings['layout_preset'],
		];
		// $layout_full_height_controls = $this->settings['box_full_screen_height_controls'] ?? '';

		$this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( 'layout' ); ?>>
			Contact
		</div>
		<?php
	}
}