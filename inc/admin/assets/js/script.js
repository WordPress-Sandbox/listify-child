jQuery(function($){

	function isNotNumber(n) {
	  return isNaN(parseFloat(n)) && isFinite(n);
	}

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


    /* admin tabs 

    https://codepen.io/cssjockey/pen/jGzuK
    */
	$('#savingwallet_admin a').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})

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

	$('#add_user_balance').click(function(e){
		e.preventDefault();
		var data = {
			action: 'add_user_balance',
			userid: $('input[name="amount_to_user_id"]').val(),
			amount: $('input[name="amount_to_user"]').val(),
		}
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: data,
			dataType: 'json',
			success: function(resp) {
				if(resp.status == 'SUCCESS') {
					$('.amount_to_user_message').css('color', 'green').text(resp.responsetext);
				} else if (resp.status == 'ERROR') {
					$('.amount_to_user_message').css('color', 'red').text(resp.responsetext);
				}
			},
			error: function( req, status, err ) {
				$('.amount_to_user_message').css('color', 'red').text('something went wrong', status, err);
			}
		});
	});
})