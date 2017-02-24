<?php 
/*
Template name: Cashback
*/
get_header();
if (!isset($_GET['customer_id']) || get_userdata( $_GET['customer_id'] ) == false ) exit;
$msw = new Mysavingwallet;
?>


	<div <?php echo apply_filters( 'listify_cover', 'page-cover page-cover--large', array( 'size' => 'full' ) ); ?>>
		<h1 class="page-title cover-wrapper"><?php the_post(); the_title(); rewind_posts(); ?>
	</div>

	<div id="primary" class="container">
	   <div class="row content-area">
	   		<?php if(!is_user_logged_in()) : ?>
				<div id="login">   
		         	<h1>Please login as a business to give cashback</h1>
		         	<div class="result-message alert-danger"></div>
					
		          	<form id="loginform" method="post" accept-charset="utf-8" autocomplete="off" role="form">
					
		            	<div class="field-wrap">
		            		<label class="active">Email Address<span class="req">*</span></label>
		            		<input type="text" name="username" required>
		            		<span class="message"></span>
		          		</div>
		          
						<div class="field-wrap">
							<label class="active">Password<span class="req">*</span></label>
							<input type="password" name="password" required>
						</div>

		          		<p class="forgot"><a href="<?php echo wp_lostpassword_url(); ?>">Forgot Password?</a></p>
		          		<?php wp_nonce_field('msw_login_user','msw_login_nonce', true, true ); ?>
		          		<button class="button button-block login_btn login">Log In</button>
		          	</form><!-- /form -->
		        </div><!-- / login -->
	   		<?php else : ?>
	   			<?php if($msw->get_user_role() == 'business') : ?>
	   				<div class="row">
	   					<div id="cashback_wrapper">
	   					<div class="col-md-5">
	   						<div class="cashback_wrapper">
	   							<p> Your current balance is: <strong><span class="balance"><?php echo $msw->wallet_balance(); ?></span></strong></p>
		   						<p> You are giving <strong><?php echo $full_amount = $msw->getMetaValue('cashback_percentage'); ?>%</strong> cashback. <br> Customer would receive a <strong><?php echo $full_amount/2; ?>%</strong> cashback.</p>
		   						<p></p>
					   			<div class="cashback">
					   				<p class="cashback_message"></p>
					   				<div class="col-md-4 padding-left-0">
					   					<input type="number" name="cashback_input" placeholder="Purchase amount">
					   					<p>Total sale amount</p>
					   				</div>
					   				<div class="col-md-4 padding-left-0">
					   					<input type="text" name="cashback_amount" id="cashback_amount" disabled>
					   					<p>Total cashback</p>
					   				</div>					   				
					   				<div class="col-md-4 padding-left-0 padding-right-0">
					   					<input type="text" name="cashback_amount" id="cashback_amount_half" disabled>
					   					<p>Customer receives</p>
					   				</div>
					   				<input type="hidden" id="customer_id" value="<?php echo $_GET['customer_id']; ?>">
					   				<button class="button button-block login_btn login" id="cashback_btn">Give cashback</button>
					   			</div>
	   						</div><!-- /cashback_wrapper -->
	   					</div>

	   					<div class="col-md-3">
							<div class="arrow_icon">
								<i class="fa fa-angle-double-right fa-3x"></i>
							</div>
						</div>

	   					<div class="col-md-4">
	   						<div class="user_info_cashback">
	   							<h4> Your are giving cashback to </h4>
	   							<div class="col-md-4 padding-left-0">
	   								<?php $user = get_userdata($_GET['customer_id']); ?>
	   								<?php echo get_avatar($_GET['customer_id']); ?>
	   							</div>
	   							<div class="col-md-8 padding-left-0 padding-right-0">
	   								<h5><?php echo $user->first_name; ?> <?php echo $user->last_name; ?></h5>
		   							<ul>
			   							<li>Email: <?php echo $user->user_email; ?></li>
			   							<li>Phone: <?php echo $msw->localize_us_number($user->billing_phone); ?></li>
					            		<li>User ID: <?php echo $_GET['customer_id']; ?></li>
				            		</ul>
	   							</div>
	   						</div>
	   					</div>
	   				 </div>
	   				</div>
	   			<?php else : ?>
	   			<p> You must be a business to give cashback</p>
	   		<?php endif; endif; ?>
	    </div> <!-- content area -->
	</div>

<?php get_footer(); ?>