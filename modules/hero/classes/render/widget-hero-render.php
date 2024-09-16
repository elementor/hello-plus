<?php

namespace HelloPlus\Modules\Hero\Classes\Render;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;

use HelloPlus\Modules\Hero\Widgets\Hero;


class Widget_Hero_Render {
	protected Hero $widget;

	protected array $settings;

	public function __construct( Hero $widget ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
	}

	public function render(): void {
        $layout_classnames = 'e-zigzag';

        $this->widget->add_render_attribute( 'layout', [
            'class' => $layout_classnames,
        ] );
        ?>
        <div <?php echo $this->widget->get_render_attribute_string( 'layout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
            Hero
        </div>
        <?php
    }
}
