jQuery(function($){

	// http://www.ibenic.com/wordpress-file-upload-with-ajax/ 

	function SavingWallet() {
		this.errors = {};
		this.requiredFields = ['reg_username', 'fname', 'lname', 'dd', 'email', 'phone', 'pass', 'confpass'];
		this.phone_status = 'unverified';
	}

	// open remodal 
	SavingWallet.prototype.openModal = function(el) {
    	var inst = $('[data-remodal-id='+ el +']').remodal();
    	inst.open();
	}	

	// close remodal 
	SavingWallet.prototype.closeModal = function(el) {
    	var inst = $('[data-remodal-id='+ el +']').remodal();
    	inst.close();
	}	

	// check empty object
	SavingWallet.prototype.isEmpty = function(obj) {
		for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
	    }
	    return true;
	}

	SavingWallet.prototype.isNumber = function(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}

	// confirm password match 
	SavingWallet.prototype.checkPasswordMatch = function(p1, p2) {
	    if(p1) {
	    	if (p1 != p2) {
	    		return false;
		    } else { 
		    	return true;
		    }
	    }
	}


	// email validation
	SavingWallet.prototype.isValidEmail = function(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}

	// phone validation 
	SavingWallet.prototype.isPhoneValid = function(){
		var telInput = $("#phone");
		telInput.intlTelInput({
		  utilsScript: local.themepath + '/assets/inttelinput/js/utils.js',
		  onlyCountries: ["us"],
		  preferredCountries: []
		});
		if(telInput.intlTelInput("isValidNumber")){
			return true;
		} else {
			return false;
		}
	}

	// check any invalid or empty field 
	SavingWallet.prototype.hasEmptyInvalidField = function(){
		var that = this;
		$.each(this.requiredFields, function(i, v) {
			var val = $('input[name="' + v + '"]').val();
			var trimmedval = $.trim(val);
			if( v == 'phone' && !that.isPhoneValid()) {
				$('input[name="phone"]').addClass('error-msg');
				that.errors['phone'] = 'Invalid phone';
			} else if ( v == 'email' && !that.isValidEmail(val)) {
				$('input[name="email"]').addClass('error-msg');
				that.errors['email'] = 'Invalid email';
			} else if (!trimmedval) {
				$('input[name="'+ v +'"]').addClass('error-msg');
				that.errors[v] = 'invalid ' + v;
			}
		});
	}


	SavingWallet.prototype.prepareUpload = function (e) {
		e.preventDefault();
		var file = e.target.files;
		var data = new FormData();
		var nonce = $("#_wpnonce").val();
		data.append('action', 'upload_bank_doc');
		//data.append('nonce', local.nonce);
		$.each(file, function(k, v) {
			data.append('upload_bank_doc', v);
		});

    	$.ajax({
    		  url: local.ajax_url,
	          type: 'POST',
	          data: data,
	          cache: false,
	          dataType: 'json',
	          processData: false,
	          contentType: false,
	          success: function(data, textStatus, jqXHR) {	
	          	console.log(data);
              	if( data.response == "SUCCESS" ){
	                var preview = "";
	                if( data.type === "image/jpg" 
	                  || data.type === "image/png" 
	                  || data.type === "image/gif"
	                  || data.type === "image/jpeg"
	                ) {
	                  preview = '<li data-attachment-id="' + data.id + '"><img src="' + data.url + '"><span class="delete_doc" data-fileurl="' + data.url + '">x</span></li>';
	                } else {
	                  preview = '<li data-attachment-id="' + data.id + '"><a href="' + data.url + '">' + data.filename + '"</a></li>';
	                }
	  
	                var previewID = $('#preview_doc');
	                previewID.append(preview);
                
                 } else {
                 	$('.add_bank_message').css('color', 'red').text(data.error);
                 }
			}
		})
    };



	var savingwallet = new SavingWallet();

	$('input').on('keyup change', function(e){
		$(this).removeClass('error-msg');
	});

    /* toggle QR code */
    $(".user_profile_img").toggle(
        function(){$(".qr_code").css({"z-index": "1"});},
        function(){$(".qr_code").css({"z-index": "-1"});}
    );

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

	// inttelinput 
	var telInput = $("#phone");

	// initialise plugin
	telInput.intlTelInput({
	  utilsScript: local.themepath + '/assets/inttelinput/js/utils.js',
	  onlyCountries: ["us"],
	  preferredCountries: []
	});

	var reset = function() {
	  telInput.parent().next('.show_message').text('');
	  delete savingwallet.errors['phone'];
	};

	// on blur: validate
	telInput.blur(function() {
	  reset();
	  if ($.trim(telInput.val())) {
	    if (telInput.intlTelInput("isValidNumber")) {
	    	telInput.parent().next('.show_message')
	    	.removeClass('error_message')
	    	.addClass('success_message')
	    	.text(' ✓ valid phone');
	      	delete savingwallet.errors['phone'];
	    } else {
	     	telInput.parent().next('.show_message')	    	
	     	.removeClass('success_message')
	    	.addClass('error_message')
	    	.text('Invalid phone');
	     	savingwallet.errors['phone'] = 'Invalid phone number';
	    }
	  }
	});

	// on keyup / change flag: reset
	telInput.on("keyup change", reset);	


	// customer user mail validation
	var emailInput = $("#email");
	emailInput.blur(function() {
	  $(this).next().text('');
	  if ($.trim(emailInput.val())) {
	    if (savingwallet.isValidEmail($.trim(emailInput.val()))) {
	      $(this).next().removeClass('error_message').addClass('success_message').text('✓ Valid email');
	      delete savingwallet.errors['email'];
	    } else {
	    	$(this).next().removeClass('success_message').addClass('error_message').text('Invalid email');
	    	savingwallet.errors['email'] = 'Invalid email';
	    }
	  }
	});

	$('.form').find('input:not([type="date"])').on('keyup blur focus change', function (e) {
	  var $this = $(this),
	      label = $this.prev('label');

		  if (e.type === 'keyup') {
				if ($this.val()) {
	          label.addClass('active highlight');
	        } else {
	          label.removeClass('active highlight');
	        }
	    } else if (e.type === 'blur') {
	    	if( $this.val()) {
	    		label.addClass('active highlight'); 
				} else {
			    label.removeClass('active highlight');   
				}   
	    } else if (e.type === 'focus') {
	      
	      if( $this.val()) {
	    		label.addClass('active highlight'); } 
	      else {
			    label.removeClass('active highlight');
				}
	    }

	});

	$('.tab-group').each(function(){
	    // For each set of tabs, we want to keep track of
	    // which tab is active and it's associated content
	    var $active, $content, $links = $(this).find('a');

	    // If the location.hash matches one of the links, use that as the active tab.
	    // If no match is found, use the first link as the initial active tab.
	    $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
	    $active.addClass('active');

	    $content = $($active[0].hash);

	    // Hide the remaining content
	    $links.not($active).each(function () {
	        $(this.hash).hide();
	    });

	    // Bind the click event handler
	    $(this).on('click', 'a', function(e){
	        // Make the old tab inactive.
	        $active.removeClass('active');
	        $content.hide();

	        // Update the variables with the new link and content
	        $active = jQuery(this);
	        $content = jQuery(this.hash);

	        // Make the tab active.
	        $active.addClass('active');
	        $content.show();

	        // Prevent the anchor's default click action
	        e.preventDefault();
	    });
	});


	/* password match */
	function checkPasswordMatch() {
	    var password = $("#pass").val();
	    var confirmPassword = $("#confpass").val();

	    if(password) {
	    	if (password != confirmPassword) {
		        $("#pass, #confpass").next().attr('id', 'error-msg').text('Passwords do not match!');
		    	savingwallet.errors['pass'] = 'Passwords not match';
		    } else { 
		        $("#pass, #confpass").next().attr('id', 'valid-msg').text('✓ Passwords match');
		    	delete savingwallet.errors['pass'];
		    }
	    }
	}

	// customer pass check
	$(document).ready(function () {
	   $("#pass, #confpass").on( 'keyup blur focus change', checkPasswordMatch);
	});

	/* Customer registration */
	$('form#register_customer').submit( function(event) {

		event.preventDefault();

	    var that = $(this);
	    var btn = that.find('.register_customer');

	    btn.text('Please wait...');

	    savingwallet.hasEmptyInvalidField();

	    console.log(savingwallet.errors);

	    if(!savingwallet.isEmpty(savingwallet.errors)) {
			that.prev().html('Please fill required fields');
			that.prev().addClass('alert-danger');
			that.prev().show();
			savingwallet.errors = {};
			btn.text('Get started');
			return;
	    } else {
	    	that.prev().hide();
	    }
	 
	    data = {
			action: 'register_user',
			reg_nonce 		: $('#register_user_nonce').val(),
			username 		: $('#reg_username').val(),
			firstname 		: $('#fname').val(),
			lastname 		: $('#lname').val(),
			gender 			: $('#gender').val(),
			dd 				: $('#dd').val(),
			email 			: $('#email').val(),
			phone 			: $("#phone").intlTelInput("getNumber"),
			streetaddress 	: $('#streetaddress').val(),
			apartmentsuite 	: $('#apartmentsuite').val(),
			city 			: $('#locality').val(),
			state 			: $('#administrative_area_level_1').val(),
			postal_code 	: $('#postal_code').val(),
			country 		: $('#country').val(),
			pass 			: $('#pass').val(),
			phone_status 	: savingwallet.phone_status,
	    };

	    console.log(data);
	 
	    // Do AJAX request
	    $.post( local.ajax_url, data, function(response) {
	    	console.log(response);
	      if( response ) {
	        var data = $.parseJSON(response);
	        if( typeof data.pin == 'number') {
	        	savingwallet.openModal('phone_verification');
	        	savingwallet.pin = data.pin;
	        	console.log(savingwallet.pin);
	        	return;
	        }
	        if( data == 'success' ) {
	          btn.text('Redirecting...');
	          location.reload();
	        } else {
	          that.prev().html(data);
	          that.prev().addClass('alert-danger');
	          that.prev().show();
	          btn.text('Get started');
	        }
	      }
	    });
	 
	});


	// Phone verification 
	$('#pin_submit').submit(function(e){
		e.preventDefault();
		var user_code = $("#verification_code").val();
		console.log(user_code == savingwallet.pin);
		if( user_code == savingwallet.pin ) {
			$(this).find('.show_message').removeClass('error_message').addClass('success_message').text('✓ Success!');
	        $('#phone').removeClass('error_message').addClass('success_message').text('Phone verified!');
			savingwallet.phone_status = 'verified';
			window.setTimeout(savingwallet.closeModal('phone_verification'), 3000);
			$('form#register_customer').submit();
		} else {
			$(this).find('.show_message').removeClass('success_message').addClass('error_message').text('x Failed');
		}
	});

	// User login 
	$('form#loginform').submit( function(event) {

		event.preventDefault();
		var that = $(this);
		var loginbutton = that.find('.login_btn');

	    loginbutton.text('Please wait...');

	    var reg_nonce = $('input[name="msw_login_nonce"]').val();
	    var username  = $('input[name="username"]').val();
	    var password  = $('input[name="password"]').val();
	 
	    data = {
	      action: 'user_login',
	      nonce: reg_nonce,
	      username: username,
	      password: password
	    };

	    // Do AJAX request
	    $.post( local.ajax_url, data, function(response) {

	      if( response ) {
	        var data = $.parseJSON(response);
	        if( data == 'success' ) {
	          loginbutton.text('Redirecting...');
	          location.reload();
	        } else {
	          that.prev().html(data);
	          that.prev().addClass('alert-danger');
	          that.prev().show();
	          loginbutton.text('Login');
	        }
	      }
	    });
	 
	});



	/* email verification */

	$('input[name="email_submit"]').click(function(e){

		e.preventDefault();

		var data = {
			action: 'email_verify',
			email_verify_nonce: $('.email_verify input[name="email_verify_nonce"]').val(), 
			email: $('.email_verify input[name="email"]').val()
		}

		$.ajax({
			type: 'POST',
			url: local.ajax_url,
			data: data,
			dataType: 'json',
			success: function(resp) {
				console.log(resp);
				if( resp.status == 'ERROR') {
					$('.message').css('color', 'red').text(resp.responsetext);
				} else if (resp.status == 'SUCCESS') {
					$('.message').css('color', 'green').text(resp.responsetext);
				}
			},
			error: function( req, status, err ) {
				console.log('error in ajax request');
				$('.message').css('color', 'red').text('something went wrong', status, err);
			}
		});

	});	

	/* cashback percentage */
	$('input[name="cashback_input"]').on('change', function(){
		if(local.cashback_percentage ) {
			var input = $(this).val();
			var cashback = local.cashback_percentage*input/100;
			$('#cashback_amount').val(local.currency_symbol + cashback);
		} else {
			$('.cashback_message').css('color', 'red').text('Please set cashback percentage between 5% and 35% from myaccount setting.');
		}
	});

	$('#cashback_btn').click(function(e){
		e.preventDefault();
		var cashback_amount = Number($('#cashback_amount').val().replace(/[^0-9\.]+/g,""));
		var data = {
			action: 'cashback', 
			cashback_amount: cashback_amount,
			customer_id: $('#customer_id').val(),
		}
		console.log(data);
		$.ajax({
			type: 'POST',
			url: local.ajax_url,
			data: data,
			dataType: 'json',
			success: function(resp) {
				console.log(resp);
				if(resp.status == 'error') {
					$('.cashback_message').css('color', 'red').text( resp.message );
				} else if (resp.status == 'success') {
					$('.cashback_message').css('color', 'green').text( resp.message );
					$('span.balance').text(local.currency_symbol + resp.balance);
				}
			},
			error: function( req, status, err ) {
				$('.cashback_message').html( 'something went wrong', status, err );
			}
		});

	});




	/* edit profile */
	$('#basic_info').submit(function(e){
		e.preventDefault();

		var data = {
			action: 'save_basic',
			dd: $(this).serializeArray()
		}

		console.log(data);

		$.ajax({
			type: 'POST',
			url: local.ajax_url,
			data: data,
			dataType: 'text',
			success: function(resp) {
				console.log(resp);
				$('.woocommerce-message').slideDown('slow').text( 'Basic Info Successfully updated!' );
			},
			error: function( req, status, err ) {
				$('.message').html( 'something went wrong', status, err );
			}
		});

	});	

	$('#user_settings').submit(function(e){
		e.preventDefault();

		var data = {};

		$.each($(this).serializeArray(), function(i, field) {
		    data[field.name] = field.value;
		});
		console.log(data);

		$.ajax({
			type: 'POST',
			url: local.ajax_url,
			data: { action: 'user_settings', dd: data },
			dataType: 'json',
			success: function(resp) {
				if(resp.status == 'success') {
					$('.message').css('color', 'green').text( resp.responsetext );
				} else {
					$('.message').css('color', 'red').text( resp.responsetext );
				}
			},
			error: function( req, status, err ) {
				$('.message').slideDown('slow').text( 'something went wrong', status, err );
			}
		});

	});

	// upload bank doc 
	$('#bank_docs').on('change', savingwallet.prepareUpload);

	// delete doc attachment
	$('body').on('click', 'span.delete_doc', function(){
		var _that = $(this);
		var id = _that.parent().data('attachment-id');
		var data = {
			action: 'delete_attachment',
			id: id
		}
		$.ajax({
			type: 'POST',
			url: local.ajax_url,
			data: data,
			cache: false,
			dataType: 'json',
			success: function(resp) {
				console.log(resp);
				if( resp.status == 'ERROR') {
						$('.add_bank_message').css('color', 'red').html(resp.responsetext);
				} else if (resp.status == 'SUCCESS') {
					console.log('upload should be deleted');
					_that.parent().remove();
				}
			},
			error: function( req, status, err ) {
				$('.add_bank_message').css('color', 'red').html('something went wrong', status, err);
			}
		});


	});


	/* save bank info */
	$('#add_bank').submit(function(e){
		e.preventDefault();
		var attachment_ids = [];
		var values = {};
		$.each($(this).serializeArray(), function(i, field) {
		    values[field.name] = field.value;
		});

		// graps all attachment ids 
		$('li[data-attachment-id]').each(function(){
			attachment_ids.push($(this).data('attachment-id'));
		});

		// include attachments ids to data values
		values['attachment_ids'] = attachment_ids;

		var data = {
			action: 'save_bank',
			dd: values
		}

		$.ajax({
			type: 'POST',
			url: local.ajax_url,
			data: data,
			dataType: 'json',
			success: function(resp) {
				if( resp.status == 'error') {
						$('.add_bank_message').css('color', 'red').html(resp.responsetext);
				} else {
					$('.add_bank_message').css('color', 'green').html(resp.responsetext);
					window.setTimeout(location.reload(), 3000);
				}
			},
			error: function( req, status, err ) {
				$('.add_bank_message').css('color', 'red').html('something went wrong', status, err);
			}
		});

	});

	/* add bank info */
	$('.add_bank').click(function(e){
		e.preventDefault();
		savingwallet.openModal('add_bank');
	});

	$('.banklist').next().hide();

	$('.banklist').click(function(){
		$(this).next().toggle();
	})	

	$('.banklist_title>section>label>i').click(function(){
		var conf_val = confirm('Delete the bank info?');
		if(conf_val === true ) {

			var data = {
				action: 'remove_bank',
				bankid: $(this).data('bankid')
			};

			$.ajax({
				type: 'POST',
				url: local.ajax_url,
				data: data,
				dataType: 'json',
				success: function(resp) {
					console.log(resp);
					if( resp.status == 'success') {
							$('.show_message').css('color', 'green').html(resp.responsetext);
							$('#banklist'+data.bankid).next().remove();
							$('#banklist'+data.bankid).remove();
					} else if (resp.status == 'error') {
						$('.show_message').css('color', 'red').html(resp.responsetext);
					}
				},
				error: function( req, status, err ) {
					console.log('error in ajax request');
					$('.show_message').css('color', 'red').html('something went wrong', status, err);
				}
			});

		}
	});

	/* add balance */
	$('.topup').click(function(){
		savingwallet.openModal('topup');
	});

	$('#topup').submit(function(e){
		e.preventDefault();

		var data = {
			action: 'topup',
		};

		$.each($(this).serializeArray(), function(i, field) {
		    data[field.name] = field.value;
		});

		$.ajax({
			type: 'POST',
			url: local.ajax_url,
			data: data,
			dataType: 'json',
			success: function(resp) {
				console.log(resp);
				if( resp.response !== 1) {
						$('.show_message').css('color', 'red').html(resp.responsetext);
				} else {
					$('.show_message').css('color', 'green').html('Top up ' + resp.amount + 'successful');
				}
			},
			error: function( req, status, err ) {
				console.log('error in ajax request');
				$('.show_message').css('color', 'red').html('something went wrong', status, err);
			}
		});

	});

	/* withdraw request */
	var withdrawClick = 1; 
	$('.withdraw').click(function(e){
		e.preventDefault();
		if ( withdrawClick == 1 ) {
	        $("form#withdraw").slideDown();
	        withdrawClick = 2;
    	} else {
    		withdrawClick = 1;
		    var data = {
				action: 'withdraw_request',
				bank: $('form#withdraw input[name="bank_name"]:checked').val(),
				amount: $('form#withdraw input[name="amount"]').val()
			}

			$.ajax({
				type: 'POST',
				url: local.ajax_url,
				data: data,
				dataType: 'json',
				success: function(resp) {
					console.log(resp);
					if( resp.status == 'ERROR') {
						$('.message').css('color', 'red').text(resp.responsetext);
					} else if (resp.status == 'SUCCESS') {
						$('.balance').text(local.currency_symbol + resp.balance);
						$('.message').css('color', 'green').text(resp.responsetext);
					}
				},
				error: function( req, status, err ) {
					console.log('error in ajax request');
					$('.message').css('color', 'red').text('something went wrong', status, err);
				}
			});

	    }

	});


});