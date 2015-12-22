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

    $('#geolookup').on('click', function(e) {
	    address = $('#genericOwnerStreetAddress').val() + ", "
	    			+ $('#genericOwnerAddressLocality').val() + ", "
	    			+ $('#genericOwnerAddressRegion').val() + ", "
	    			+ $('#genericOwnerPostalCode').val() + ", "
	    			+ $('#genericOwnerAddressCountry').val();
		$.ajax({
			url:"http://maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=false",
			type: "POST",
			success:function(res){
			 $('#genericOwnerGeoLatitude').val(res.results[0].geometry.location.lat);
			 $('#genericOwnerGeoLongitude').val(res.results[0].geometry.location.lng);
			}
		});
    });

});