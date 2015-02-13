<?php

$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['SERVER_NAME'] = '';
$PHP_SELF = $GLOBALS['PHP_SELF'] = $_SERVER['PHP_SELF'] = '/index.php';

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if( ! $_tests_dir ) $_tests_dir = '/tmp/wordpress-tests-lib';

require_once $_tests_dir . '/includes/functions.php';

function _manually_load_plugin() {
    require dirname( __FILE__ ) . '/../meetup-connect.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';

activate_plugin( 'meetup-connect/meetup-connect.php' );

echo "Installing Meetup Connect...\n";

global $current_user, $meetup_connect_options;

$meetup_connect_options = get_option( 'meetup_connect_settings' );

$current_user = new WP_User(1);
$current_user->set_role('administrator');
