// Admin JavaScript for V7 Popup Modal

(function ($) {
    'use strict';

    $(document).ready(function () {
        var mediaUploader;

        $('#v7_upload_icon_button').on('click', function (e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Icon',
                button: {
                    text: 'Choose Icon'
                },
                multiple: false
            });

            mediaUploader.on('select', function () {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#v7_icon_url').val(attachment.url);
                $('#v7_remove_icon_button').show();
                if ($('#v7_icon_preview').length === 0) {
                    $('#v7_upload_icon_button').after('<br><img id="v7_icon_preview" src="' + attachment.url + '" style="max-width: 100px; max-height: 100px; margin-top: 10px;" />');
                } else {
                    $('#v7_icon_preview').attr('src', attachment.url).show();
                }
            });

            mediaUploader.open();
        });

        $('#v7_remove_icon_button').on('click', function (e) {
            e.preventDefault();
            $('#v7_icon_url').val('');
            $('#v7_remove_icon_button').hide();
            $('#v7_icon_preview').hide();
        });

        // Initialize WP color picker
        if ($.wp && $.wp.colorPicker) {
            $('.v7-color-field').wpColorPicker();
        } else if ($.fn.wpColorPicker) {
            $('.v7-color-field').wpColorPicker();
        }
    });

})(jQuery);