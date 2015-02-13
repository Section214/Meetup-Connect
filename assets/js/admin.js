/*jslint nomen: true*/
/*global jQuery, document, window, wp, _wpMediaViewsL10n, file_frame, meetup_connect_vars*/
/*jslint nomen: false*/
jQuery(document).ready(function ($) {
    'use strict';

    // Setup color picker
    if ($('.meetup-connect-color-picker').length) {
        $('.meetup-connect-color-picker').wpColorPicker();
    }

    // Setup uploaders
    if ($('.meetup_connect_settings_upload_button').length) {
        $('body').on('click', '.meetup_connect_settings_upload_button', function (e) {
            e.preventDefault();

            var button = $(this);

            window.formfield = $(this).parent().prev();

            // If the media frame already exists, reopen it
            if (file_frame) {
                file_frame.open();
                return;
            }

            // Create the media frame
            file_frame = wp.media.frames.file_frame = wp.media({
                frame: 'post',
                state: 'insert',
                title: button.data('uploader_title'),
                button: {
                    text: button.data('uploader_button_text')
                },
                multiple: false
            });

            file_frame.on('menu:render:default', function (view) {
                // Store our views in an object
                var views = {};

                // Unset default menu items
                view.unset('library-separator');
                view.unset('gallery');
                view.unset('featured-image');
                view.unset('embed');

                // Initialize the views in our object
                view.set(views);
            });

            // Run a callback on select
            file_frame.on('insert', function () {
                var selection = file_frame.state().get('selection');

                selection.each(function (attachment, index) {
                    attachment = attachment.toJSON();
                    window.formfield.val(attachment.url);
                });
            });

            // Open the modal
            file_frame.open();
        });

        var file_frame;
        window.formfield = '';
    }

    $("select[name='meetup_connect_settings[auth_type]']").change(function () {
        var selectedItem = $("select[name='meetup_connect_settings[auth_type]'] option:selected");

        if (selectedItem.val() === 'oauth') {
            $("div[name='meetup_connect_settings[auth_info]']").closest('tr').css('display', 'none');
            $("input[name='meetup_connect_settings[api_key]']").closest('tr').css('display', 'none');
            $("input[name='meetup_connect_settings[oauth_key]']").closest('tr').css('display', 'table-row');
            $("input[name='meetup_connect_settings[oauth_secret]']").closest('tr').css('display', 'table-row');
        } else {
            $("div[name='meetup_connect_settings[auth_info]']").closest('tr').css('display', 'table-row');
            $("input[name='meetup_connect_settings[api_key]']").closest('tr').css('display', 'table-row');
            $("input[name='meetup_connect_settings[oauth_key]']").closest('tr').css('display', 'none');
            $("input[name='meetup_connect_settings[oauth_secret]']").closest('tr').css('display', 'none');
        }
    }).change();
});
