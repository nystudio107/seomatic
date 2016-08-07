// LOAD EVENTS

$(function () {

    $('#preview-seometrics').on('click', function(e) {

        // Prevents the default action to be triggered.
        e.preventDefault();

        // Triggering bPopup when click event is fired
        $('#preview-seometrics-popup').bPopup();

    });

    $('#preview-display').on('click', function(e) {

        // Prevents the default action to be triggered.
        e.preventDefault();

        // Triggering bPopup when click event is fired
        $('#preview-display-popup').bPopup();

    });

    $('#preview-tags').on('click', function(e) {

        // Prevents the default action to be triggered.
        e.preventDefault();

        // Triggering bPopup when click event is fired
        $('#preview-tags-popup').bPopup();

    });

});