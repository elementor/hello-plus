<?php

namespace HelloPlus\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\{
	Controls_Manager,
	Widget_Base
};

use HelloPlus\Modules\Content\Classes\{
	Choose_Img_Control,
};

class Ehp_Column_Structure {
	private $context = [];
	private ?Widget_Base $widget = null;
	private array $condition = [];
	private string $separator = '';

	public function set_context( array $context ) {
		$this->context = $context;
	}

	public function add_column_structure_attributes() {
		$widget_settings = $this->widget->get_settings_for_display();

		if ( empty( $widget_settings['layout_column_structure'] ) ) {
			return;
		}

		$this->widget->add_render_attribute( $this->context['render_attribute'], [
			'class' => [
				'has-column-structure-' . $widget_settings['layout_column_structure'],
				'yes' === $widget_settings['layout_reverse_structure'] ? 'is-reverse' : '',
			],
		] );
	}

	public function add_style_controls() {
		$this->widget->add_control(
			'layout_column_structure',
			[
				'label' => esc_html__( 'Column Structure', 'hello-plus' ),
				'type' => Choose_Img_Control::CONTROL_NAME,
				'default' => '50-50',
				'label_block' => true,
				'columns' => 2,
				'toggle' => false,
				'options' => [
					'50-50' => [
						'title' => esc_html__( '50:50', 'hello-plus' ),
						'image' => HELLOPLUS_IMAGES_URL . 'column-50-50.svg',
						'hover_image' => true,
					],
					'33-66' => [
						'title' => esc_html__( '33:66', 'hello-plus' ),
						'image' => HELLOPLUS_IMAGES_URL . 'column-33-66.svg',
						'hover_image' => true,
					],
					'25-75' => [
						'title' => esc_html__( '25:75', 'hello-plus' ),
						'image' => HELLOPLUS_IMAGES_URL . 'column-25-75.svg',
						'hover_image' => true,
					],
				],
				'frontend_available' => true,
				'separator' => $this->separator,
				'condition' => $this->condition,
			]
		);

		$this->widget->add_control(
			'layout_reverse_structure',
			[
				'label' => esc_html__( 'Reverse Structure', 'hello-plus' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'hello-plus' ),
				'label_off' => esc_html__( 'No', 'hello-plus' ),
				'return_value' => 'yes',
				'frontend_available' => true,
				'default' => '',
				'condition' => array_merge( $this->condition, [
					'layout_column_structure!' => '50-50',
				] ),
			]
		);
	}

	public function __construct( Widget_Base $widget, $context = [] ) {
		$this->widget = $widget;
		$this->context = $context;
		$this->condition = $this->context['condition'] ?? [];
		$this->separator = $this->context['separator'] ?? '';
	}
}
