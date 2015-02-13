<?php
/**
 * Plugin Name:     Meetup Connect
 * Plugin URI:      http://section214.com
 * Description:     Integrate Meetup.com with your website
 * Version:         1.0.0
 * Author:          Daniel J Griffiths
 * Author URI:      http://ghost1227.com
 * Text Domain:     meetup-connect
 *
 * @package         Meetup_Connect
 * @author          Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright       Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


if( ! class_exists( 'Meetup_Connect' ) ) {


    /**
     * Main Meetup_Connect class
     *
     * @since       1.0.0
     */
    class Meetup_Connect {


        /**
         * @access      private
         * @since       1.0.0
         * @var         Meetup_Connect $instance The one true Meetup_Connect
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      self::$instance The one true Meetup_Connect
         */
        public static function instance() {
            if( ! self::$instance ) {
                self::$instance = new Meetup_Connect();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'MEETUP_CONNECT_VER', '1.0.0' );

            // Plugin path
            define( 'MEETUP_CONNECT_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'MEETUP_CONNECT_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include required files
         *
         * @access      private
         * @since       1.0.0
         * @global      array $meetup-connect_options The Meetup_Connect options array
         * @return      void
         */
        private function includes() {
            global $meetup_connect_options;

            require_once MEETUP_CONNECT_DIR . 'includes/admin/settings/register.php';
            $meetup_connect_options = meetup_connect_get_settings();

            require_once MEETUP_CONNECT_DIR . 'includes/scripts.php';
            require_once MEETUP_CONNECT_DIR . 'includes/functions.php';

            if( is_admin() ) {
                require_once MEETUP_CONNECT_DIR . 'includes/admin/actions.php';
                require_once MEETUP_CONNECT_DIR . 'includes/admin/pages.php';
                require_once MEETUP_CONNECT_DIR . 'includes/admin/dashboard.php';
                require_once MEETUP_CONNECT_DIR . 'includes/admin/settings/display.php';
            }
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function hooks() {
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
            $lang_dir = apply_filters( 'meetup_connect_language_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), '' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'meetup-connect', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/meetup-connect/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/meetup-connect/ folder
                load_textdomain( 'meetup-connect', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/meetup-connect/languages/ folder
                load_textdomain( 'meetup-connect', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'meetup-connect', false, $lang_dir );
            }
        }
    }
}


/**
 * The main function responsible for returning the one true Meetup_Connect
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      Meetup_Connect The one true Meetup_Connect
 */
function meetup_connect() {
    return Meetup_Connect::instance();
}
add_action( 'plugins_loaded', 'meetup_connect' );
