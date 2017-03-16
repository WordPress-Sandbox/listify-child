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

	$(document).ready(function() {
	    $('#bankinfo tbody').on('click', 'tr', function (e) {
	    	e.preventDefault();
	    	var el = $(this).find('.magnific-popup');
	    	$.each(el, function(e) {
			    $(this).magnificPopup({
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
	    	})
	    });
	});


	/* add notes 
	http://stackoverflow.com/questions/33267797/turn-text-element-into-input-field-type-text-when-clicked-and-change-back-to-tex
	*/
	$('body').on('click', '.bankNote, .withNote', function(){
  
		  var $el = $(this),
		  $notetype = $el.attr('class'),
		  $userid = $el.attr('data-userid'), 
		  $routing = $el.attr('data-routing');
		  $withid = $el.attr('data-withid');

		  $datatype = ($notetype == 'bankNote') ? 'data-routing=' + $routing : 'data-withid=' + $withid ;
		              
		  var $input = $('<textarea/>').val( $el.text() );
		  $el.replaceWith( $input );
		  
		  var save = function(){

		  	var updateVal = $input.val(); 
		  	if(updateVal)  {
		  		var updateVal = updateVal;
		  	} else {
		  		var updateVal = 'Empty';
		  	}

		  	var $p = $('<p class="'+ $notetype +'" data-userid="'+ $userid +'"' + $datatype + '/>').text( updateVal );

		    $input.replaceWith( $p );
    		$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: { 
					action: 'update_admin_notes', 
					userid: $userid, 
					notetype: $notetype, 
					routing: $routing, 
					withid: $withid, 
					note: $input.val()
				},
				dataType: 'json',
				success: function(resp) {
					console.log(resp);
				},
				error: function( req, status, err ) {
					console.log('something went wrong', status, err);
				}
			});
		  };
		  
		  /**
		    We're defining the callback with `one`, because we know that
		    the element will be gone just after that, and we don't want 
		    any callbacks leftovers take memory. 
		    Next time `p` turns into `input` this single callback 
		    will be applied again.
		  */
		  $input.one('blur', save).focus();
		  
		});


	/* verify, unverify customer account */
	$('body').on('click', '.verify_btn', function(e){
		e.preventDefault();
		var _that = $(this);
		var data = {
			action: 'verify_unverify_banks',
			userid: _that.data('userid'),
			routing: _that.data('routing'),
			status: _that.data('status'),
		}
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: data,
			dataType: 'json',
			success: function(resp) {
				console.log(resp)
				alert('Bank status set to ' + resp.status);
				_that.parent().parent().remove();
			},
			error: function( req, status, err ) {
				$('.message').text('something went wrong', status, err);
			}
		});
	});

	/* approve, decline customer withdraw */
	$('body').on('click', '.verify_with', function(e){
		e.preventDefault();
		var _that = $(this);
		var data = {
			action: 'approve_decline_withdraw',
			userid: _that.data('userid'),
			withid: _that.data('withid'),
			status: _that.data('status'),
		}
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: data,
			dataType: 'json',
			success: function(resp) {
				console.log(resp)
				alert('Withdraw status set to ' + resp.status);
				_that.parent().parent().remove();
			},
			error: function( req, status, err ) {
				$('.message').text('something went wrong', status, err);
			}
		});
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
		            { "data": "id" },
		            { "data": "name" },
		            { "data": "customer" },
		            { "data": "email" },
		            { "data": "username" },
		            { "data": "date" },
		            { "data": "time" },
		            { "data": "bank" },
		            { "data": "amount" },
		            { "data": "status" },
		            { "data": "note" },
		            { "data": "action" }
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
		        	{ "data": "date" },
		            { "data": "time" },
		            { "data": "transaction_id" },
		            { "data": "business_id" },
		            { "data": "business_name" },
		            { "data": "customer_id" },
		            { "data": "customer_name" },
		            { "data": "customer_balance" },
		            { "data": "business_balance" },
		            { "data": "comtranpro" },
		            { "data": "combalance" },
		        ]
			});
		};

	CashbackListQuery();

	/* banks information */
	function banksReportQuery(qu) {
		var _this = this;
		var query = JSON.stringify(qu);
		$('#bankinfo').DataTable({
			bLengthChange :true,
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
	            { "data": "ip" },
	            { "data": "status" },
	            { "data": "note" },
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

	// enable links to be worked 

	$( "#bankinfo tbody" ).on('click', 'a[target="_blank"]',  function(){
		 var url = $(this).attr('href'); 
		 window.open(url, '_blank');
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

	/* search user, debit credit balance */

	function userSearchInputField() {
		var el = $('#search_by'), value = el.val();
		el.next().remove();
		el.after('<input type="text" name="search_input" class="search_input" placeholder="User '+ value +'">');
	}
	userSearchInputField();
	$('#search_by').on('change', userSearchInputField);


	$('#SearchUser').submit(function(e){
		e.preventDefault();
		var _that = $(this);
		var select = _that.find('#search_by').val();
		var search_input = _that.find('.search_input').val();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: { action: 'SearchUser', select: select, search_input: search_input },
			dataType: 'json',
			success: function(resp) {
				$('.user_search_message').empty();
				$('#LoadUser').empty().html(output);
				if(resp.status == 'SUCCESS') {
					let resArray = resp.responsetext;
					// if( resArray.isArray ) {
						for( let res of resArray) {
							var output = `<div class="userfound">
							<div class="user_img"><img src="` + res.avatar + `"></div>
							<div class="userinfo">`
							 + ` User name: <strong>` + res.name + `</strong><br/>`
							 + ` User email: ` + res.email + `<br/>`
							 + ` Role: ` + res.roles[0] + `<br/>`
							 + ` Balance: ` + local.currency + `<span class="balance">` + res.balance + `</span><br/>`
							+ `</div>
							<a class="CreditBalance" data-action="credit" data-userid="` + res.userid +`">Credit Balance </a>
							<a class="DebitBalance" data-action="debit" data-userid="` + res.userid + `"> Debit Balance </a>
							</div>`;
							$('#LoadUser').append(output);
						}
					// }
				} else {
					$('#LoadUser').empty();
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
				if(resp.status == 'SUCCESS') {
					_that.siblings('.userinfo').find('.balance').text(resp.user_balance);
					$('.com_b').text(resp.company_balance)
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


	/* generate reports */
	var reportrange = function() {

	    var start = moment().subtract(29, 'days');
	    var end = moment();

	    function cb(start, end) {
	        $('#reportrange span').html(start.format('MMMM D YYYY, HH:MM') + ' - ' + end.format('MMMM D YYYY, HH:MM'));

			/* load report to datatables */
			var query = { 
				start_time : start.format('YYYY-MM-DD HH:MM'),
				end_time : end.format('YYYY-MM-DD HH:MM')
			};

			reportDataTable(query);

		};

		function reportDataTable(qu) {
			var timeframe = JSON.stringify(qu);
			$('#generate_reports').DataTable({
				processing: true,
				serverSide: false,
				responsive: true,
				paging: true,
				destroy: true,
				sAjaxDataProp: 'data[]',
				dom: 'Bfrtip',
		        buttons: [
		            'print',
		            'csvFlash',
		            'excelFlash',
		            'pdfFlash'
		        ],
				ajax: ajaxurl +'?action=generate_report&timeframe='+timeframe,
				// aoColumnDefs: [
			 //      { "bSortable": false, "aTargets": [ 0, 4, 5, 6 ] }
			 //    ],
			 	oLanguage: {
				    "sEmptyTable": "No report available within your timeframe"
				},
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
		}

	    $('#reportrange').daterangepicker({
			startDate: start,
			endDate: end,
			timePicker: true,
			timePicker24Hour: true,
			alwaysShowCalendars: true,
			showCustomRangeLabel: false,
			opens: 'left',
	        ranges: {
	           'Today': [moment(), moment()],
	           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	           'This Month': [moment().startOf('month'), moment().endOf('month')],
	           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	        },

	    }, cb);

	    cb(start, end);
	}

	reportrange();

})