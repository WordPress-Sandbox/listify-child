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
						      	<li class="tab active"><a href="#login">Log In</a></li>	
						        <li class="tab"><a href="#signup">Sign Up</a></li>
						      </ul>
						      
						      <div class="tab-content">

						      	<div id="login">   
						          <h1>Welcome Back!</h1>
						          
						          <form id="login" action="/" method="post">
						          
						            <div class="field-wrap">
						            <label>
						              Email Address<span class="req">*</span>
						            </label>
						            <input type="email"required autocomplete="off"/>
						            <span class="message"></span>
						          </div>
						          
						          <div class="field-wrap">
						            <label>
						              Password<span class="req">*</span>
						            </label>
						            <input type="password"required autocomplete="off"/>
						          </div>
						          
						          <p class="forgot"><a href="#">Forgot Password?</a></p>
						          
						          <button class="button button-block"/>Log In</button>
						          
						          </form>

						        </div>

						        <div id="signup">   
						          <h1>Sign Up for Free</h1>
						          
						          <form id="register" action="/" method="post">

								<div class="field-wrap">
									<label> Username </label>
								  	<input type="text" name="username" id="username" />
								</div>

						          <div class="top-row">
						            <div class="field-wrap">
						              <label>
						                First Name<span class="req">*</span>
						              </label>
						              <input type="text" name="fname" id="fname" required/>
						            </div>
						            <div class="field-wrap">
						              <label>
						                Last Name<span class="req">*</span>
						              </label>
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
						              <input id="datedropper" name="dd" id="dd" data-theme="my-style" value="Date of birth"/>
						            </div>
						          </div>

						          <div class="top-row">
									<div class="field-wrap">
										<input id="email" name="email" id="email" type="email" placeholder="Email*" required/>
										<span></span>
									</div>
									<div class="field-wrap">
						              	<input id="phone" name="phone" id="phone" placeholder="Phone*" />
										<span id="valid-msg" class="hide">✓ Valid</span>
										<span id="error-msg" class="hide">Invalid number</span>
						            </div>
						          </div>

								<div class="field-wrap">
								  	<input id="autocomplete" name="streetaddress" id="streetaddress" type="text"></input>
								</div>

								<div class="field-wrap">
									<label> Apartment/Suite #</label>
								  	<input type="text" name="apartmentsuite" id="apartment_suite" />
								</div>


								<div class="top-row">
									<div class="field-wrap">
										<label> City </label>
									  	<input type="text" name="city" id="locality"/>
									</div>
									<div class="field-wrap">
										<label> State </label>
									  	<input type="text" name="state" id="administrative_area_level_1"/>
									</div>
						        </div>

								<div class="top-row">
									<div class="field-wrap">
										<label> Zip </label>
									  	<input type="text" name="zip" id="postal_code"/>
									</div>
									<div class="field-wrap">
										<label> Country </label>
									  	<input type="text" name="country" id="country"/>
									</div>
						        </div>

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
						          
						          <button type="submit" class="button button-block register"/>Get Started</button>
						          
						          </form>

						        </div>
						        
						      </div><!-- tab-content -->
						      
						</div> <!-- /form -->
						</div>
						<div class="col-md-6 hidden-mobile">
							<?php echo do_shortcode('[apsl-login-lite login_text="Login with Social Media"]'); ?>
						</div>
					</div>
				</div>
			</div>

	    </div> <!-- content area -->
	</div>

<?php get_footer(); ?>