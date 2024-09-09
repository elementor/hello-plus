<?php

namespace HelloPlus\Modules\Content\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use HelloPlus\Modules\Content\Widgets\Classes\Base\Widget_Zig_Zag_Base;
use HelloPlus\Modules\Theme\Module as Theme_Module;

class Zig_Zag extends Widget_Zig_Zag_Base {

	public function get_name(): string {
		return 'zigzag';
	}

	public function get_title(): string {
		return esc_html__( 'Zig-Zag', 'hello-plus' );
	}

	public function get_categories(): array {
		return [ Theme_Module::HELLO_PLUS_EDITOR_CATEGORY_SLUG ];
	}

	public function get_keywords(): array {
		return [ 'zigzag', 'content' ];
	}

	public function get_style_depends(): array {
		return [ 'hello-plus-content' ];
	}

	public function render(): void {
		parent::render();
	}
}

