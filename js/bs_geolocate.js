jQuery(function($){

	// This example displays an address form, using the autocomplete feature
	// of the Google Places API to help users fill in the information.

	$("#bs_streetaddress").on('focus', function () {
		$(this).attr('placeholder', 'Street Address');
	    bs_geolocate();
	});

	var bs_placeSearch, bs_autocomplete;
	var bs_componentForm = {
	    bs_locality: 'long_name',
	    bs_administrative_area_level_1: 'short_name',
	    bs_country: 'long_name',
	    bs_postal_code: 'short_name'
	};

	function bs_initialize() {
	    // Create the autocomplete object, restricting the search
	    // to geographical location types.
	    bs_autocomplete = new google.maps.places.Autocomplete(
	    /** @type {HTMLInputElement} */ (document.getElementById('bs_streetaddress')), {
	        types: ['geocode'],
	        // componentRestrictions: ['us'],
	    });
	    // When the user selects an address from the dropdown,
	    // populate the address fields in the form.
	    google.maps.event.addListener(bs_autocomplete, 'place_changed', function () {
	        bs_fillInAddress();
	    });
	}

	// [START region_fillform]
	function bs_fillInAddress() {
	    // Get the place details from the autocomplete object.
	    var place = bs_autocomplete.getPlace();
	    for (var component in bs_componentForm) {
	        document.getElementById(component).value = '';
	        document.getElementById(component).disabled = false;
	    }

	    // Get each component of the address from the place details
	    // and fill the corresponding field on the form.
	    for (var i = 0; i < place.address_components.length; i++) {
	        var addressType = 'bs_' + place.address_components[i].types[0];
	        if (bs_componentForm[addressType]) {
	            var val = place.address_components[i][bs_componentForm[addressType]];
	            document.getElementById(addressType).previousElementSibling.className += ' active';
	            document.getElementById(addressType).value = val;
	        }
	    }
	}
	// [END region_fillform]

	// [START region_geolocation]
	// Bias the autocomplete object to the user's geographical location,
	// as supplied by the browser's 'navigator.geolocation' object.
	function bs_geolocate() {
		if (navigator.geolocation) {
		  navigator.geolocation.getCurrentPosition(function(position) {
		    var geolocation = {
		      lat: position.coords.latitude,
		      lng: position.coords.longitude
		    };
		    var circle = new google.maps.Circle({
		      center: geolocation,
		      radius: position.coords.accuracy
		    });
		    bs_autocomplete.setBounds(circle.getBounds());
		  });
		}
	}

	bs_initialize();
	// [END region_geolocation]

});