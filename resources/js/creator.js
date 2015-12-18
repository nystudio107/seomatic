// LOAD EVENTS

$(function () {

    $('.metaPane').hide();
    value = $('#siteCreatorType').val();
    $('#'+value).show();
    if (value != "person")
    	$('#generic').show();
    $('#siteCreatorType').on('change', function(e) {
        $('.metaPane').hide();
        $('#'+this.value).show();
	    if (this.value != "person")
	    	$('#generic').show();
    });

});