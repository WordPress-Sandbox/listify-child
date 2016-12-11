jQuery(function($){

	//

	function SavingWallet() {
		this.errors = {};
		this.phone = 'required';
		this.isEmpty = 	function(obj) {
		    for(var key in obj) {
		        if(obj.hasOwnProperty(key))
		            return false;
		    }
		    return true;
		};
	}

	var savingwallet = new SavingWallet();


	$('.form').find('textarea, input:not([type="date"])').on('keyup blur focus change', function (e) {
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


	// inttelinput 
	var telInput = $(".phone_customer, .phone_business"),
	  errorMsg = $("#error-msg"),
	  validMsg = $("#valid-msg");

	// initialise plugin
	telInput.intlTelInput({
	  utilsScript: local.themepath + '/assets/inttelinput/js/utils.js',
	  onlyCountries: ["us"],
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

	// varify emails 
	function isEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}

	var emailInput = $("#email");
	emailInput.blur(function() {
	  $(this).next().text('');
	  if ($.trim(emailInput.val())) {
	    if (isEmail($.trim(emailInput.val()))) {
	      $(this).next().attr('id', 'valid-msg').text('✓ Valid email');
	      delete savingwallet.errors['email'];
	    } else {
	    	$(this).next().attr('id', 'error-msg').text('Invalid email');
	    	savingwallet.errors['email'] = 'Invalid email';
	    }
	  }
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


	/* register user */

	$('button.register').click( function(event) {

	    that = $(this);

	    if (event.preventDefault) {
	        event.preventDefault();
	    } else {
	        event.returnValue = false;
	    }

	    if(!savingwallet.isEmpty(savingwallet.errors)) {
			$('.result-message').html('Please fill required fields');
			$('.result-message').addClass('alert-danger');
			$('.result-message').show();
			return;
	    }

	    that.text('Please wait...');
	 
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
			confpass 		: $('#confpass').val(),
			phone_verify 	: savingwallet.phone,
	    };
	 
	    // Do AJAX request
	    $.post( local.ajax_url, data, function(response) {
	      if( response ) {
	        var data = $.parseJSON(response);
	        if( typeof data.pin == 'number') {
	        	var inst = $('[data-remodal-id=phone_verification]').remodal();
	        	inst.open();
	        	savingwallet.pin = data.pin;
	        	console.log(savingwallet.pin);
	        	return;
	        }
	        if( data == 'success' ) {
	          that.text('Redirecting...');
	          location.reload();
	        } else {
	          $('.result-message').html(data);
	          $('.result-message').addClass('alert-danger');
	          $('.result-message').show();
	          that.text('Get started now');
	        }
	      }
	    });
	 
	});


	// Phone verification 
	$('#pin_submit').click(function(e){
		e.preventDefault();
		var user_code = $("#verification_code").val();
		console.log(user_code == savingwallet.pin);
		if( user_code == savingwallet.pin ) {
			console.log(this);
			$(this).append('<span class="success"> Success!</span>');
			var inst = $('[data-remodal-id=phone_verification]').remodal();
	        	inst.close();
	        $('#valid-msg').removeClass('hide').text('Phone verified!');
			savingwallet.phone = 'verified';
		}
	});


	// User login 
	$(".login_btn").click(function(){
	    $(".login_btn_head").show();
	});

	$('button.login').click( function(event) {

	    that = $(this);

	    if (event.preventDefault) {
	        event.preventDefault();
	    } else {
	        event.returnValue = false;
	    }

	    that.text('Please wait...');

	    var reg_nonce = $('#msw_login_nonce').val();
	    var username  = $('#username').val();
	    var password  = $('#password').val();
	 
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
	        console.log(data);
	        if( data == 'success' ) {
	          that.text('Redirecting...');
	          //window.location.href = ajax.profile_page;
	          localStorage.clear(); // clear search data to make favorite icon work
	          location.reload();
	        } else {
	          $('.result-message').html(data);
	          $('.result-message').addClass('alert-danger');
	          $('.result-message').show();
	          that.text('Login');
	        }
	      }
	    });
	 
	});



});