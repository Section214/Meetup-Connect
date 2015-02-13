<?php
/**
 * Admin Actions
 *
 * @package     Meetup_Connect\Admin\Actions
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Process all actions sent via POST and GET by looking for the 'meetup-connect-action'
 * request and running do_action() to call the function
 *
 * @since       1.0.0
 * @return      void
 */
function meetup_connect_process_actions() {
    if( isset( $_POST['meetup-connect-action'] ) ) {
        do_action( 'meetup_connect_' . $_POST['meetup-connect-action'], $_POST );
    }

    if( isset( $_GET['meetup-connect-action'] ) ) {
        do_action( 'meetup_connect_' . $_GET['meetup-connect-action'], $_GET );
    }
}
add_action( 'admin_init', 'meetup_connect_process_actions' );
