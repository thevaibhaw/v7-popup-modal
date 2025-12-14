// Public JavaScript for V7 Popup Modal

(function ($) {
    'use strict';

    $(document).ready(function () {
        var $modal = $('#v7-popup-modal');
        var $closeBtn = $('.v7-popup-modal-close');
        var $timerSpan = $('#v7-timer-countdown');
        var timerEnabled = (typeof v7_popup_modal_vars.timer_enabled !== 'undefined') ? (parseInt(v7_popup_modal_vars.timer_enabled, 10) === 1) : false;
        var timerRemaining = (typeof v7_popup_modal_vars.timer_remaining !== 'undefined') ? parseInt(v7_popup_modal_vars.timer_remaining, 10) : null;
        var timerDuration = (typeof v7_popup_modal_vars.timer_duration !== 'undefined') ? (parseInt(v7_popup_modal_vars.timer_duration, 10) * 3600) : 3600; // Convert hours to seconds
        var countdownInterval;

        // Show modal on page load
        $('body').addClass('v7-modal-open');
        $modal.fadeIn();

        // Close modal on close button click
        $closeBtn.on('click', function () {
            closeModal();
        });

        // Close modal on overlay click
        $('.v7-popup-modal-overlay').on('click', function () {
            closeModal();
        });

        // Close modal on ESC key
        $(document).on('keydown', function (e) {
            if (e.keyCode === 27) {
                closeModal();
            }
        });

        // Timer functionality
        if (timerEnabled) {
            var remainingTime = (timerRemaining !== null) ? timerRemaining : timerDuration;
            // Only start the countdown if there is positive remaining time
            if (remainingTime > 0) {
                updateTimerDisplay(remainingTime);

                countdownInterval = setInterval(function () {
                    remainingTime--;
                    updateTimerDisplay(remainingTime);

                    if (remainingTime <= 0) {
                        clearInterval(countdownInterval);
                        closeModal();
                    }
                }, 1000);
            }
        }

        function updateTimerDisplay(seconds) {
            var hours = Math.floor(seconds / 3600);
            var minutes = Math.floor((seconds % 3600) / 60);
            var secs = seconds % 60;
            $timerSpan.text(
                (hours < 10 ? '0' : '') + hours + ':' +
                (minutes < 10 ? '0' : '') + minutes + ':' +
                (secs < 10 ? '0' : '') + secs
            );
        }

        function closeModal() {
            $modal.fadeOut();
            $('body').removeClass('v7-modal-open');
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
        }
    });

})(jQuery);