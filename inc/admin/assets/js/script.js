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
		$('#savingwallet_admin a').removeClass('nav-tab-active');
		$('.tab-content').removeClass('current');
		$(this).addClass('nav-tab-active');
		$("#"+tab_id).addClass('current');
	});


	/* withdraw reports */
	function withdrawlsQuery(qu) {
			var _this = this;
			var query = JSON.stringify(qu);
			$('#withdrawls').DataTable({
				processing: true,
				serverSide: false,
				responsive: true,
				paging: true,
				destroy: true,
				sAjaxDataProp: 'data[]',
				oLanguage: {
				    "sEmptyTable": "No " + qu.load + " withdraw available"
				},
				ajax: ajaxurl +'?action=withdrawls_report&query='+query,
				// aoColumnDefs: [
			 //      { "bSortable": false, "aTargets": [ 0, 4, 5, 6 ] }
			 //    ],
		        columns: [
		            { "data": "name" },
		            { "data": "id" },
		            { "data": "email" },
		            { "data": "username" },
		            { "data": "date" },
		            { "data": "time" },
		            { "data": "bank" },
		            { "data": "amount" }
		        ]
			});
		}

	withdrawlsQuery({load: 'all' });

	$('.withdrawls_filters li a').on('click', function(e){
		e.preventDefault();
		var load = $(this).attr('data-load');
		withdrawlsQuery({load: load });
	});


	/* admin cashback lists */
	function CashbackListQuery() {
			$('#cashbacks').DataTable({
				processing: true,
				serverSide: false,
				responsive: true,
				paging: true,
				destroy: true,
				sAjaxDataProp: 'data[]',
				oLanguage: {
				    "sEmptyTable": "No cashback available."
				},
				ajax: ajaxurl +'?action=cashback_report',
				// aoColumnDefs: [
			 //      { "bSortable": false, "aTargets": [ 0, 4, 5, 6 ] }
			 //    ],
		        columns: [
		            { "data": "cashback_id" },
		            { "data": "customer_id" },
		            { "data": "business_id" },
		            { "data": "customer_balance" },
		            { "data": "business_balance" },
		            { "data": "company_balance" },
		            { "data": "amount" },
		            { "data": "date" },
		            { "data": "time" }
		        ]
			});
		};

	CashbackListQuery();

	/* banks information */
	function banksReportQuery(qu) {
		var _this = this;
		var query = JSON.stringify(qu);
		$('#bankinfo').DataTable({
			processing: true,
			serverSide: false,
			responsive: true,
			paging: true,
			destroy: true,
			sAjaxDataProp: 'data[]',
			oLanguage: {
			    "sEmptyTable": "No " + qu.load + " banks available"
			},
			ajax: ajaxurl +'?action=bank_report&query='+query,
			// aoColumnDefs: [
		 //      { "bSortable": false, "aTargets": [ 0, 4, 5, 6 ] }
		 //    ],
	        columns: [
	            { "data": "customer_id" },
	            { "data": "customer_username" },
	            { "data": "customer_email" },
	            { "data": "customer_name" },
	            { "data": "bank_name" },
	            { "data": "account_type" },
	            { "data": "routing_number" },
	            { "data": "account_number" },
	            { "data": "support_doc" },
	            { "data": "action_btn" }
	        ]
		});
	}

	banksReportQuery({load: 'all'});
	$('.bankinfo_filters li a').on('click', function(e){
		e.preventDefault();
		var load = $(this).attr('data-load');
		banksReportQuery({load: load });
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
				$('.user_search_message').empty();
				if(resp.status == 'SUCCESS') {
					let res = resp.responsetext;
					let output = `<div class="userfound">
					<div class="user_img"><img src="` + res.avatar + `"></div>
					<div class="userinfo">`
					 + ` User name: <strong>` + res.name + `</strong><br/>`
					 + ` User email: ` + res.email + `<br/>`
					 + ` Role: ` + res.roles[0] + `<br/>`
					 + ` Balance: ` + local.currency + `<span class="balance">` + res.balance + `</span><br/>`
					+ `</div>
					<a class="CreditBalance" data-action="credit" data-userid="` + userid +`">Credit Balance </a>
					<a class="DebitBalance" data-action="debit" data-userid="` + userid + `"> Debit Balance </a>
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
		let inputField = `<form id="debitCredit">`+local.currency+`<input type="text" name="debit_credit_amount"/>`;
			inputField += `<input type="hidden" name="process" value="`+ $(this).attr('data-action') +`"/>`;
			inputField += `<input type="hidden" name="user_id" value="`+ $(this).attr('data-userid') +`"/>`;
			inputField += `<input type="submit" value="` + $(this).attr('data-action') + ` Balance" /></form>`;
		$(this).after(inputField);
	});


	$('body').on( 'submit', '#debitCredit', function(e){
		e.preventDefault();
		var _that = $(this);
		var data = { action: 'debitCreditUserBalance' };
		$.each($(this).serializeArray(), function(i, field) {
		    data[field.name] = field.value;
		});

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: data,
			dataType: 'json',
			success: function(resp) {
				console.log(resp);
				if(resp.status == 'SUCCESS') {
					$('body').find('span.balance').text(resp.responsetext.balance);
					_that.after('<p style="color:green"> '+ resp.responsetext + '</p>');
				} else if (resp.status == 'ERROR') {
					_that.after('<p style="color:red"> '+ resp.responsetext + '</p>');
				}
			},
			error: function( req, status, err ) {
				_that.after('<p style="color:green"> something went wrong ' + status, err + '</p>');
			}
		});

	});


})