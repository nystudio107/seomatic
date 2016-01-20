// LOAD EVENTS

$(function () {

    $('.metaPane').hide();
    var value = $('#siteOwnerType').val();
    value = value.capitalizeFirstLetter();

    $('#'+value).show();
    if (value != "Person")
    	$('#generic').show();

    $('#siteOwnerType').on('change', function(e) {
        $('.metaPane').hide();
        $('#'+this.value).show();
	    if (this.value != "Person")
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
			success:function(res) {
			 	$('#geolookup-errors').hide();
				if (res.results.length) {
					$('#genericOwnerGeoLatitude').val(res.results[0].geometry.location.lat);
					$('#genericOwnerGeoLongitude').val(res.results[0].geometry.location.lng);
				} else {
			 		$('#geolookup-errors').show();
				}
			}
		});
    });

});

String.prototype.capitalizeFirstLetter = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}
