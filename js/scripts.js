jQuery(function($){

	// 
	function isEmpty(obj) {
	    for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
	    }
	    return true;
	}

	function SavingWallet() {
		this.errors = {};
	}

	var savingwallet = new SavingWallet();


	$('.form').find('input, textarea').on('keyup blur focus change', function (e) {
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

	    if (password != confirmPassword) {
	        $("#pass, #confpass").next().attr('id', 'error-msg').text('Passwords do not match!');
	    	savingwallet.errors['pass'] = 'Passwords not match';
	    } else { 
	        $("#pass, #confpass").next().attr('id', 'valid-msg').text('✓ Passwords match');
	    	delete savingwallet.errors['pass'];
	    }
	}

	$(document).ready(function () {
	   $("#pass, #confpass").on( 'keyup, focus', checkPasswordMatch);
	});


	/* register user */

	$('.send_message').click(function(e){

		e.preventDefault();

		var data_message = {
			action: 'send_sms_localhost',
			nonce : local.nonce
		}

		$.post( local.ajax_url, data_message, function(res) {
			var res = $.parseJSON(res);
			console.log(res);
		});

	});




$('button.register').click( function(event) {

    that = $(this);

    if (event.preventDefault) {
        event.preventDefault();
    } else {
        event.returnValue = false;
    }

    if(!isEmpty(savingwallet.errors)) {
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
    };
 
    // Do AJAX request
    $.post( local.ajax_url, data, function(response) {
      if( response ) {
        var data = $.parseJSON(response);
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

});