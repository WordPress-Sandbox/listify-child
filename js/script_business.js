jQuery(function($){

	//

	function Bs_savingWallet() {
		this.errors = {};
		this.requiredFields = ['bs_name', 'bs_type', 'bs_username', 'bs_fname', 'bs_lname', 'bs_dd', 'bs_email', 'bs_phone', 'bs_pass', 'bs_confpass'];
		this.phone_verify = 'unverified';
	}

	// open remodal 
	Bs_savingWallet.prototype.openModal = function() {
    	var inst = $('[data-remodal-id=bs_phone_verification]').remodal();
    	inst.open();
	}	

	// close remodal 
	Bs_savingWallet.prototype.closeModal = function() {
    	var inst = $('[data-remodal-id=bs_phone_verification]').remodal();
    	inst.close();
	}	

	// check empty object
	Bs_savingWallet.prototype.isEmpty = function(obj) {
		for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
	    }
	    return true;
	}

	// confirm password match 
	Bs_savingWallet.prototype.checkPasswordMatch = function(p1, p2) {
	    if(p1) {
	    	if (p1 != p2) {
	    		return false;
		    } else { 
		    	return true;
		    }
	    }
	}


	// email validation
	Bs_savingWallet.prototype.isValidEmail = function(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}

	// phone validation 
	Bs_savingWallet.prototype.isPhoneValid = function(){
		var telInput = $("#bs_phone");
		telInput.intlTelInput({
		  utilsScript: local.themepath + '/assets/inttelinput/js/utils.js',
		  // onlyCountries: ["us"],
		  preferredCountries: []
		});
		if(telInput.intlTelInput("isValidNumber")){
			return true;
		} else {
			return false;
		}
	}

	// check any invalid or empty field 
	Bs_savingWallet.prototype.hasEmptyInvalidField = function(){
		var that = this;
		$.each(this.requiredFields, function(i, v) {
			if( v == 'bs_type') {
				var val = $('select[name="bs_type"]').val();
			} else {
				var val = $('input[name="' + v + '"]').val();
			}
			var trimmedval = $.trim(val);
			if( v == 'bs_phone' && !that.isPhoneValid()) {
				$('input[name="bs_phone"]').addClass('error-msg');
				that.errors['bs_phone'] = 'Invalid phone';
			} else if ( v == 'bs_email' && !that.isValidEmail(val)) {
				$('input[name="bs_email"]').addClass('error-msg');
				that.errors['bs_email'] = 'Invalid email';
			} else if (!trimmedval) {
				if( v == 'bs_type') {
					$('select[name="bs_type"]').addClass('error-msg');
				} else {
					$('input[name="'+ v +'"]').addClass('error-msg');
				}
				that.errors[v] = 'invalid ' + v;
			}
		});
	}

	var bssavingwallet = new Bs_savingWallet();

	// inttelinput 
	var bstelInput = $("#bs_phone");

	// initialise plugin
	bstelInput.intlTelInput({
	  utilsScript: local.themepath + '/assets/inttelinput/js/utils.js',
	  // onlyCountries: ["us"],
	  preferredCountries: []
	});

	var bsreset = function() {
	  bstelInput.parent().next('.show_message').text('');
	  delete bssavingwallet.errors['phone'];
	};

	// on blur: validate
	bstelInput.blur(function() {
	  bsreset();
	  if ($.trim(bstelInput.val())) {
	    if (bstelInput.intlTelInput("isValidNumber")) {
	    	bstelInput.parent().next('.show_message')
	    	.removeClass('error_message')
	    	.addClass('success_message')
	    	.text(' ✓ valid phone');
	      	delete bssavingwallet.errors['phone'];
	    } else {
	     	bstelInput.parent().next('.show_message')	    	
	     	.removeClass('success_message')
	    	.addClass('error_message')
	    	.text('Invalid phone');
	     	bssavingwallet.errors['phone'] = 'Invalid phone number';
	    }
	  }
	});

	// on keyup / change flag: reset
	bstelInput.on("keyup change", bsreset);	


	// customer user mail validation
	var bsemailInput = $("#bs_email");
	bsemailInput.blur(function() {
	  $(this).next().text('');
	  if ($.trim(bsemailInput.val())) {
	    if (bssavingwallet.isValidEmail($.trim(bsemailInput.val()))) {
	      $(this).next().removeClass('error_message').addClass('success_message').text('✓ Valid email');
	      delete bssavingwallet.errors['email'];
	    } else {
	    	$(this).next().removeClass('success_message').addClass('error_message').text('Invalid email');
	    	bssavingwallet.errors['email'] = 'Invalid email';
	    }
	  }
	});

	/* password match */
	function bscheckPasswordMatch() {
	    var password = $("#bs_pass").val();
	    var confirmPassword = $("#bs_confpass").val();

	    if(password) {
	    	if (password != confirmPassword) {
		        $("#bs_pass, #bs_confpass").next().addClass('error_message').text('Passwords do not match!');
		    	bssavingwallet.errors['pass'] = 'Passwords not match';
		    } else { 
		        $("#bs_pass, #bs_confpass").next().addClass('success_message').text('✓ Passwords match');
		    	delete bssavingwallet.errors['pass'];
		    }
	    }
	}

	// customer pass check
	$(document).ready(function () {
	   $("#bs_pass, #bs_confpass").on( 'keyup blur focus change', bscheckPasswordMatch);
	});


	// Business registration 
	$('form#register_business').submit(function(event){

		event.preventDefault();
	    var that = $(this);
	    var btn = that.find('.register_business');

	    btn.text('Please wait...');

	    bssavingwallet.hasEmptyInvalidField();
	    console.log(bssavingwallet.errors);

	    if(!bssavingwallet.isEmpty(bssavingwallet.errors)) {
			that.prev().html('Please fill required fields');
			that.prev().addClass('alert-danger');
			that.prev().show();
			bssavingwallet.errors = {};
			btn.text('Get started');
			return;
	    } else {
	    	that.prev().hide();
	    }
	 
	    data = {
			action: 'register_business',
			reg_nonce 		: $('#register_business_nonce').val(),
			bs_name 	 	: $('#bs_name').val(),
			bs_username 	: $('#bs_username').val(),
			bs_fname 		: $('#bs_fname').val(),
			bs_lname 		: $('#bs_lname').val(),
			bs_gender 		: $('#bs_gender').val(),
			bs_dd 			: $('#bs_dd').val(),
			bs_email 		: $('#bs_email').val(),
			bs_phone 		: $('#bs_phone').val(),
			bs_streetaddress: $('#bs_streetaddress').val(),
			bs_apartmentsuite : $('#bs_apartmentsuite').val(),
			bslocality 		: $('#bslocality').val(),
			bsstate 		: $('#administrative_area_level_2').val(),
			bspostal_code 	: $('#bspostal_code').val(),
			bs_country 		: $('#bs_country').val(),
			bs_pass 		: $('#bs_pass').val(),
			phone_verify 	: bssavingwallet.phone_verify,
	    };
	 
	    // Do AJAX request
	    $.post( local.ajax_url, data, function(response) {
	      if( response ) {
	        var data = $.parseJSON(response);
	        if( typeof data.pin == 'number') {
	        	bssavingwallet.openModal();
	        	bssavingwallet.pin = data.pin;
	        	console.log(bssavingwallet.pin);
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
	$('#bs_pin_submit').submit(function(e){
		e.preventDefault();
		var user_code = $("#bs_verification_code").val();
		console.log(user_code == bssavingwallet.pin);
		if( user_code == bssavingwallet.pin ) {
			$(this).find('.bs_show_message').removeClass('error_message').addClass('success_message').text('✓ Success!');
	        $('#bs_phone').removeClass('error_message').addClass('success_message').text('Phone verified!');
			bssavingwallet.phone_verify = 'verified';
			window.setTimeout(bssavingwallet.closeModal, 3000);
			$('form#register_business').submit();
		} else {
			$(this).find('.bs_show_message').removeClass('success_message').addClass('error_message').text('x Failed');
		}
	});



});