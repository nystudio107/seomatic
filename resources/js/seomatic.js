// LOAD EVENTS

$(function () {

    $('#preview-tags').on('click', function(e) {

        // Prevents the default action to be triggered.
        e.preventDefault();

        // Triggering bPopup when click event is fired
        $('#preview-tags-popup').bPopup();

    });

    $('#preview-display').on('click', function(e) {

        // Prevents the default action to be triggered.
        e.preventDefault();

        // Triggering bPopup when click event is fired
        $('#preview-display-popup').bPopup();

    });

});