<?php
/**
 * Plugin Name: Hello Plus
 * Description: Puts the PLUS in Elementor's Hello+ themes
 * Plugin URI: https://elementor.com
 * Author: Elementor.com
 * Version: 0.0.1
 * Author URI: https://elementor.com
 *
 * Text Domain: hello-plus
 *
 * @package HelloPlus
 *
 * Hello+ is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Hello+ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

use HelloPlus\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_PLUS_VERSION', '0.0.1' );

define( 'HELLO_PLUS__FILE__', __FILE__ );
define( 'HELLO_PLUS_PLUGIN_BASE', plugin_basename( HELLO_PLUS__FILE__ ) );
define( 'HELLO_PLUS_PATH', plugin_dir_path( HELLO_PLUS__FILE__ ) );
define( 'HELLO_PLUS_URL', plugins_url( '', HELLO_PLUS__FILE__ ) );
define( 'HELLO_PLUS_ASSETS_PATH', HELLO_PLUS_PATH . '/build/' );
define( 'HELLO_PLUS_ASSETS_URL', HELLO_PLUS_URL . '/build/' );
define( 'HELLO_PLUS_SCRIPTS_PATH', HELLO_PLUS_ASSETS_PATH . 'js/' );
define( 'HELLO_PLUS_SCRIPTS_URL', HELLO_PLUS_ASSETS_URL . 'js/' );
define( 'HELLO_PLUS_STYLE_PATH', HELLO_PLUS_ASSETS_PATH . 'css/' );
define( 'HELLO_PLUS_STYLE_URL', HELLO_PLUS_ASSETS_URL . 'css/' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

// Init the Theme class
require HELLO_PLUS_PATH . '/plugin.php';

Plugin::instance();
