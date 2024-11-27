<?php
/**
 * Plugin Name: Hello Plus
 * Description: Puts the PLUS in Elementor's Hello+ themes
 * Plugin URI: https://elementor.com
 * Author: Elementor.com
 * Version: 0.0.4
 * Author URI: https://elementor.com
 * License: GPL-3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Text Domain: hello-plus
 *
 * @package HelloPlus
 *
 * Hello+ Plugin is a free WordPress plugin crafted for seamless use with Elementor’s Hello Themes,
 * tailored to help beginner web creators, but far from limited to just them, to build professional websites with ease.
 * It features a guided setup, dedicated kits, streamlined management tools,
 * and specialized widgets (Hello Widgets) to ensure a fast, efficient site-building experience
 *
 * Hello+ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 */

use HelloPlus\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_PLUS_VERSION', '0.0.4' );

define( 'HELLO_PLUS__FILE__', __FILE__ );
define( 'HELLO_PLUS_PLUGIN_BASE', plugin_basename( HELLO_PLUS__FILE__ ) );
define( 'HELLO_PLUS_PATH', plugin_dir_path( HELLO_PLUS__FILE__ ) );
define( 'HELLO_PLUS_URL', plugins_url( '', HELLO_PLUS__FILE__ ) );
define( 'HELLO_PLUS_ASSETS_PATH', HELLO_PLUS_PATH . 'build/' );
define( 'HELLO_PLUS_ASSETS_URL', HELLO_PLUS_URL . '/build/' );
define( 'HELLO_PLUS_SCRIPTS_PATH', HELLO_PLUS_ASSETS_PATH . 'js/' );
define( 'HELLO_PLUS_SCRIPTS_URL', HELLO_PLUS_ASSETS_URL . 'js/' );
define( 'HELLO_PLUS_STYLE_PATH', HELLO_PLUS_ASSETS_PATH . 'css/' );
define( 'HELLO_PLUS_STYLE_URL', HELLO_PLUS_ASSETS_URL . 'css/' );
define( 'HELLO_PLUS_IMAGES_PATH', HELLO_PLUS_ASSETS_PATH . 'images/' );
define( 'HELLO_PLUS_IMAGES_URL', HELLO_PLUS_ASSETS_URL . 'images/' );


if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

// Init the Theme class
require HELLO_PLUS_PATH . '/plugin.php';

Plugin::instance();
