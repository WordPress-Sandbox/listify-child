jQuery(function($){

	// This example displays an address form, using the autocomplete feature
	// of the Google Places API to help users fill in the information.

	$("#bs_streetaddress").on('focus', function () {
		$(this).attr('placeholder', 'Street Address');
	    geolocate();
	});

	var placeSearch, autocomplete;
	var componentForm = {
	    bs_locality: 'long_name',
	    bs_administrative_area_level_1: 'short_name',
	    bs_country: 'long_name',
	    bs_postal_code: 'short_name'
	};

	function initialize() {
	    // Create the autocomplete object, restricting the search
	    // to geographical location types.
	    autocomplete = new google.maps.places.Autocomplete(
	    /** @type {HTMLInputElement} */ (document.getElementById('bs_streetaddress')), {
	        types: ['geocode'],
	        // componentRestrictions: ['us'],
	    });
	    // When the user selects an address from the dropdown,
	    // populate the address fields in the form.
	    google.maps.event.addListener(autocomplete, 'place_changed', function () {
	        fillInAddress();
	    });
	}

	// [START region_fillform]
	function fillInAddress() {
	    // Get the place details from the autocomplete object.
	    var place = autocomplete.getPlace();

	    for (var component in componentForm) {
	        document.getElementById(component).value = '';
	        document.getElementById(component).disabled = false;
	    }

	    // Get each component of the address from the place details
	    // and fill the corresponding field on the form.
	    for (var i = 0; i < place.address_components.length; i++) {
	        var addressType = place.address_components[i].types[0];
	        if (componentForm[addressType]) {
	            var val = place.address_components[i][componentForm[addressType]];
	            document.getElementById(addressType).value = val;
	            // document.getElementById(addressType).previousSibling.className += ' active';
	        }
	    }
	}
	// [END region_fillform]

	// [START region_geolocation]
	// Bias the autocomplete object to the user's geographical location,
	// as supplied by the browser's 'navigator.geolocation' object.
	function geolocate() {
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
		    autocomplete.setBounds(circle.getBounds());
		  });
		}
	}

	initialize();
	// [END region_geolocation]

});