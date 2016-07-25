var selectedItem = "";
if (typeof metaFieldPrefix == 'undefined')
    var metaFieldPrefix = "";

function setSelectedValue(whichValue) {
    selectedItem = whichValue;
} /* -- setSelectedValue */

function fillDynamicMenu(whichValue) {
    var menu = $('#' + metaFieldPrefix + 'seoMainEntityOfPage');

    menu.empty();
    if (main_enitity_items[whichValue]) {
        $.each(main_enitity_items[whichValue], function(){
            $("<option />")
            .attr("value", this.value)
            .html(this.name)
            .appendTo(menu);
        });
    }
} /* -- fillDynamicMenu */

function updateInfoLink() {
    var selectedItem = $('#' + metaFieldPrefix + 'seoMainEntityOfPage').val();
    var value = $('#' + metaFieldPrefix + 'seoMainEntityCategory').val();
    if (selectedItem)
        $('.seomaticSchemaInfo').html("<a href='http://schema.org/" + selectedItem + "' target='_blank'>" + selectedItem + " info...</a>");
    else
        $('.seomaticSchemaInfo').html("<a href='http://schema.org/" + value + "' target='_blank'>" + value + " info...</a>");
} /* -- updateInfoLink */

$(function () {

    if ($('#' + metaFieldPrefix + 'seoKeywords').length)
    {
        $('#' + metaFieldPrefix + 'seoKeywords').tokenfield({
            createTokensOnBlur: true,
            });
    }

/* -- Set the panes to the right visibility based on the settings */

    var value = $('#' + metaFieldPrefix + 'seoMainEntityCategory').val();

/* -- Fill in the dynamic menu */

    fillDynamicMenu(value);
    $('#' + metaFieldPrefix + 'seoMainEntityOfPage').val(selectedItem);
    updateInfoLink();

/* -- Handle clicks on the seoMainEntityCategory dropdown */

    $('#' + metaFieldPrefix + 'seoMainEntityCategory').on('change', function(e) {
        var value = $('#' + metaFieldPrefix + 'seoMainEntityCategory').val();

        fillDynamicMenu(value);
        updateInfoLink();
    });

/* -- Handle clicks on the seoMainEntityOfPage dropdown */

    $('#' + metaFieldPrefix + 'seoMainEntityOfPage').on('change', function(e) {
        updateInfoLink();
    });

});