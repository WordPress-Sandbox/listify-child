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
						          
						          	<form action="/" method="post">
						            	<div class="field-wrap">
						            		<label>Email Address<span class="req">*</span></label>
						            		<input type="email"required autocomplete="off"/>
						            		<span class="message"></span>
						          		</div>
						          
											<div class="field-wrap">
												<label>Password<span class="req">*</span></label>
												<input type="password"required autocomplete="off"/>
											</div>
						          		<p class="forgot"><a href="#">Forgot Password?</a></p>
						          		<button class="button button-block">Log In</button>
						          	</form><!-- /form -->
						        	</div><!-- / login -->

						        	<div id="signup">   
						         	<h1>Sign Up for Free</h1>

										<!-- Signup Type Tab -->
										<ul class="tab-group">
											<li class="tab"><a href="#signup_customer">Customer</a></li>	
											<li class="tab"><a href="#signup_business">Business</a></li>
										</ul><!-- /Signup Type -->

						         	<div class="result-message alert-danger alert"></div>
										
							         <div id="signup_customer">
							         	<form id="register" action="/" method="post">

												<div class="field-wrap">
													<label> Username </label>
												  	<input type="text" name="username" id="username" />
												</div>

									         <div class="top-row">
									            <div class="field-wrap">
									              	<label>First Name<span class="req">*</span></label>
									              	<input type="text" name="fname" id="fname" required/>
									            </div>
									            <div class="field-wrap">
									              	<label>Last Name<span class="req">*</span></label>
									              	<input type="text" name="lname" id="lname" required/>
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
									              	<input type="date" id="datedropper" class="datedropper_customer" name="dd" data-theme="my-style" value="Date of birth"/>
									            </div>
								         	</div>

								          	<div class="top-row">
													<div class="field-wrap">
														<input name="email" id="email" type="email" placeholder="Email*" required/>
														<span></span>
													</div>
													<div class="field-wrap">
														<input id="phone" name="phone" class="phone_customer" placeholder="Phone*" />
														<span id="valid-msg" class="hide">✓ Valid</span>
														<span id="error-msg" class="hide">Invalid number</span>
													</div>
								          	</div>

												<div class="field-wrap">
												  	<input id="streetaddress" class="streetaddress_customer" name="streetaddress" type="text">
												</div>

												<div class="field-wrap">
													<label> Apartment/Suite #</label>
												  	<input type="text" name="apartmentsuite" id="apartmentsuite" />
												</div>


												<div class="top-row">
													<div class="field-wrap">
														<label> City </label>
														<input type="text" name="city" id="locality"/>
													</div><!-- /City -->

													<div class="field-wrap">
														<label> State </label>
														<input type="text" name="state" id="administrative_area_level_1"/>
													</div><!-- /State -->
												</div>

												<div class="top-row">
													<div class="field-wrap">
														<label> Zip </label>
														<input type="text" name="zip" id="postal_code"/>
													</div><!-- /Zip -->

													<div class="field-wrap">
														<label> Country </label>
														<input type="text" name="country" id="country"/>
													</div>
												</div><!-- /Country -->

												<div class="top-row">
													<div class="field-wrap">
														<input id="pass" name="pass" id="password" type="password" placeholder="Set A Password" required autocomplete="off"/>
														<span></span>
													</div>
													<div class="field-wrap">
														<input id="confpass" type="password" placeholder="Re-enter Password" required autocomplete="off"/>
														<span></span>
													</div>
												</div>

								          	<?php wp_nonce_field('register_user','register_user_nonce', true, true ); ?>
								          
								          	<button type="submit" class="button button-block register">Get Started</button>
								         </form><!-- /form signup_customer -->
							         </div><!-- /signup_customer Id -->

							         <div id="signup_business">
							         	<form id="register" action="/" method="post">
												<div class="top-row">

													<div class="field-wrap">
														<input type="text" name="business_name" id="business_name" class="business_name"  placeholder="Business name" required/>
														<span></span>
													</div>
													<div class="field-wrap">
														<select name="business_type" id="business_type" required>
															<option value="Business Type 1">Business Type 1</option>
															<option value="Business Type 2">Business Type 2</option>
															<option value="Business Type 3">Business Type 3</option>
															<option value="Business Type 4">Business Type 4</option>
															<option value="Business Type 5">Business Type 5</option>
														</select>
													</div>
								          	</div>

												<div class="field-wrap">
													<label> Username </label>
												  	<input type="text" name="username" id="username" />
												</div>

									         <div class="top-row">
									            <div class="field-wrap">
									              	<label>First Name<span class="req">*</span></label>
									              	<input type="text" name="fname" id="fname" required/>
									            </div>
									            <div class="field-wrap">
									              	<label>Last Name<span class="req">*</span></label>
									              	<input type="text" name="lname" id="lname" required/>
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
									              	<input type="date" id="datedropper" class="datedropper_business" name="dd" data-theme="my-style" value="Date of birth"/>
									            </div>
								         	</div>

								          	<div class="top-row">
													<div class="field-wrap">
														<input name="email" id="email" type="email" placeholder="Email*" required/>
														<span></span>
													</div>
													<div class="field-wrap">
														<input id="phone" class="phone_business" name="phone"  placeholder="Phone*" />
														<span id="valid-msg" class="hide">✓ Valid</span>
														<span id="error-msg" class="hide">Invalid number</span>
													</div>
								          	</div>

												<div class="field-wrap">
												  	<input type="text" name="streetaddress" id="streetaddress" class="streetaddress_business">
												</div>

												<div class="field-wrap">
													<label> Apartment/Suite #</label>
												  	<input type="text" name="apartmentsuite" id="apartmentsuite" />
												</div>


												<div class="top-row">
													<div class="field-wrap">
														<label> City </label>
														<input type="text" name="city" id="locality"/>
													</div><!-- /City -->

													<div class="field-wrap">
														<label> State </label>
														<input type="text" name="state" id="administrative_area_level_1"/>
													</div><!-- /State -->
												</div>

												<div class="top-row">
													<div class="field-wrap">
														<label> Zip </label>
														<input type="text" name="zip" id="postal_code"/>
													</div><!-- /Zip -->

													<div class="field-wrap">
														<label> Country </label>
														<input type="text" name="country" id="country"/>
													</div>
												</div><!-- /Country -->

												<div class="top-row">
													<div class="field-wrap">
														<input name="pass" id="password" type="password" placeholder="Set A Password" required autocomplete="off"/>
														<span></span>
													</div>
													<div class="field-wrap">
														<input id="confpass" type="password" placeholder="Re-enter Password" required autocomplete="off"/>
														<span></span>
													</div>
												</div>

								          	<?php wp_nonce_field('register_user','register_user_nonce', true, true ); ?>
								          
								          	<button type="submit" class="button button-block register">Get Started</button>
								         </form><!-- /form signup_Business -->
							         </div><!-- /signup_business -->

						        	</div><!-- /sinup -->

						      </div><!-- tab-content -->
						   </div><!-- /form -->
						</div>

						<div class="col-md-6 hidden-mobile">
							<?php echo do_shortcode('[apsl-login-lite login_text="Login with Social Media"]'); ?>
						</div>
					</div>
				</div>
			</div>

	    </div> <!-- content area -->
	</div>

	<div class="remodal" data-remodal-id="phone_verification" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
	<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
	<style>

	</style>
	<h2 id="modal1Title">Verify Your Phone Number</h2>
	<div class="verification_content">
		<div class="verification_img">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/SmartPhone.png" alt="">
		</div>
		<div id="modal1Desc">
			<form action="">
				<label for="verification_code">Enter Verification Code</label>
				<input type="number" name="verification_code" id="verification_code">
				<input type="submit" name="code_submit" value="Verify" id="code_submit" class="custom_btn">
			</form>
		</div>
	</div>
</div>


<?php get_footer(); ?>