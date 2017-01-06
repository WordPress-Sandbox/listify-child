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

		console.log($(this).parent().parent());

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: data,
			dataType: 'json',
			success: function(resp) {
				console.log(resp);
				$(this).parent().parent().html('Bank status set to ' + resp.status);
			},
			error: function( req, status, err ) {
				$(this).parent().parent().html('something went wrong', status, err);
			}
		});

	});
})