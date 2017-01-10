jQuery(function($){

	//

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



	var savingwallet = new SavingWallet();

	$('input').on('keyup change', function(e){
		$(this).removeClass('error-msg');
	});

    /* toggle QR code */
    $(".user_profile_img").toggle(
        function(){$(".qr_code").css({"z-index": "1"});},
        function(){$(".qr_code").css({"z-index": "-1"});}
    );

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
			user_id: $('.email_verify input[name="user_id"]').val(), 
			email: $('.email_verify input[name="email"]').val()
		}

		$.post( local.ajax_url, data, function(res) {
			var res = $.parseJSON(res);
			console.log(res);
			if(res == 'success') {
				$('.email_verify').html('<span class="success_message">✓ Please check your inbox.</span>');
			}
		});

	});	

	/* cashback percentage */
	$('input[name="cashback_input"]').on('change', function(){
		var input = $(this).val();
		var cashback = local.cashback_percentage*input/100;
		$('#cashback_amount').val(local.currency_symbol + cashback);
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


	/* async upload bank doc */
	var $imgFile = $('.bank_docs');
	var $imgNotice = $('.image-notice');
	var $imgId      = $('.image_id');

    $imgFile.on('change', function(e) {
	    e.preventDefault();

	    var formData = new FormData();

	    formData.append('action', 'upload-attachment');
	    formData.append('async-upload', $imgFile[0].files[0]);
	    formData.append('name', $imgFile[0].files[0].name);
	    formData.append('_wpnonce', local.nonce);

	    $.ajax({
	        url: local.upload_url,
	        data: formData,
	        processData: false,
	        contentType: false,
	        dataType: 'json',
	        xhr: function() {
	            var myXhr = $.ajaxSettings.xhr();

	            if ( myXhr.upload ) {
	                myXhr.upload.addEventListener( 'progress', function(e) {
	                    if ( e.lengthComputable ) {
	                        var perc = ( e.loaded / e.total ) * 100;
	                        perc = perc.toFixed(2);
	                        $imgNotice.html('Uploading&hellip;(' + perc + '%)');
	                    }
	                }, false );
	            }

	            return myXhr;
	        },
	        type: 'POST',
	        beforeSend: function() {
	            $imgFile.hide();
	            $imgNotice.html('Uploading&hellip;').show();
	        },
	        success: function(resp) {
	            if ( resp.success ) {
	                $imgNotice.html('Successfully uploaded. <a href="#" class="btn-change-image">Change?</a>');

	                var img = $('<img>', {
	                    src: resp.data.url
	                });

	                $imgId.val( resp.data.id );
	                // $imgPreview.html( img ).show();

	            } else {
	                $imgNotice.html('Fail to upload image. Please try again.');
	                $imgFile.show();
	                $imgId.val('');
	            }
	        }
	    });
	});

	/* save bank info */
	$('#add_bank').submit(function(e){
		e.preventDefault();

		var data = {
			action: 'save_bank',
			dd: $(this).serializeArray()
		}

		$.ajax({
			type: 'POST',
			url: local.ajax_url,
			data: data,
			dataType: 'json',
			success: function(resp) {
				if( typeof resp.error === 'object') {
						$('.add_bank_message').css('color', 'red').html(resp.error);
				} else {
					$('.add_bank_message').css('color', 'green').html(resp);
					window.setTimeout(location.reload(), 3000);
				}
			},
			error: function( req, status, err ) {
				console.log('error in ajax request');
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

			console.log(data);

			$.ajax({
				type: 'POST',
				url: local.ajax_url,
				data: data,
				dataType: 'json',
				success: function(resp) {
					console.log(resp);
					if( resp.status == 'error') {
						$('.message').css('color', 'red').text(resp.responsetext);
					} else if (resp.status == 'success') {
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