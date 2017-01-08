jQuery(function($){
	$('.accordion').accordion({
	    "transitionSpeed": 400
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