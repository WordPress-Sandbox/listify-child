<?php 

// http://techslides.com/creating-a-wordpress-plugin-admin-page
// https://pippinsplugins.com/loading-scripts-correctly-in-the-wordpress-admin/

add_action( 'admin_menu', 'savingwallet_admin_page' );

function savingwallet_admin_page() {
	add_menu_page( 'Saving Wallet', 'Saving Wallet', 'manage_options', 'savingwallet-admin.php', 'savingwallet_admin', 'dashicons-tickets', 8);
}

function enqueue_admin_scripts($hook) {

	global $msw;

  /* css */
  wp_enqueue_style('jQueryaccordion', get_stylesheet_directory_uri() . '/inc/admin/assets/css/jquery.accordion.css');
  wp_enqueue_style('magnific-popup', get_stylesheet_directory_uri() . '/assets/magnific-popup/magnific-popup.css');
  wp_enqueue_style('datatables', '//cdn.datatables.net/v/dt/dt-1.10.13/datatables.min.css');
  /* report generate */
  wp_enqueue_style('datatable-button', 'https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css');
  wp_enqueue_style('bootstrapcss', '//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css');
  wp_enqueue_style('daterangepicker', '//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css');

  wp_enqueue_style('savingwallet', get_stylesheet_directory_uri() . '/inc/admin/assets/css/style.css');

  /* js */
  wp_enqueue_script('jQueryaccordion', get_stylesheet_directory_uri() . '/inc/admin/assets/js/jquery.accordion.js', array('jquery'));
  wp_enqueue_script('magnific-popup', get_stylesheet_directory_uri() . '/assets/magnific-popup/magnific-popup.js', array('jquery'));
  wp_enqueue_script('datatables', '//cdn.datatables.net/v/dt/dt-1.10.13/datatables.min.js', array('jquery'));
  /* report generate */
  wp_enqueue_script('datatable-button', 'https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js', array('jquery'));
  wp_enqueue_script('button-flash', '//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js', array('jquery'));
  wp_enqueue_script('jszip', '//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js', array('jquery'));
  wp_enqueue_script('pdfmake', '//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/pdfmake.min.js', array('jquery'));
  wp_enqueue_script('vfs_fonts', '//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/vfs_fonts.js', array('jquery'));
  wp_enqueue_script('buttons.html5', '//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js', array('jquery'));
  wp_enqueue_script('buttons.print', '//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js', array('jquery'));
  wp_enqueue_script('moment', '//cdn.jsdelivr.net/momentjs/latest/moment.min.js', array('jquery'));
  wp_enqueue_script('daterangepicker', '//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js', array('jquery'));

  wp_enqueue_script('savingwallet', get_stylesheet_directory_uri() . '/inc/admin/assets/js/script.js', array('jquery', 'datatables'));
  wp_localize_script('savingwallet', 'local', array('currency' => $msw->currency_symbol ));
}

if (is_admin() && $_GET['page'] == 'savingwallet-admin.php') {
	add_action( 'admin_enqueue_scripts', 'enqueue_admin_scripts' );
}


function savingwallet_admin(){
	global $msw;
	?>

	<div class="wrap">

		<h1> <?php echo esc_html('MySavingWallet Administration Panel'); ?></h1>

		<!-- navigation tabs -->
		<h2 class="nav-tab-wrapper" id="savingwallet_admin">
		    <a class="nav-tab nav-tab-active" data-tab="management-tab"> Management </a>
		    <a class="nav-tab" data-tab="bankinfo-tab"> Bank Info </a>
		    <a class="nav-tab" data-tab="withdrawls-tab"> Withdrawls </a>
		    <a class="nav-tab" data-tab="reports"> Reports </a>
		</h2>
		<!-- / navigation tabs -->

		<!-- management tab -->
		<div id="management-tab" class="tab-content current">
			<h3> Company balance: <?php echo $msw->currency_symbol; ?><span class="com_b"><?php echo get_option('company_balance'); ?></span></h3>
			<div class="add_user_balance">
				<h3> Search a user </h3>
				<form id="SearchUser">
					<p class="user_search_message"></p>
					<?php 
						if(function_exists('woocommerce_form_field')) {
							woocommerce_form_field('search_by', array(
								'type' => 'select',
								'class' => array('search_by'),
								'label' => __('By'),
								'options' => array(
									'name' => __('Name'),
									'email' => __('Email'),
									'login' => __('Username'),
									'ID' => __('ID'),
								)
							));
						} 
					?>
					<input type="submit" value="Search User" class="btn">
				</form>
				<div id="LoadUser"></div>
			</div>
			<div class="cashback_reports">
			<h3> Cashback reports </h3>
			<table id="cashbacks" class="display" cellspacing="0" width="100%">
				<thead>
		            <tr>
		                <th>Cashback ID</th>
		                <th>Customer ID</th>
		                <th>Business ID</th>
		                <th>Customer Balance</th>
		                <th>Business Balance</th>
		                <th>Company Balance</th>
		                <th>Amount</th>
		                <th>Date</th>
		                <th>Time</th>
		            </tr>
		        </thead>
			</table>
			</div>

		</div>
		<!-- management tab -->

		<!-- Bank info tab -->
		<div id="bankinfo-tab" class="tab-content">
			<h3> Banks Information </h3>
			<div class="pull-left"> 
				<ul class="bankinfo_filters admin_filters">
					<li><a href="#" data-load="all" class="btn active">All Banks</a></li>
					<li><a href="#" data-load="pending" class="btn">Pending</a></li>
					<li><a href="#" data-load="verified" class="btn">Verified</a></li>
					<li><a href="#" data-load="declined" class="btn">Declined</a></li>
				</ul>
			</div>

			<table id="bankinfo" class="display" cellspacing="0" width="100%">
		        <thead>
		            <tr>
		                <th>Customer ID</th>
		                <th>Customer Username</th>
		                <th>Customer Email</th>
		                <th>Customer Name</th>
		                <th>Bank Name</th>
		                <th>Account Type</th>
		                <th>Routing Number</th>
		                <th>Account Number</th>
		                <th>Support Doc</th>
		                <th>IP</th>
		                <th>Status</th>
		                <th>Note</th>
		                <th>Action</th>
		            </tr>
		        </thead>
		    </table>

		</div>
		<!-- / Bank info tab -->

		<!-- withdrawls tab -->
		<div id="withdrawls-tab" class="tab-content">
			<div class="pull-left"> 
				<ul class="withdrawls_filters admin_filters">
					<li><a href="#" data-load="all" class="btn active">All</a></li>
					<li><a href="#" data-load="pending" class="btn">Pending</a></li>
					<li><a href="#" data-load="approved" class="btn">Approved</a></li>
					<li><a href="#" data-load="declined" class="btn">Declined</a></li>
				</ul>
			</div>
			<table id="withdrawls" class="display" cellspacing="0" width="100%">
		        <thead>
		            <tr>
		                <th>Withdraw ID</th>
		                <th>Customer Name</th>
		                <th>Customer ID</th>
		                <th>Customer Email</th>
		                <th>Customer Username</th>
		                <th>Withdraw Date</th>
		                <th>Withdraw Time</th>
		                <th>Bank Name</th>
		                <th>Amount</th>
		                <th>Status</th>
		                <th>Note</th>
		                <th>Action</th>
		            </tr>
		        </thead>
		    </table>
		</div>
		<!-- / withdrawls tab -->


		<!-- withdrawls tab -->
		<div id="reports" class="tab-content">
			<h3> Generate Reports </h3>
			<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
			    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
			    <span></span> <b class="caret"></b>
			</div>

			<table id="generate_reports" class="display" cellspacing="0" width="100%">
				<thead>
		            <tr>
		                <th>Cashback ID</th>
		                <th>Customer ID</th>
		                <th>Business ID</th>
		                <th>Customer Balance</th>
		                <th>Business Balance</th>
		                <th>Company Balance</th>
		                <th>Amount</th>
		                <th>Date</th>
		                <th>Time</th>
		            </tr>
		        </thead>
			</table>
		</div>
		<!-- / withdrawls tab -->

	</div>

<?php } ?>