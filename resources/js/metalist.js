// LOAD EVENTS

$(function () {

    // Behavior for "New Meta" button
    $('#new-meta').on('click', function () {
        // Redirect
        window.location = Craft.getUrl('seomatic/meta/new');
    });

});