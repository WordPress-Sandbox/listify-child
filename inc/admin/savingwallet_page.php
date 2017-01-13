<?php 


// http://techslides.com/creating-a-wordpress-plugin-admin-page
// https://pippinsplugins.com/loading-scripts-correctly-in-the-wordpress-admin/

add_action( 'admin_menu', 'savingwallet_admin_page' );

function savingwallet_admin_page() {
	add_menu_page( 'Saving Wallet', 'Saving Wallet', 'manage_options', 'savingwallet-admin.php', 'savingwallet_admin', 'dashicons-tickets', 8);
	add_submenu_page( 'savingwallet-admin.php', 'Withdraw Request', 'Withdraw Request', 'manage_options', 'withdraw-request.php', 'withdraw_request' ); 
	add_submenu_page( 'savingwallet-admin.php', 'Bank Verification', 'Bank Verification', 'manage_options', 'bank-verification.php', 'bank_verification' ); 
}


function enqueue_admin_scripts($hook) {

  // if( 'bank-verification.php' != $hook ) {
  // 	return;
  // }  

  /* css */
  wp_enqueue_style('jQueryaccordion', get_stylesheet_directory_uri() . '/inc/admin/assets/css/jquery.accordion.css');
  wp_enqueue_style('magnific-popup', get_stylesheet_directory_uri() . '/assets/magnific-popup/magnific-popup.css');
  wp_enqueue_style('savingwallet', get_stylesheet_directory_uri() . '/inc/admin/assets/css/style.css');
  /* js */
  wp_enqueue_script('jQueryaccordion', get_stylesheet_directory_uri() . '/inc/admin/assets/js/jquery.accordion.js', array('jquery'));
  wp_enqueue_script('magnific-popup', get_stylesheet_directory_uri() . '/assets/magnific-popup/magnific-popup.js', array('jquery'));
  wp_enqueue_script('savingwallet', get_stylesheet_directory_uri() . '/inc/admin/assets/js/script.js', array('jquery'));
}

add_action( 'admin_enqueue_scripts', 'enqueue_admin_scripts' );


function savingwallet_admin(){
global $msw;
	?>
	<div class="wrap">
		<h1> Saving Wallet Settings </h1>
		<h3> Company balance: <?php echo $msw->currency_symbol; ?><?php echo get_option('company_balance'); ?></h3>
		<h4> Lastest Cashbacks </h4>
		<?php echo do_shortcode('[cashbacks]'); ?>
	</div>

<?php 
}


function bank_verification() {

	$user_ids = get_users(array('role__in' => array('customer'), 'fields' => 'ID'));
	$counter = 1;
?>
<div class="wrap">
<h1> Banks Required Verification </h1>
<section id="banks_verification" data-accordion-group="">
	<div data-accordion-group>

	<?php

		foreach ($user_ids as $id) : 
		$banks = get_user_meta($id, 'banks', true);
		if(is_array($banks)) :
			foreach ($banks as $key => $bank) :
				if($bank['verification'] === 'pending') : 
				?>

				<div class="accordion" data-accordion>
			        <div data-control> Bank #<?php echo $counter; $counter++; ?></div>
			        <div data-content>
			            <div>
			            	<h3 class="message"></h3>
			            	<div class="basic_info">
			            	<ul>
			            		<?php $user = get_userdata($id); ?>
			            		<li>Customer ID: <?php echo $id; ?></li>
			            		<li>Customer username: <?php echo $user->user_login; ?></li>
			            		<li>Customer Email: <?php echo $user->user_email; ?></li>
			            		<li>Customer name: <?php echo $user->display_name; ?></li>
			            		<li>Bank name: <?php echo $bank['bank_name']; ?></li>
			            		<li>Account type: <?php echo $bank['account_type']; ?></li>
			            		<li>Routing number: <?php echo $bank['bank_routing']; ?></li>
			            		<li>Account number: <?php echo $bank['account_number']; ?></li>
			            	</ul>
			            	<div class="btn_area">
			            		<a class="verify_btn" data-status="verified" data-userid="<?php echo $id; ?>" data-bankkey="<?php echo $key; ?>">Check as verified</a>
			            		<a class="verify_btn" data-status="declined" data-userid="<?php echo $id; ?>" data-bankkey="<?php echo $key; ?>">Check as Declined</a>
			            	</div>
			            	</div>
			            	<div class="support_docs">
			            		<h4> Support Docs</h4>
			            		<ul>
			            			<?php if(is_array($bank['attachment_ids'])) : foreach($bank['attachment_ids'] as $id ) : 
			            				$image_atts = wp_get_attachment_image_src( $id );
			            			?>
			            				<li><a class="magnific-popup" href="<?php echo wp_get_attachment_url($id); ?>"><img src="<?php echo $image_atts[0]; ?>" /></a></li>
			            			<?php endforeach; endif; ?>
			            		</ul>
			            	</div>
			            </div>
			        </div>
			    </div>

				<?php 
				endif;
				
			endforeach;
		endif; 
	endforeach;

	?>

	</div>
</section>
</div>
<?php
}



function withdraw_request() {

	$user_ids = get_users(array('role__in' => array('customer'), 'fields' => 'ID'));
	$counter = 1;
?>
<div class="wrap">
<h1> Pending Withdrawls </h1>
<section id="banks_verification" data-accordion-group="">
	<div data-accordion-group>

	<?php

		foreach ($user_ids as $id) : 
		$withdrawls = get_user_meta($id, 'withdrawls', true);
		if(is_array($withdrawls)) :
			foreach ($withdrawls as $key => $with) :
				if($with['status'] === 'pending') :
					// $mysavingwallet = new Mysavingwallet;
					// $bank = $mysavingwallet->filterBank('bank_name', 'Rupali Bank Ltd.');
					// var_dump($bank);
				?>

				<div class="accordion" data-accordion>
			        <div data-control> Withdrawl #<?php echo $counter; $counter++; ?></div>
			        <div data-content>
			            <div>
			            	<h3 class="message"></h3>
			            	<ul>
			            		<?php $user = get_userdata($id); ?>
			            		<li>Customer ID: <?php echo $id; ?></li>
			            		<li>Customer username: <?php echo $user->user_login; ?></li>
			            		<li>Customer Email: <?php echo $user->user_email; ?></li>
			            		<li>Customer name: <?php echo $user->display_name; ?></li>
			            		<li>Withdraw ID: <?php echo $with['id']; ?></li>
			            		<li>Withdraw Amount: <?php echo $with['amount']; ?></li>
			            		<li>Withdraw Time: <?php echo $with['time']; ?></li>
			            		<li>Bank Name: <?php echo $with['bank']; ?></li>
			            	</ul>
			            	<div class="btn_area">
			            		<a class="withdrawl_btn" data-status="approved" data-userid="<?php echo $id; ?>" data-withdrawkey="<?php echo $key; ?>">Approve</a>
			            		<a class="withdrawl_btn" data-status="declined" data-userid="<?php echo $id; ?>" data-withdrawkey="<?php echo $key; ?>">Decline</a>
			            	</div>
			            </div>
			        </div>
			    </div>

				<?php 
				endif;
				
			endforeach;
		endif; 
	endforeach;

	?>

	</div>
</section>
</div>
<?php
}
?>