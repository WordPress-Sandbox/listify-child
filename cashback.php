<?php 
/*
Template name: Cashback
*/
get_header();
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
	   			<?php show_deposit_balance(); ?>
	   			<div class="cashback">
	   				<p class="cashback_message"></p>
	   				<input type="number" name="cashback_amout" id="cashback_amout">
	   				<input type="hidden" id="customer_id" value="<?php echo $_GET['customer_id']; ?>">
	   				<button class="button button-block login_btn login" id="cashback_btn">Give cashback</button>
	   			</div>
	   		<?php endif; ?>
	    </div> <!-- content area -->
	</div>

<?php get_footer(); ?>