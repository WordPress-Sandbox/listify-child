jQuery(function($){

	//

	function SavingWallet() {
		this.errors = {};
		this.requiredFields = ['customer_username', 'fname', 'lname', 'dd', 'email', 'phone_customer', 'pass', 'confpass'];
		this.phone_verify = 'unverified';
	}

	// open remodal 
	SavingWallet.prototype.openModal = function() {
    	var inst = $('[data-remodal-id=phone_verification]').remodal();
    	inst.open();
	}	

	// close remodal 
	SavingWallet.prototype.closeModal = function() {
    	var inst = $('[data-remodal-id=phone_verification]').remodal();
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
		var telInput = $("#phone_customer");
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
	SavingWallet.prototype.hasEmptyInvalidField = function(){
		var that = this;
		$.each(this.requiredFields, function(i, v) {
			var val = $('input[name="' + v + '"]').val();
			var trimmedval = $.trim(val);
			if( v == 'phone_customer' && !that.isPhoneValid()) {
				$('input[name="phone_customer"]').addClass('error-msg');
				that.errors['phone_customer'] = 'Invalid phone';
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


	// inttelinput 
	var telInput = $("#phone_customer"),
	  errorMsg = $("#error-msg"),
	  validMsg = $("#valid-msg");

	// initialise plugin
	telInput.intlTelInput({
	  utilsScript: local.themepath + '/assets/inttelinput/js/utils.js',
	  // onlyCountries: ["us"],
	  preferredCountries: []
	});

	var reset = function() {
	  telInput.removeClass("error");
	  errorMsg.addClass("hide");
	  validMsg.addClass("hide");
	  delete savingwallet.errors['phone'];
	};

	// on blur: validate
	telInput.blur(function() {
	  reset();
	  if ($.trim(telInput.val())) {
	    if (telInput.intlTelInput("isValidNumber")) {
	      validMsg.removeClass("hide");
	      delete savingwallet.errors['phone'];
	    } else {
	      telInput.addClass("error");
	      errorMsg.removeClass("hide");
	      savingwallet.errors['phone'] = 'Invalid phone number';
	    }
	  }
	});

	// on keyup / change flag: reset
	telInput.on("keyup change", reset);


	var emailInput = $("#email");
	emailInput.blur(function() {
	  $(this).next().text('');
	  if ($.trim(emailInput.val())) {
	    if (savingwallet.isValidEmail($.trim(emailInput.val()))) {
	      $(this).next().attr('id', 'valid-msg').text('✓ Valid email');
	      delete savingwallet.errors['email'];
	    } else {
	    	$(this).next().attr('id', 'error-msg').text('Invalid email');
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

	$('.streetaddress_customer, .streetaddress_business').on('keyup blur focus change', function(){
		$('#locality, #administrative_area_level_1, #postal_code, #country').prev('label').addClass('active highlight');
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

	$(document).ready(function () {
	   $("#pass, #confpass").on( 'keyup blur focus change', checkPasswordMatch);
	});


	/* Customer registration */
	$('form#register_customer').submit( function(event) {

		event.preventDefault();

	    var that = $(this);
	    var btn = that.find('.register_customer');

	    // console.log(savingwallet.phone_verify);
	    // console.log($('#phone').val());

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
			username 		: $('#username').val(),
			firstname 		: $('#fname').val(),
			lastname 		: $('#lname').val(),
			gender 			: $('#gender').val(),
			dd 				: $('#dd').val(),
			email 			: $('#email').val(),
			phone 			: $('#phone_customer').val(),
			streetaddress 	: $('#streetaddress').val(),
			apartmentsuite 	: $('#apartmentsuite').val(),
			city 			: $('#locality').val(),
			state 			: $('#administrative_area_level_1').val(),
			postal_code 	: $('#postal_code').val(),
			country 		: $('#country').val(),
			pass 			: $('#pass').val(),
			phone_verify 	: savingwallet.phone_verify,
	    };
	 
	    // Do AJAX request
	    $.post( local.ajax_url, data, function(response) {
	      if( response ) {
	        var data = $.parseJSON(response);
	        if( typeof data.pin == 'number') {
	        	savingwallet.openModal();
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
			$(this).find('.show_message').removeClass('phone_failed').addClass('phone_success').text('✓ Success!');
	        $('#valid-msg').removeClass('hide').text('Phone verified!');
			savingwallet.phone_verify = 'verified';
			window.setTimeout(savingwallet.closeModal, 3000);
			$('form#register_customer').submit();
		} else {
			$(this).find('.show_message').removeClass('phone_success').addClass('phone_failed').text('x Failed');
		}
	});


	// Business registration 
	$('form#register_business').submit(function(event){

		event.preventDefault();
	    var that = $(this);
	    var btn = that.find('.register_business');

	   	// console.log(savingwallet.phone_verify);
	    // console.log($('#phone').val());

	    btn.text('Please wait...');

	    savingwallet.hasEmptyInvalidField();

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
			username 		: $('#username').val(),
			firstname 		: $('#fname').val(),
			lastname 		: $('#lname').val(),
			gender 			: $('#gender').val(),
			dd 				: $('#datedropper').val(),
			email 			: $('#email').val(),
			phone 			: $('#phone').val(),
			streetaddress 	: $('#streetaddress').val(),
			apartmentsuite 	: $('#apartmentsuite').val(),
			city 			: $('#locality').val(),
			state 			: $('#administrative_area_level_1').val(),
			postal_code 	: $('#postal_code').val(),
			country 		: $('#country').val(),
			pass 			: $('#pass').val(),
			phone_verify 	: savingwallet.phone_verify,
	    };
	 
	    // Do AJAX request
	    $.post( local.ajax_url, data, function(response) {
	      if( response ) {
	        var data = $.parseJSON(response);
	        if( typeof data.pin == 'number') {
	        	savingwallet.openModal();
	        	savingwallet.pin = data.pin;
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

});