<?php
/**
 * Theme functions and definitions
 *
 * @package HelloPlus
 */

use HelloPlus\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_PLUS_ELEMENTOR_VERSION', '0.0.1' );
define( 'HELLO_PLUS_PATH', get_template_directory() );
define( 'HELLO_PLUS_URL', get_template_directory_uri() );
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
require HELLO_PLUS_PATH . '/theme.php';

Theme::instance();
