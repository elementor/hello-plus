<?php

namespace HelloPlus\Modules\Admin\Components;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Admin_Controller {

	public function plugin_row_meta( $plugin_meta, $plugin_file ) {

		if ( HELLOPLUS_PLUGIN_BASE === $plugin_file ) {
			$row_meta = [
				'changelog' => '<a href="#" id="hello-plus-whats-new-link" aria-label="' . esc_attr( esc_html__( 'Changelog', 'hello-plus' ) ) . '" >' . esc_html__( 'Changelog', 'hello-plus' ) . '</a><div id="hello-plus-whats-new"></div>',
			];

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

	public function __construct() {
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
	}
}
