<?php
/**
 * Settings page
 *
 * @package     Meetup_Connect\Admin\Settings\Display
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Render the settings page
 *
 * @since       1.0.0
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_render_settings_page() {
    global $meetup_connect_options;
    
    $active_tab = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], meetup_connect_get_settings_tabs() ) ? $_GET['tab'] : 'settings';

    ob_start();
    ?>
    <div class="wrap">
        <h2 class="nav-tab-wrapper">
            <?php
            foreach( meetup_connect_get_settings_tabs() as $tab_id => $tab_name ) {
                $tab_url = add_query_arg( array(
                    'settings-updated'  => false,
                    'tab'               => $tab_id
                ) );

                $active = $active_tab == $tab_id ? ' nav-tab-active' : '';

                echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">' . esc_html( $tab_name ) . '</a>';
            }
            ?>
        </h2>
        <div id="tab_container">
            <form method="post" action="options.php">
                <table class="form-table">
                    <?php
                    settings_fields( 'meetup_connect_settings' );
                    do_settings_fields( 'meetup_connect_settings_' . $active_tab, 'meetup_connect_settings_' . $active_tab );
                    ?>
                </table>
                <?php if( $active_tab != 'about' && $active_tab != 'club' ) { submit_button(); } ?>
            </form>
        </div>
    </div>
    <?php
    echo ob_get_clean();
}
