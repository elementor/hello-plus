<?php

namespace HelloPlus\Modules\TemplateParts\Widgets;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;
use HelloPlus\Modules\TemplateParts\Classes\Traits\Shared_Header_Traits;

abstract class Abstract_Ehp_Widget extends Widget_Base {
	use Shared_Header_Traits;

	abstract public function get_advanced_tab_id();

	protected function add_advanced_tab() {
		$advanced_tab_id = $this->get_advanced_tab_id();

		Controls_Manager::add_tab(
			$advanced_tab_id,
			esc_html__( 'Advanced', 'hello-plus' )
		);

		$this->start_controls_section(
			'advanced_custom_controls_section',
			[
				'label' => esc_html__( 'CSS', 'hello-plus' ),
				'tab' => $advanced_tab_id,
			]
		);

		$this->add_control(
			'advanced_custom_css_id',
			[
				'label' => esc_html__( 'CSS ID', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'ai' => [
					'active' => false,
				],
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'hello-plus' ),
				'style_transfer' => false,
			]
		);

		$this->add_control(
			'advanced_custom_css_classes',
			[
				'label' => esc_html__( 'CSS Classes', 'hello-plus' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'ai' => [
					'active' => false,
				],
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'hello-plus' ),
			]
		);

		$this->end_controls_section();

		Plugin::$instance->controls_manager->add_custom_css_controls( $this, $advanced_tab_id );

		Plugin::$instance->controls_manager->add_custom_attributes_controls( $this, $advanced_tab_id );
	}
}
