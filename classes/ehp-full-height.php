<?php

namespace HelloPlus\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	Widget_Base
};

use HelloPlus\Classes\{
	Widget_Utils,
};

class Ehp_Full_Height {
	private $context = [];
	private ?Widget_Base $widget = null;

	private $widget_settings = [];

	public function set_context( array $context ) {
		$this->context = $context;
	}

	public function add_full_height_attributes() {
		$this->widget_settings = $this->widget->get_settings_for_display();

		$layout_full_height_controls = $this->widget_settings['box_full_screen_height_controls'] ?? '';

		if ( ! empty( $layout_full_height_controls ) ) {
			$full_height_classnames = [ 'is-full-height' ];

			foreach ( $layout_full_height_controls as $breakpoint ) {
				$full_height_classnames[] = 'is-full-height-' . $breakpoint;
			}

			$this->widget->add_render_attribute( 'layout', [
				'class' => $full_height_classnames,
			] );
		}
	}

	public function add_style_controls() {
		$this->widget->add_control(
			'box_full_screen_height',
			[
				'label' => esc_html__( 'Full Screen Height', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'default' => '',
				'tablet_default' => '',
				'mobile_default' => '',
				'separator' => 'before',
			]
		);

		$configured_breakpoints = Widget_Utils::get_configured_breakpoints();

		$this->widget->add_control(
			'box_full_screen_height_controls',
			[
				'label' => esc_html__( 'Apply Full Screen Height on', 'hello-plus' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $configured_breakpoints['devices_options'],
				'default' => $configured_breakpoints['active_devices'],
				'condition' => [
					'box_full_screen_height' => 'yes',
				],
			]
		);
	}

	public function __construct( Widget_Base $widget, $context = [] ) {
		$this->widget = $widget;
		$this->context = $context;
	}
}
