// LOAD EVENTS

$(function () {

    $('#siteSeoKeywords').tokenfield({
        createTokensOnBlur: true,
        });

    $('#preview-robots').on('click', function(e) {

        // Prevents the default action to be triggered.
        e.preventDefault();

        // Triggering bPopup when click event is fired
        $('#preview-robots-popup').bPopup();

    });

});