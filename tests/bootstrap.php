<?php

$composer_autoloader_file = __DIR__ . '/../vendor/autoload.php';

if ( ! file_exists( $composer_autoloader_file ) ) {
	die( 'Installing composer are required for running the tests.' );
}

require $composer_autoloader_file;

$_tests_dir = getenv( 'WP_TESTS_DIR' );

define( 'ELEMENTOR_TESTS', true );
define( 'HELLO_PLUS_TESTS', true );

/**
 * change PLUGIN_FILE env in phpunit.xml
 */
define( 'PLUGIN_FILE', getenv( 'PLUGIN_FILE' ) );
define( 'PLUGIN_FOLDER', basename( dirname( __DIR__ ) ) );
define( 'PLUGIN_PATH', PLUGIN_FOLDER . '/' . PLUGIN_FILE );

$elementor_plugin_path = 'elementor/elementor.php';

$active_plugins = [ $elementor_plugin_path, PLUGIN_PATH ];

// Activates this plugin in WordPress so it can be tested.
$GLOBALS[ 'wp_tests_options' ] = [
	'active_plugins' => $active_plugins,
	'template'       => 'twentytwentyone',
	'stylesheet'     => 'twentytwentyone',
];

require_once $_tests_dir . '/includes/functions.php';

// Mock email implementation for tests
tests_add_filter( 'wp_mail', function ( $args ) {
	// Create our mock emails table if it doesn't exist
	global $wpdb;
	$table_name = $wpdb->prefix . 'mock_emails';

	// Check if table exists
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            to_email text NOT NULL,
            subject text NOT NULL,
            message text NOT NULL,
            headers text NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	// Store the email
	$to      = $args[ 'to' ];
	$subject = $args[ 'subject' ];
	$message = $args[ 'message' ];
	$headers = $args[ 'headers' ];

	$wpdb->insert(
		$table_name,
		[
			'time'     => current_time( 'mysql' ),
			'to_email' => is_array( $to ) ? implode( ',', $to ) : $to,
			'subject'  => $subject,
			'message'  => $message,
			'headers'  => is_array( $headers ) ? implode( "\n", $headers ) : (string) $headers,
		]
	);

	// Log for debugging
	error_log( "Mock email captured: To: $to, Subject: $subject" );

	// Return true to indicate success and prevent actual sending
	return true;
}, 10, 1 );

// Instead of REST API, create an AJAX endpoint for checking emails
tests_add_filter( 'wp_ajax_check_mock_emails', 'check_mock_emails_handler' );
tests_add_filter( 'wp_ajax_nopriv_check_mock_emails', 'check_mock_emails_handler' );
function check_mock_emails_handler() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'mock_emails';

	$emails = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC LIMIT 10" );

	wp_send_json( $emails );
}

tests_add_filter( 'wp_ajax_clear_mock_emails', 'clear_mock_emails_handler' );
tests_add_filter( 'wp_ajax_nopriv_clear_mock_emails', 'clear_mock_emails_handler' );
function clear_mock_emails_handler() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'mock_emails';

	$wpdb->query( "TRUNCATE TABLE $table_name" );

	wp_send_json( [ 'success' => true, 'message' => 'Email log cleared' ] );
}

tests_add_filter( 'muplugins_loaded', function () {
	// Manually load plugin
	$elementor_plugin_path = getenv( 'WP_TESTS_ELEMENTOR_DIR' );

	require $elementor_plugin_path;

	require dirname( __DIR__ ) . '/' . PLUGIN_FILE;
} );

// Removes all sql tables on shutdown
// Do this action last
tests_add_filter( 'shutdown', 'drop_tables', 999999 );

require $_tests_dir . '/includes/bootstrap.php';

remove_action( 'admin_init', '_maybe_update_themes' );
remove_action( 'admin_init', '_maybe_update_core' );
remove_action( 'admin_init', '_maybe_update_plugins' );
/**
 * WordPress added deprecation error messages to print_emoji_styles in 6.4, which causes our PHPUnit assertions
 * to fail. This is something that might still change during the beta period, but for now we need to remove this action
 * as to not block all our PRs, but still run tests on WP Nightly.
 *
 * @see https://core.trac.wordpress.org/changeset/56682/
 */
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Set behavior like on WP Admin for things like WP_Query->is_admin (default post status will be based on `show_in_admin_all_list`).
if ( ! defined( 'WP_ADMIN' ) ) {
	define( 'WP_ADMIN', true );
}

do_action( 'plugins_loaded' );

function initialize_elementor_plugin( $plugin_class ) {
	if ( ! class_exists( $plugin_class ) ) {
		return null;
	}

	return $plugin_class::instance();
}

$plugin_instance = initialize_elementor_plugin( 'Elementor\Plugin' );

$plugin_instance->initialize_container();

do_action( 'init' );
do_action( 'wp_loaded' );
