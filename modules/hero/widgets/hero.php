<?php

namespace HelloPlus\Modules\Hero\Widgets;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

use HelloPlus\Modules\Hero\Classes\Render\Widget_Hero_Render;
use HelloPlus\Modules\Theme\Module as Theme_Module;

class Hero extends Widget_Base {

	public function get_name(): string {
		return 'hero';
	}

	public function get_title(): string {
		return esc_html__( 'Hero', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLO_PLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'hero' ];
	}

	public function get_icon(): string {
		return 'eicon-single-page';
	}

	public function get_style_depends(): array {
		return [ 'hello-plus-hero' ];
	}

	protected function render(): void {
		$render_strategy = new Widget_Hero_Render( $this );

		$render_strategy->render();
	}

	protected function register_controls() {
		$this->add_content_section();
		// $this->add_style_section();
	}

    protected function add_content_section() {
        $this->start_controls_section(
			'zigzags_content_section',
			[
				'label' => esc_html__( 'Zigzags', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'first_zigzag_direction',
			[
				'label' => esc_html__( 'Align First Graphic', 'hello-plus' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'row' => [
						'title' => esc_html__( 'Left', 'hello-plus' ),
						'icon' => 'eicon-order-start',
					],
					'row-reverse' => [
						'title' => esc_html__( 'Right', 'hello-plus' ),
						'icon' => 'eicon-order-end',
					],
				],
				'default' => 'row',
			]
		);

        $this->end_controls_section();
    }

    protected function add_style_section() {
        // style section here
    }
}
