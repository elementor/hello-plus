<?php

namespace HelloPlus\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
abstract class Render_Base implements Renderable {
	protected Widget_Base $widget;

	protected array $settings;

	protected string $layout_classname;


	abstract public function render(): void;

	protected function get_class_name( string $suffix ): string {
		return $this->layout_classname . $suffix;
	}

	public function __construct( Widget_Base $widget, string $layout_classname ) {
		$this->widget = $widget;
		$this->settings = $widget->get_settings_for_display();
		$this->layout_classname = $layout_classname;
	}
}
