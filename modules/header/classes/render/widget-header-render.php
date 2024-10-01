<?php

namespace HelloPlus\Modules\Header\Classes\Render;

use HelloPlus\Modules\Header\Widgets\Header;

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

        $this->widget->add_render_attribute( 'layout', [
			'class' => $layout_classnames,
		] );
        ?>
		<div <?php echo $this->widget->get_render_attribute_string( 'layout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			Header
		</div>
		<?php
	}
}
