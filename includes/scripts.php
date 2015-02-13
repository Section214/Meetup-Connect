<?php
/**
 * Scripts
 *
 * @package     Meetup_Connect\Scripts
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Load admin scripts
 *
 * @since       1.0.0
 * @param       string $hook The page hook
 * @return      void
 */
function meetup_connect_admin_scripts( $hook ) {
    if( $hook != 'meetup-connect_page_meetup-connect-settings' ) {
        return;
    }

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix   = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
    $ui_style = ( get_user_option( 'admin_color' ) == 'classic' ) ? 'classic' : 'fresh';

    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_media();
    wp_enqueue_style( 'jquery-ui-css', MEETUP_CONNECT_URL . 'assets/css/jquery-ui-' . $ui_style . $suffix . '.css' );
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_style( 'thickbox' );
    wp_enqueue_script( 'thickbox' );
    wp_enqueue_style( 'meetup-connect-fa', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );

    wp_enqueue_style( 'meetup-connect', MEETUP_CONNECT_URL . 'assets/css/admin' . $suffix . '.css', array(), MEETUP_CONNECT_VER );
    wp_enqueue_script( 'meetup-connect', MEETUP_CONNECT_URL . 'assets/js/admin' . $suffix . '.js', array( 'jquery' ), MEETUP_CONNECT_VER );
    wp_localize_script( 'meetup-connect', 'meetup_connect_vars', array(
        'image_media_button'    => __( 'Insert Image', 'meetup-connect' ),
        'image_media_title'     => __( 'Select Image', 'meetup-connect' ),
    ) );
}
add_action( 'admin_enqueue_scripts', 'meetup_connect_admin_scripts' );


/**
 * Load frontend scripts
 *
 * @since       1.0.0
 * @return      void
 */
function meetup_connect_scripts() {
    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    wp_enqueue_style( 'meetup-connect', MEETUP_CONNECT_URL . 'assets/css/meetup-connect' . $suffix . '.css', array(), MEETUP_CONNECT_VER );
    wp_enqueue_script( 'meetup-connect', MEETUP_CONNECT_URL . 'assets/js/meetup-connect' . $suffix . '.js', array( 'meetup-connect-colorbox' ), MEETUP_CONNECT_VER );
    wp_localize_script( 'meetup-connect', 'meetup_connect_vars', array(
    ) );
}
add_action( 'wp_enqueue_scripts', 'meetup_connect_scripts' );
