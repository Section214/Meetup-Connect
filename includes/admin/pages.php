<?php
/**
 * Admin pages
 *
 * @package     Meetup_Connect\Admin\Pages
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Create the settings submenu page
 *
 * @since       1.0.0
 * @return      void
 */
function meetup_connect_add_settings_page() {
    add_menu_page( __( 'Meetup Connect Dashboard', 'meetup-connect' ), __( 'Meetup Connect', 'meetup-connect' ), 'manage_options', 'meetup-connect', 'meetup_connect_render_dashboard', 'dashicons-calendar-alt' );
    add_submenu_page( 'meetup-connect', __( 'Meetup Connect Dashboard', 'meetup-connect' ), __( 'Dashboard', 'meetup-connect' ), 'manage_options', 'meetup-connect', 'meetup_connect_render_dashboard' );
    add_submenu_page( 'meetup-connect', __( 'Meetup Connect Settings', 'meetup-connect' ), __( 'Settings', 'meetup-connect' ), 'manage_options', 'meetup-connect-settings', 'meetup_connect_render_settings_page' );
}
add_action( 'admin_menu', 'meetup_connect_add_settings_page', 10 );
