<?php
/**
 * Register settings
 *
 * @package     Meetup_Connect\Admin\Settings\Register
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Retrieve the settings tabs
 *
 * @since       1.0.0
 * @return      array $tabs The registered settings tabs
 */
function meetup_connect_get_settings_tabs() {
    $settings = meetup_connect_get_registered_settings();

    $tabs               = array();
    $tabs['settings']   = __( 'Settings', 'meetup-connect' );
    $tabs['about']      = __( 'About', 'meetup-connect' );
    
    return apply_filters( 'meetup_connect_settings_tabs', $tabs );
}


/**
 * Retrieve the array of plugin settings
 *
 * @since       1.0.0
 * @return      array $meetup_connect_settings The registered settings
 */
function meetup_connect_get_registered_settings() {
    $meetup_connect_settings = array(
        // Settings
        'settings' => apply_filters( 'meetup_connect_settings', array(
            array(
                'id'        => 'auth_header',
                'name'      => __( 'Authentication Settings', 'meetup-connect' ),
                'desc'      => '',
                'type'      => 'header'
            ),
            array(
                'id'        => 'auth_info',
                'name'      => '',
                'desc'      => __( 'While we support both traditional API keys and OAuth, we highly recommend OAuth. Some functionality (such as user login and RSVPs) requires OAuth access.', 'meetup-connect' ),
                'type'      => 'info',
                'header'    => __( 'Notice', 'meetup-connect' ),
                'style'     => 'warning',
                'icon'      => 'info-circle'
            ),
            array(
                'id'        => 'auth_type',
                'name'      => __( 'Authentication Type', 'meetup-connect' ),
                'desc'      => __( 'Select the authentication type to use.', 'meetup-connect' ),
                'type'      => 'select',
                'options'   => array(
                    'key'       => __( 'API Key', 'meetup-connect' ),
                    'oauth'     => __( 'OAuth', 'meetup-connect' )
                )
            ),
            array(
                'id'        => 'api_key',
                'name'      => __( 'API Key', 'meetup-connect' ),
                'desc'      => sprintf( __( 'You can find your Meetup API key <a href="%s" target="_blank">here</a>.', 'meetup-connect' ), 'https://secure.meetup.com/meetup_api/key' ),
                'type'      => 'text'
            ),
            array(
                'id'        => 'oauth_key',
                'name'      => __( 'OAuth Key', 'meetup-connect' ),
                'desc'      => sprintf( __( 'You can find (or create) your OAuth key <a href="%s" target="_blank">here</a>.', 'meetup-connect' ), 'https://secure.meetup.com/meetup_api/oauth_consumers' ),
                'type'      => 'text'
            ),
            array(
                'id'        => 'oauth_secret',
                'name'      => __( 'OAuth Secret', 'meetup-connect' ),
                'desc'      => sprintf( __( 'You can find (or create) your OAuth secret <a href="%s" target="_blank">here</a>.', 'meetup-connect' ), 'https://secure.meetup.com/meetup_api/oauth_consumers' ),
                'type'      => 'text'
            )
        ) ),
        'about' => apply_filters( 'meetup_connect_settings_about', array(
            array(
                'id'        => 'about_header',
                'name'      => __( 'About', 'meetup-connect' ),
                'desc'      => '',
                'type'      => 'header'
            ),
        ) )
    );

    return apply_filters( 'meetup_connect_registered_settings', $meetup_connect_settings );
}


/**
 * Retrieve an option
 *
 * @since       1.0.0
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      mixed
 */
function meetup_connect_get_option( $key = '', $default = false ) {
    global $meetup_connect_options;

    $value = ! empty( $meetup_connect_options[$key] ) ? $meetup_connect_options[$key] : $default;
    $value = apply_filters( 'meetup_connect_get_option', $value, $key, $default );

    return apply_filters( 'meetup_connect_get_option_' . $key, $value, $key, $default );
}


/**
 * Retrieve all options
 *
 * @since       1.0.0
 * @return      array $meetup_connect_options The Meetup Connect options
 */
function meetup_connect_get_settings() {
    $meetup_connect_settings = get_option( 'meetup_connect_settings' );

    if( empty( $meetup_connect_settings ) ) {
        $meetup_connect_settings = array();

        update_option( 'meetup_connect_settings', $meetup_connect_settings );
    }

    return apply_filters( 'meetup_connect_get_settings', $meetup_connect_settings );
}


/**
 * Add settings sections and fields
 *
 * @since       1.0.0
 * @return      void
 */
function meetup_connect_register_settings() {
    if( get_option( 'meetup_connect_settings' ) == false ) {
        add_option( 'meetup_connect_settings' );
    }

    foreach( meetup_connect_get_registered_settings() as $tab => $settings ) {
        add_settings_section(
            'meetup_connect_settings_' . $tab,
            __return_null(),
            '__return_false',
            'meetup_connect_settings_' . $tab
        );

        foreach( $settings as $option ) {
            $name = isset( $option['name'] ) ? $option['name'] : '';

            add_settings_field(
                'meetup_connect_settings[' . $option['id'] . ']',
                $name,
                function_exists( 'meetup_connect_' . $option['type'] . '_callback' ) ? 'meetup_connect_' . $option['type'] . '_callback' : 'meetup_connect_missing_callback',
                'meetup_connect_settings_' . $tab,
                'meetup_connect_settings_' . $tab,
                array(
                    'section'       => $tab,
                    'id'            => isset( $option['id'] )           ? $option['id']             : null,
                    'desc'          => ! empty( $option['desc'] )       ? $option['desc']           : '',
                    'name'          => isset( $option['name'] )         ? $option['name']           : null,
                    'size'          => isset( $option['size'] )         ? $option['size']           : null,
                    'options'       => isset( $option['options'] )      ? $option['options']        : '',
                    'std'           => isset( $option['std'] )          ? $option['std']            : '',
                    'min'           => isset( $option['min'] )          ? $option['min']            : null,
                    'max'           => isset( $option['max'] )          ? $option['max']            : null,
                    'step'          => isset( $option['step'] )         ? $option['step']           : null,
                    'placeholder'   => isset( $option['placeholder'] )  ? $option['placeholder']    : null,
                    'rows'          => isset( $option['rows'] )         ? $option['rows']           : null,
                    'buttons'       => isset( $option['buttons'] )      ? $option['buttons']        : null,
                    'wpautop'       => isset( $option['wpautop'] )      ? $option['wpautop']        : null,
                    'teeny'         => isset( $option['teeny'] )        ? $option['teeny']          : null,
                    'notice'        => isset( $option['notice'] )       ? $option['notice']         : false,
                    'style'         => isset( $option['style'] )        ? $option['style']          : null,
                    'header'        => isset( $option['header'] )       ? $option['header']         : null,
                    'icon'          => isset( $option['icon'] )         ? $option['icon']           : null,
                    'class'         => isset( $option['class'] )        ? $option['class']          : null
                )
            );
        }
    }

    register_setting( 'meetup_connect_settings', 'meetup_connect_settings', 'meetup_connect_settings_sanitize' );
}
add_action( 'admin_init', 'meetup_connect_register_settings' );


/**
 * Settings sanitization
 *
 * @since       1.0.0
 * @param       array $input The value entered in the field
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      string $input The sanitized value
 */
function meetup_connect_settings_sanitize( $input = array() ) {
    global $meetup_connect_options;

    if( empty( $_POST['_wp_http_referer'] ) ) {
        return $input;
    }
    
    parse_str( $_POST['_wp_http_referer'], $referrer );

    $settings   = meetup_connect_get_registered_settings();
    $tab        = isset( $referrer['tab'] ) ? $referrer['tab'] : 'settings';

    $input = $input ? $input : array();
    $input = apply_filters( 'meetup_connect_settings_' . $tab . '_sanitize', $input );

    foreach( $input as $key => $value ) {
        $type = isset( $settings[$tab][$key]['type'] ) ? $settings[$tab][$key]['type'] : false;

        if( $type ) {
            // Field type specific filter
            $input[$key] = apply_filters( 'meetup_connect_settings_sanitize_' . $type, $value, $key );
        }

        // General filter
        $input[$key] = apply_filters( 'meetup_connect_settings_sanitize', $input[$key], $key );
    }

    if( ! empty( $settings[$tab] ) ) {
        foreach( $settings[$tab] as $key => $value ) {
            if( is_numeric( $key ) ) {
                $key = $value['id'];
            }

            if( empty( $input[$key] ) || ! isset( $input[$key] ) ) {
                unset( $meetup_connect_options[$key] );
            }
        }
    }

    // Merge our new settings with the existing
    $input = array_merge( $meetup_connect_options, $input );

    add_settings_error( 'meetup-connect-notices', '', __( 'Settings updated.', 'meetup-connect' ), 'updated' );

    return $input;
}


/**
 * Sanitize text fields
 *
 * @since       1.0.0
 * @param       array $input The value entered in the field
 * @return      string $input The sanitized value
 */
function meetup_connect_sanitize_text_field( $input ) {
    return trim( $input );
}
add_filter( 'meetup_connect_settings_sanitize_text', 'meetup_connect_sanitize_text_field' );


/**
 * Header callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @return      void
 */
function meetup_connect_header_callback( $args ) {
    echo '<hr />';
}


/**
 * Checkbox callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_checkbox_callback( $args ) {
    global $meetup_connect_options;

    $checked = isset( $meetup_connect_options[$args['id']] ) ? checked( 1, $meetup_connect_options[$args['id']], false ) : '';

    $html  = '<input type="checkbox" id="meetup_connect_settings[' . $args['id'] . ']" name="meetup_connect_settings[' . $args['id'] . ']" value="1" ' . $checked . '/>&nbsp;';
    $html .= '<label for="meetup_connect_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Color callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the settings
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_color_callback( $args ) {
    global $meetup_connect_options;

    if( isset( $meetup_connect_options[$args['id']] ) ) {
        $value = $meetup_connect_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $default = isset( $args['std'] ) ? $args['std'] : '';
    $size    = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

    $html  = '<input type="text" class="meetup-connect-color-picker" id="meetup_connect_settings[' . $args['id'] . ']" name="meetup_connect_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '" data-default-color="' . esc_attr( $default ) . '" />&nbsp;';
    $html .= '<span class="meetup-connect-color-picker-label"><label for="meetup_connect_settings[' . $args['id'] . ']">' . $args['desc'] . '</label></span>';

    echo $html;
}


/**
 * Editor callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_editor_callback( $args ) {
    global $meetup_connect_options;

    if( isset( $meetup_connect_options[$args['id']] ) ) {
        $value = $meetup_connect_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $rows       = ( isset( $args['rows'] ) && ! is_numeric( $args['rows'] ) ) ? $args['rows'] : '10';
    $wpautop    = isset( $args['wpautop'] ) ? $args['wpautop'] : true;
    $buttons    = isset( $args['buttons'] ) ? $args['buttons'] : true;
    $teeny      = isset( $args['teeny'] ) ? $args['teeny'] : false;

    wp_editor(
        $value,
        'meetup_connect_settings_' . $args['id'],
        array(
            'wpautop'       => $wpautop,
            'media_buttons' => $buttons,
            'textarea_name' => 'meetup_connect_settings[' . $args['id'] . ']',
            'textarea_rows' => $rows,
            'teeny'         => $teeny
        )
    );
    echo '<br /><label for="meetup_connect_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';
}


/**
 * Info callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_info_callback( $args ) {
    global $meetup_connect_options;

    $notice = ( $args['notice'] == true ? '-notice' : '' );
    $class  = ( isset( $args['class'] ) ? $args['class'] : '' );
    $style  = ( isset( $args['style'] ) ? $args['style'] : 'normal' );
    $header = '';

    if( isset( $args['header'] ) ) {
        $header = '<b>' . $args['header'] . '</b><br />';
    }

    echo '<div id="meetup_connect_settings[' . $args['id'] . ']" name="meetup_connect_settings[' . $args['id'] . ']" class="meetup-connect-info' . $notice . ' meetup-connect-info-' . $style . '">';

    if( isset( $args['icon'] ) ) {
        echo '<p class="meetup-connect-info-icon">';
        echo '<i class="fa fa-' . $args['icon'] . ' ' . $class . '"></i>';
        echo '</p>';
    }

    echo '<p class="meetup-connect-info-desc">' . $header . $args['desc'] . '</p>';
    echo '</div>';
}


/**
 * Multicheck callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_multicheck_callback( $args ) {
    global $meetup_connect_options;

    if( ! empty( $args['options'] ) ) {
        foreach( $args['options'] as $key => $option ) {
            $enabled = ( isset( $meetup_connect_options[$args['id']][$key] ) ? $option : NULL );

            echo '<input name="meetup_connect_settings[' . $args['id'] . '][' . $key . ']" id="meetup_connect_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked( $option, $enabled, false ) . ' />&nbsp;';
            echo '<label for="meetup_connect_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br />';
        }
        echo '<p class="description">' . $args['desc'] . '</p>';
    }
}


/**
 * Number callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_number_callback( $args ) {
    global $meetup_connect_options;

    if( isset( $meetup_connect_options[$args['id']] ) ) {
        $value = $meetup_connect_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $max    = isset( $args['max'] ) ? $args['max'] : 999999;
    $min    = isset( $args['min'] ) ? $args['min'] : 0;
    $step   = isset( $args['step'] ) ? $args['step'] : 1;
    $size   = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

    $html  = '<input type="number" step="' . esc_attr( $step ) . '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . $size . '-text" id="meetup_connect_settings[' . $args['id'] . ']" name="meetup_connect_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '" />&nbsp;';
    $html .= '<label for="meetup_connect_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Password callback
 * 
 * @since       1.0.0
 * @param       array $args Arguments passed by the settings
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_password_callback( $args ) {
    global $meetup_connect_options;

    if( isset( $meetup_connect_options[$args['id']] ) ) {
        $value = $meetup_connect_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

    $html  = '<input type="password" class="' . $size . '-text" id="meetup_connect_settings[' . $args['id'] . ']" name="meetup_connect_settings[' . $args['id'] . ']" value="' . esc_attr( $value )  . '" />&nbsp;';
    $html .= '<label for="meetup_connect_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Radio callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_radio_callback( $args ) {
    global $meetup_connect_options;

    if( ! empty( $args['options'] ) ) {
        foreach( $args['options'] as $key => $option ) {
            $checked = false;

            if( isset( $meetup_connect_options[$args['id']] ) && $meetup_connect_options[$args['id']] == $key ) {
                $checked = true;
            } elseif( isset( $args['std'] ) && $args['std'] == $key && ! isset( $meetup_connect_options[$args['id']] ) ) {
                $checked = true;
            }

            echo '<input name="meetup_connect_settings[' . $args['id'] . ']" id="meetup_connect_settings[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked( true, $checked, false ) . '/>&nbsp;';
            echo '<label for="meetup_connect_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br />';
        }

        echo '<p class="description">' . $args['desc'] . '</p>';
    }
}


/**
 * Select callback
 * 
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_select_callback( $args ) {
    global $meetup_connect_options;

    if( isset( $meetup_connect_options[$args['id']] ) ) {
        $value = $meetup_connect_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';

    $html = '<select id="meetup_connect_settings[' . $args['id'] . ']" name="meetup_connect_settings[' . $args['id'] . ']" placeholder="' . $placeholder . '" />';

    foreach( $args['options'] as $option => $name ) {
        $selected = selected( $option, $value, false );

        $html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
    }

    $html .= '</select>&nbsp;';
    $html .= '<label for="meetup_connect_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Text callback
 * 
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_text_callback( $args ) {
    global $meetup_connect_options;

    if( isset( $meetup_connect_options[$args['id']] ) ) {
        $value = $meetup_connect_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

    $html  = '<input type="text" class="' . $size . '-text" id="meetup_connect_settings[' . $args['id'] . ']" name="meetup_connect_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) )  . '" />&nbsp;';
    $html .= '<label for="meetup_connect_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Textarea callback
 * 
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_textarea_callback( $args ) {
    global $meetup_connect_options;

    if( isset( $meetup_connect_options[$args['id']] ) ) {
        $value = $meetup_connect_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $html  = '<textarea class="large-text" cols="50" rows="5" id="meetup_connect_settings[' . $args['id'] . ']" name="meetup_connect_settings[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>&nbsp;';
    $html .= '<label for="meetup_connect_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Upload callback
 * 
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $meetup_connect_options The Meetup Connect options
 * @return      void
 */
function meetup_connect_upload_callback( $args ) {
    global $meetup_connect_options;

    if( isset( $meetup_connect_options[$args['id']] ) ) {
        $value = $meetup_connect_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

    $html  = '<input type="text" class="' . $size . '-text" id="meetup_connect_settings[' . $args['id'] . ']" name="meetup_connect_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '" />&nbsp;';
    $html .= '<span><input type="button" class="meetup_connect_settings_upload_button button-secondary" value="' . __( 'Upload File', 'meetup-connect' ) . '" /></span>&nbsp;';
    $html .= '<label for="meetup_connect_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Hook callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @return      void
 */
function meetup_connect_hook_callback( $args ) {
    do_action( 'meetup_connect_' . $args['id'] );
}


/**
 * Missing callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @return      void
 */
function meetup_connect_missing_callback( $args ) {
    printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'meetup-connect' ), $args['id'] );
}
