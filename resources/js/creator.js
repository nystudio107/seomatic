// LOAD EVENTS

$(function () {

    $('.metaPane').hide();
    var value = $('#siteCreatorType').val();
    value = value.capitalizeFirstLetter();
    $('#'+value).show();
    if (value != "Person")
        $('#generic').show();

    $('#siteCreatorType').on('change', function(e) {
        $('.metaPane').hide();
        $('#'+this.value).show();
        if (this.value != "Person")
            $('#generic').show();
    });

    $('#preview-humans').on('click', function(e) {

        // Prevents the default action to be triggered.
        e.preventDefault();

        // Triggering bPopup when click event is fired
        $('#preview-humans-popup').bPopup();

    });

    $('#geolookup').on('click', function(e) {
        address = $('#genericCreatorStreetAddress').val() + ", "
                    + $('#genericCreatorAddressLocality').val() + ", "
                    + $('#genericCreatorAddressRegion').val() + ", "
                    + $('#genericCreatorPostalCode').val() + ", "
                    + $('#genericCreatorAddressCountry').val();
        $.ajax({
            url:"//maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=false",
            type: "POST",
            success:function(res) {
                $('#geolookup-errors').hide();
                if (res.results.length) {
                    $('#genericCreatorGeoLatitude').val(res.results[0].geometry.location.lat);
                    $('#genericCreatorGeoLongitude').val(res.results[0].geometry.location.lng);
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
