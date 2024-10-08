<?php

namespace HelloPlus\Modules\TemplateParts\Classes\Render;

use HelloPlus\Modules\TemplateParts\Widgets\Header;

class Widget_Header_Render {
	protected Header $widget;
	const LAYOUT_CLASSNAME = 'ehp-header';

	protected array $settings;

	public function __construct( Header $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
		$layout_classnames = self::LAYOUT_CLASSNAME;
		$custom_classes = $this->settings['advanced_custom_css_classes'] ?? '';

		if ( $custom_classes ) {
			$layout_classnames .= ' ' . $custom_classes;
		}

		$this->widget->add_render_attribute( 'layout', [
			'id' => $this->settings['advanced_custom_css_id'],
			'class' => $layout_classnames,
		] );
		?>
		<div <?php echo $this->widget->get_render_attribute_string( 'layout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			Header
		</div>
		<?php
	}
}
