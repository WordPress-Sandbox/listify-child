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

		console.log(tab_id); 
		$('#savingwallet_admin a').removeClass('nav-tab-active');
		$('.tab-content').removeClass('current');

		$(this).addClass('nav-tab-active');
		$("#"+tab_id).addClass('current');
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

	/* add balance to user */
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
					$('.amount_to_user_message').css('color', 'green').html(resp.responsetext);
				} else if (resp.status == 'ERROR') {
					$('.amount_to_user_message').css('color', 'red').html(resp.responsetext);
				}
			},
			error: function( req, status, err ) {
				$('.amount_to_user_message').css('color', 'red').html('something went wrong', status, err);
			}
		});
	});

	/* search user by id */
	$('#SearchUser').submit(function(e){
		e.preventDefault();
		var _that = $(this);
		var userid = _that.find('.search_id').val();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: { action: 'SearchUser', user_id: userid },
			dataType: 'json',
			success: function(resp) {
				console.log(resp);
				if(resp.status == 'SUCCESS') {
					$('.user_search_message').empty();
					let res = resp.responsetext;
					let output = `<div class="userfound">
					<div class="user_img"><img src="` + res.avatar + `"></div>
					<div class="userinfo">`
					 + ` User name: <strong>` + res.name + `</strong><br/>`
					 + ` User email: ` + res.email + `<br/>`
					 + ` Role: ` + res.roles[0] + `<br/>`
					+ `</div>
					<a class="CreditBalance" data-action="credit">Credit Balance </a>
					<a class="DebitBalance" data-action="debit"> Debit Balance </a>
					</div>`;
					$('#LoadUser').empty().html(output);
				} else {
					$('.user_search_message').empty().css('color', 'red').html(resp.responsetext);
				} 
			},
			error: function(resp, status, err) {
				$('.user_search_message').empty().css('color', 'red').html('something went wrong', status, err);
			}
		});
	});

	$('body').on('click', '.CreditBalance, .DebitBalance', function(){
		$('#debitCredit').remove();
		let inputField = `<form id="debitCredit"><input type="number" name="balance_debit_credit"/>`;
			inputField += `<input type="hidden" name="action" value="`+ $(this).attr('data-action')+`"/>`;
			inputField += `<input type="submit" value="Credit Balance" /></form>`;
		$(this).after(inputField);
	});


})