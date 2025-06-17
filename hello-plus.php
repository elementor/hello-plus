<?php
/**
 * Plugin Name: Hello Plus
 * Description: Puts the PLUS in Elementor's Hello+ themes
 * Plugin URI: https://elementor.com
 * Author: Elementor.com
 * Author URI: https://elementor.com/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 * Version: 1.6.2
 * License: GPL-3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Text Domain: hello-plus
 *
 * @package HelloPlus
 *
 * Hello+ is a free WordPress plugin designed to work seamlessly with Elementor’s Hello suite of themes.
 * Hello+ includes specialized Hello widgets such as a Header, Footer, Zigzag, Form Lite, and more.
 * Hello widgets help you build faster and create polished, professional websites.
 * To use Hello+, you’ll need to install one of Elementor’s Hello suite of themes.
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

define( 'HELLOPLUS_VERSION', '1.6.2' );
define( 'HELLO_PLUS_VERSION', HELLOPLUS_VERSION );

define( 'HELLOPLUS__FILE__', __FILE__ );
define( 'HELLOPLUS_PLUGIN_BASE', plugin_basename( HELLOPLUS__FILE__ ) );
define( 'HELLOPLUS_PATH', plugin_dir_path( HELLOPLUS__FILE__ ) );
define( 'HELLOPLUS_URL', plugins_url( '', HELLOPLUS__FILE__ ) );
define( 'HELLOPLUS_ASSETS_PATH', HELLOPLUS_PATH . 'assets/' );
define( 'HELLOPLUS_ASSETS_URL', HELLOPLUS_URL . '/assets/' );
define( 'HELLOPLUS_SCRIPTS_PATH', HELLOPLUS_ASSETS_PATH . 'js/' );
define( 'HELLOPLUS_SCRIPTS_URL', HELLOPLUS_ASSETS_URL . 'js/' );
define( 'HELLOPLUS_STYLE_PATH', HELLOPLUS_ASSETS_PATH . 'css/' );
define( 'HELLOPLUS_STYLE_URL', HELLOPLUS_ASSETS_URL . 'css/' );
define( 'HELLOPLUS_IMAGES_PATH', HELLOPLUS_ASSETS_PATH . 'images/' );
define( 'HELLOPLUS_IMAGES_URL', HELLOPLUS_ASSETS_URL . 'images/' );
define( 'HELLOPLUS_MIN_ELEMENTOR_VERSION', '3.27.1' );

// Init the Plugin class
require HELLOPLUS_PATH . '/plugin.php';

Plugin::instance();
