/* Live preview for V7 Popup Modal admin */
(function ($) {
    'use strict';
    $(document).ready(function () {
        var $preview = $('.v7-preview-box');
        var $cssField = $('textarea[name="v7_popup_modal_options[custom_css]"]');
        function applyPreviewCss() {
            $('#v7-admin-custom-css').remove();
            var css = $cssField.val() || '';
            if (css.trim()) {
                $('<style id="v7-admin-custom-css">' + css + '</style>').appendTo('head');
            }
        }
        $cssField.on('input', debounce(applyPreviewCss, 300));

        function debounce(fn, wait) {
            var t;
            return function () {
                clearTimeout(t);
                t = setTimeout(fn, wait);
            };
        }
        applyPreviewCss();
    });
})(jQuery);
