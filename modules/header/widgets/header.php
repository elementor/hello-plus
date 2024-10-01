<?php

namespace HelloPlus\Modules\Header\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

use HelloPlus\Modules\Header\Classes\Render\Widget_Header_Render;
use HelloPlus\Modules\Theme\Module as Theme_Module;

use ElementorPro\Modules\ThemeBuilder\Classes\Control_Media_Preview;
use ElementorPro\Plugin;

class Header extends Widget_Base {
	public function get_name(): string {
		return 'header';
	}

	public function get_title(): string {
		return esc_html__( 'Header', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLO_PLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'header' ];
	}

	public function get_icon(): string {
		return 'eicon-single-page';
	}

	public function get_style_depends(): array {
		return [ 'hello-plus-header' ];
	}

	protected function render(): void {
		$render_strategy = new Widget_Header_Render( $this );

		$render_strategy->render();
	}

	protected function register_controls() {
		$this->add_content_section();
		$this->add_style_section();
		$this->add_advanced_section();
	}

	protected function add_content_section() {
		$this->add_site_logo_section();
	}

	protected function add_style_section() {
		// controls here
	}

	protected function add_advanced_section() {
		// controls here
	}

	protected function add_site_logo_section() {
		$this->start_controls_section(
			'site_logo_label',
			[
				'label' => esc_html__( 'Site Logo', 'hello-plus' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'site_logo_brand_select',
			[
				'label' => esc_html__( 'Brand', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'logo' => 'Site Logo',
					'title' => 'Site Title',
				],
				'default' => 'logo',
				'tablet_default' => 'logo',
				'mobile_default' => 'logo',
			]
		);

		$this->add_control(
			'site_logo_image',
			[
				'label' => esc_html__( 'Site Logo', 'hello-plus' ),
				'type' => Control_Media_Preview::CONTROL_TYPE,
				'src' => $this->get_site_logo(),
				'dynamic' => [
					'default' => Plugin::elementor()->dynamic_tags->tag_data_to_tag_text( null, 'site-logo' ),
				],
				'condition' => [
					'site_logo_brand_select' => 'logo',
				]
			],
			[
				'recursive' => true,
			]
		);

		$this->add_control(
			'change_logo_cta',
			[
				'type' => Controls_Manager::BUTTON,
				'label_block' => true,
				'show_label' => false,
				'button_type' => 'default elementor-button-center',
				'text' => esc_html__( 'Change Site Logo', 'hello-plus' ),
				'event' => 'elementorProSiteLogo:change',
				'condition' => [
					'site_logo_brand_select' => 'logo',
				]
			],
			[
				'position' => [
					'of' => 'image',
					'type' => 'control',
					'at' => 'after',
				],
			]
		);

		$this->add_control(
			'site_logo_title_alert',
			[
				'type' => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'content' => esc_html__( 'Go to', 'hello-plus' ) . ' <a href="">' . esc_html__( 'Site Identity > Site Description', 'hello-plus' ) . '</a>' . esc_html__( ' to edit the Site Title', 'hello-plus' ),
				'condition' => [
					'site_logo_brand_select' => 'title',
				]
			]
		);

		$this->add_control(
			'site_logo_title_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'hello-plus' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
				'condition' => [
					'site_logo_brand_select' => 'title',
				]
			]
		);

		$this->end_controls_section();

	}

	private function get_site_logo(): string {
		$site_logo = Plugin::elementor()->dynamic_tags->get_tag_data_content( null, 'site-logo' );
		return $site_logo['url'] ?? Utils::get_placeholder_image_src();
	}
}