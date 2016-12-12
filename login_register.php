<?php 
/*
Template name: Login Register 
*/

// redirect if logged in 
$wc_account =  get_permalink( get_option('woocommerce_myaccount_page_id') );
$redirect_url = $wc_account ? $wc_account : bloginfo('url');
if(is_user_logged_in()) wp_redirect($redirect_url);

get_header();

?>

	<div <?php echo apply_filters( 'listify_cover', 'page-cover page-cover--large', array( 'size' => 'full' ) ); ?>>
		<h1 class="page-title cover-wrapper"><?php the_post(); the_title(); rewind_posts(); ?>
	</div>

	<div id="primary" class="container">
	   <div class="row content-area">

		   <div class="log_reg_form">
				<div class="container">
					<div class="row">
						<div class="col-md-6 visible-mobile">
							<?php echo do_shortcode('[apsl-login-lite login_text="Login with Social Media"]'); ?>
						</div>
						<div class="col-md-6">
							<div class="form">

						      <ul class="tab-group">
						      	<li class="tab"><a href="#login">Log In</a></li>	
						        	<li class="tab"><a href="#signup">Sign Up</a></li>
						      </ul>
						      
						      <div class="tab-content">
						      	<div id="login">   
						         	<h1>Welcome Back!</h1>
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

						        	<div id="signup">   
						         	<h1>Sign Up for Free</h1>

									<!-- Signup Type Tab -->
									<ul class="tab-group">
										<li class="tab"><a href="#signup_customer">Customer</a></li>	
										<li class="tab"><a href="#signup_business">Business</a></li>
									</ul><!-- /Signup Type -->
										
							         <div id="signup_customer">
							         	<div class="result-message alert-danger alert"></div>
							         	<form id="register_customer" action="/" method="post">
							         		<h2>Register and start saving</h2>
											<div class="field-wrap">
												<label> Username <span class="req">*</span></label>
											  	<input type="text" name="customer_username" id="username" >
											</div>

									         <div class="top-row">
									            <div class="field-wrap">
									              	<label>First Name<span class="req">*</span></label>
									              	<input type="text" name="fname" id="fname">
									            </div>
									            <div class="field-wrap">
									              	<label>Last Name<span class="req">*</span></label>
									              	<input type="text" name="lname" id="lname">
									            </div>
									         </div>

								          	<div class="top-row">
												<div class="field-wrap">
													<select name="gender" id="gender">
														<option value="male">Male</option>
														<option value="female">Female</option>
													</select>
												</div>
												<div class="field-wrap">
													<label class="active">Date of birth<span class="req">*</span></label>
									              	<input type="date" id="datedropper" class="datedropper_customer" name="dd" data-theme="my-style" value="Date of birth">
									            </div>
								         	</div>

								          	<div class="top-row">
													<div class="field-wrap">
														<label>Email<span class="req">*</span></label>
														<input name="email" id="email" type="email">
														<span></span>
													</div>
													<div class="field-wrap">
														<label class="active hightlight">Phone<span class="req">*</span></label>
														<input id="phone_customer" name="phone_customer" class="phone_customer">
														<span id="valid-msg" class="hide">✓ Valid</span>
														<span id="error-msg" class="hide">Invalid number</span>
													</div>
								          	</div>

												<div class="field-wrap">
												  	<input id="streetaddress" class="streetaddress_customer" name="streetaddress" type="text">
												</div>

												<div class="field-wrap">
													<label> Apartment/Suite #</label>
												  	<input type="text" name="apartmentsuite" id="apartmentsuite">
												</div>


												<div class="top-row">
													<div class="field-wrap">
														<label> City </label>
														<input type="text" name="city" id="locality">
													</div><!-- /City -->

													<div class="field-wrap">
														<label> State </label>
														<input type="text" name="state" id="administrative_area_level_1">
													</div><!-- /State -->
												</div>

												<div class="top-row">
													<div class="field-wrap">
														<label> Zip </label>
														<input type="text" name="zip" id="postal_code">
													</div><!-- /Zip -->

													<div class="field-wrap">
														<label> Country </label>
														<input type="text" name="country" id="country">
													</div>
												</div><!-- /Country -->

												<div class="top-row">
													<div class="field-wrap">
														<label>Enter a password</label>
														<input type="password" name="pass" id="pass" id="password">
														<span></span>
													</div>
													<div class="field-wrap">
														<label>Re-enter password</label>
														<input type="password" name="confpass" id="confpass">
														<span></span>
													</div>
												</div>

								          	<?php wp_nonce_field('register_user','register_user_nonce', true, true ); ?>
								          
								          	<button type="submit" class="button button-block register_customer">Get Started</button>
								         </form><!-- /form signup_customer -->
							         </div><!-- /signup_customer Id -->

							         <div id="signup_business">
							         	<div class="result-message alert-danger alert"></div>
							         	<form id="register_business" action="/" method="post">
							         		<h2>List your business for free</h2>
											<div class="top-row">
												<div class="field-wrap">
													<label>Business name</label>
													<input type="text" name="business_name" id="business_name">
													<span></span>
												</div>
												<div class="field-wrap">
													<select name="business_type" id="business_type">
														<option value="accountant">Accountant</option>
														<option value="accountant">Arts & Entertainment</option>
														<option value="accountant">Automotive</option>
														<option value="accountant">Business Services</option>
														<option value="accountant">Cleaning Services</option>
														<option value="accountant">Doctors & Health Professionals</option>
														<option value="accountant">Grocery Store</option>
														<option value="accountant">Hair Salon & Spa</option>
														<option value="accountant">Home Improvement</option>
														<option value="accountant">Hotels & Lodging</option>
														<option value="accountant">Legal Services</option>
														<option value="accountant">Outdoors</option>
														<option value="accountant">Restaurants & Bars</option>
														<option value="accountant">Transportation</option>
													</select>
												</div>
								          	</div>

											<div class="field-wrap">
												<label> Username <span class="req">*</span></label>
											  	<input type="text" name="business_username" id="business_username" >
											</div>

									         <div class="top-row">
									            <div class="field-wrap">
									              	<label>First Name<span class="req">*</span></label>
									              	<input type="text" name="bs_fname" id="bs_fname">
									            </div>
									            <div class="field-wrap">
									              	<label>Last Name<span class="req">*</span></label>
									              	<input type="text" name="bs_lname" id="bs_lname">
									            </div>
									         </div>

								          	<div class="top-row">
												<div class="field-wrap">
													<select name="bs_gender" id="bs_gender">
														<option value="male">Male</option>
														<option value="female">Female</option>
													</select>
												</div>
												<div class="field-wrap">
													<label class="active">Date of birth<span class="req">*</span></label>
									              	<input type="date" id="bs_datedropper" class="datedropper_business" name="bs_dd"value="Date of birth">
									            </div>
								         	</div>

								          	<div class="top-row">
												<div class="field-wrap">
													<label>Email<span class="req">*</span></label>
													<input name="bs_email" id="bs_email" type="email">
													<span></span>
												</div>
												<div class="field-wrap">
													<label class="active hightlight">Phone<span class="req">*</span></label>
													<input id="bs_phone" name="bs_phone" class="bs_phone">
													<span id="valid-msg" class="hide">✓ Valid</span>
													<span id="error-msg" class="hide">Invalid number</span>
												</div>
								          	</div>

											<div class="field-wrap">
											  	<input id="bs_streetaddress" class="bs_streetaddress_customer" name="streetaddress" type="text">
											</div>

											<div class="field-wrap">
												<label> Apartment/Suite #</label>
											  	<input type="text" name="bs_apartmentsuite" id="abs_partmentsuite">
											</div>


											<div class="top-row">
												<div class="field-wrap">
													<label> City </label>
													<input type="text" name="bs_city" id="locality">
												</div><!-- /City -->

												<div class="field-wrap">
													<label> State </label>
													<input type="text" name="bs_state" id="administrative_area_level_1">
												</div><!-- /State -->
											</div>

											<div class="top-row">
												<div class="field-wrap">
													<label> Zip </label>
													<input type="text" name="bs_zip" id="postal_code">
												</div><!-- /Zip -->

												<div class="field-wrap">
													<label> Country </label>
													<input type="text" name="bs_country" id="country">
												</div>
											</div><!-- /Country -->

											<div class="top-row">
												<div class="field-wrap">
													<label>Enter a password</label>
													<input type="password" name="bs_pass" id="bs_password">
													<span></span>
												</div>
												<div class="field-wrap">
													<label>Re-enter password</label>
													<input type="password" name="bs_confpass" id="bs_confpass">
													<span></span>
												</div>
											</div>


								          	<?php wp_nonce_field('register_business','register_business_nonce', true, true ); ?>
								          
								          	<button type="submit" class="button button-block register_business">Get Started</button>
								         </form><!-- /form signup_Business -->
							         </div><!-- /signup_business -->

						        	</div><!-- /sinup -->

						      </div><!-- tab-content -->
						   </div><!-- /form -->
						</div>

						<div class="col-md-offset-2 col-md-4 hidden-mobile">
							<?php echo do_shortcode('[wordpress_social_login]'); ?>
						</div>
					</div>
				</div>
			</div>

	    </div> <!-- content area -->
	</div>
	<div class="remodal" data-remodal-id="phone_verification" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
	<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
	<h2 id="modal1Title">Verify Your Phone Number</h2>
	<div class="verification_content">
		<div class="verification_img">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/Smart_Phone.png" alt="">
		</div>
		<div id="modal1Desc">
			<form id="pin_submit" action="">
				<label for="verification_code">Enter Verification Code</label>
				<input type="number" name="verification_code" id="verification_code">
				<input type="submit" name="pin_submit" value="Verify" class="custom_btn">
				<div class="show_message"></div>
			</form>
		</div>
	</div>
</div>


<?php get_footer(); ?>