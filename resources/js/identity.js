// LOAD EVENTS

$(function () {

    $('.metaPane').hide();
    value = $('#siteOwnerType').val();
    $('#'+value).show();
    if (value != "person")
    	$('#generic').show();
    $('#siteOwnerType').on('change', function(e) {
        $('.metaPane').hide();
        $('#'+this.value).show();
	    if (this.value != "person")
	    	$('#generic').show();
    });

});