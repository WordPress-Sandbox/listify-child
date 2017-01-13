jQuery(function($){
	$('.accordion').accordion({
	    "transitionSpeed": 400
	});

	/* Magnific Popup */
    $('.magnific-popup').magnificPopup({
    	type: 'image',
    	closeOnContentClick: false,
    	mainClass: 'mfp-with-zoom',
		image: {
			verticalFit: true
		},
		zoom: {
			enabled: true,
			duration: 300, // don't foget to change the duration also in CSS
			easing: 'ease-in-out',
		}
    });


/* verify & unverify customer account */

	$('.verify_btn').click(function(e){
		e.preventDefault();

		var data = {
			action: 'verify_unverify_customer_account',
			userid: $(this).data('userid'),
			bankkey: $(this).data('bankkey'),
			status: $(this).data('status'),
		}

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: data,
			dataType: 'json',
			success: function(resp) {
				$('.message').text('Bank status set to ' + resp.status);
			},
			error: function( req, status, err ) {
				$('.message').text('something went wrong', status, err);
			}
		});

	});
})