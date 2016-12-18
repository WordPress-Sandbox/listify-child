<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if(!is_user_logged_in()){
    wp_redirect(get_template_page_link('login_register.php'));
    exit();
}


$user_id = get_current_user_id();
$user = new WP_User($user_id);
$email_status = get_user_meta($user_id, 'email_status', true);
$email_code = get_user_meta($user_id, 'email_code', true);
$key = $_GET['key'];

?>

    <?php echo get_user_meta($user_id, 'email_code', true); ?>
    <?php echo get_user_meta($user_id, 'email_status', true); ?>
    <?php echo $key; ?>

<?php 

if( ($user->roles[0] != 'administrator' ) &&  ( $email_status != 'verified' )  && ( $email_status != 'pending' ) ) :  ?>
    <div class="container email_verification">
        <img class="email_icon" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/email_icon.png" alt="">
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <h2>Please verify your email</h2>
                <p> Verifying your email is important before proceeding to profile </p>
            </div>
        </div>
        <div class="row">
            <div class="email_verify">
                <input type="text" name="email" value="<?php echo $user->user_email; ?>" disabled>
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <?php wp_nonce_field('email_verify','email_verify_nonce', true, true ); ?>
                <input type="submit" name="email_submit" value="Send verification code">
            </div>
        </div>
    </div>

<?php elseif ( ( $key == $email_code ) && ( $email_status == 'pending' ) ) :  ?>

    <div class="container">
        <div class="email_confirmed">
            <?php update_user_meta($user_id, 'email_status', 'verified'); ?>
            <h2> Your Email Verified Successfully </h2>
            <script>window.setTimeout(location.reload, 2000);</script>
        </div>
    </div>

<?php else : ?>


	<div class="container user_profile">
        <div class="row">
            <div class="col-md-3">
                <div class="user_profile_img">
                    <?php echo get_avatar($user_id, 170); ?>
                </div>
                <div class="user_profile_desc">
                    <h2 class="user_name"><?php echo $user->display_name; ?></h2>
                    <p class="user_bio"><?php echo $user->description; ?></p>
				
                    <ul class="social_media model-2">
                    	<?php if ($user->facebook): ?>
                    		<li><a href="<?php echo $user->facebook; ?>" class="fa fa-facebook"></a></li>
                    	<?php endif ?>
                    	
                    	<?php if ($user->twitter): ?>
                    		<li><a href="<?php echo $user->twitter; ?>" class="fa fa-twitter"></a></li>
                    	<?php endif ?>

                    	<?php if ($user->googleplus): ?>
                    		<li><a href="<?php echo $user->googleplus; ?>" class="fa fa-google-plus"></a></li>
                    	<?php endif ?>

                    	<?php if ($user->linkedin): ?>
                    		<li><a href="<?php echo $user->linkedin; ?>" class="fa fa-linkedin"></a></li>
                    	<?php endif ?>

                    	<?php if ($user->instagram): ?>
                    		<li><a href="<?php echo $user->instagram; ?>" class="fa fa-instagram"></a></li>
                    	<?php endif ?>

                    	<?php if ($user->pinterest): ?>
                    		<li><a href="<?php echo $user->pinterest; ?>" class="fa fa-pinterest"></a></li>
                    	<?php endif ?>

                    	<?php if ($user->github): ?>
                    		<li><a href="<?php echo $user->github; ?>" class="fa fa-github"></a></li>
                    	<?php endif ?>

                    	<?php if ($user->jabber): ?>
                    		<li><a href="<?php echo $user->jabber; ?>" class="fa fa-google"></a></li>
                    	<?php endif ?>

                    	<?php if ($user->yim): ?>
                    		<li><a href="<?php echo $user->yim; ?>" class="fa fa-yahoo"></a></li>
                    	<?php endif ?>

                    	<?php if ($user->aim): ?>
                    		<li><a href="<?php echo $user->aim; ?>" class="fa fa-buysellads"></a></li>
                    	<?php endif ?>
                    </ul><!-- /social_media -->
                </div>
            </div>

            <div class="col-md-9">
                <div class="user_profile_body">
                    <ul class="tab-group">
                        <li><a href="#profile">Edit Profile</a></li>
                        <li><a href="#passwordTab">Change Password</a></li>
                        <li><a href="#payment">Payment Options</a></li>
                        <li><a href="#social">Social Media</a></li>
                        <li><a href="#settings">Notification Settings</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="profile" class="user_profile_edit fade">
                            <h2>Manage your Name, ID and Email Addresses.</h2>
                            <p>Below are the name and email addresses on file for your account.</p>

							<div class="row">
								<div class="col-md-12">
									<span class="edit_profile button fa fa-pencil pull-right"></span>
								</div>
							</div>
							<hr>
							
                            <?php echo do_shortcode('[avatar_upload]'); ?>
							<hr>
							<form action="" class="form-edit-account" method="post">
								<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	                            <dl class="dl-horizontal">
	                                <dt><strong><?php esc_html_e( 'First name', 'listify_child' ); ?></strong></dt>
	                                <dd>
							            <input type="text" class="input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>">
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Last name', 'listify_child' ); ?></strong></dt>
	                                <dd>
							            <input type="text" class="input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>">
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Your ID', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="text" class="input-text" name="user_login" id="user_login" value="<?php echo esc_attr( $user->user_login ); ?>" disabled >
	                                </dd>
	                                <hr>

	                                <dt><strong><?php esc_html_e( 'Company name', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="text" class="input-text " name="billing_company" id="billing_company" value="<?php echo esc_attr( $user->billing_company ); ?>"> 
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Primary Email Address', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="email" class="input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>">
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Phone Number', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="tel" class="input-text " name="billing_phone" id="billing_phone" autocomplete="tel" value="<?php echo esc_attr( $user->billing_phone ); ?>">
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Address', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="text" class="input-text " name="billing_address_1" id="billing_address_1" autocomplete="address-line1" value="<?php echo esc_attr( $user->billing_address_1 ); ?>">
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'City', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="text" class="input-text " name="City" id="City" value="<?php echo esc_attr( $user->City ); ?>">
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Country', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="text" class="input-text " name="country" id="country" value="<?php echo esc_attr( $user->country ); ?>">
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'About', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                	<textarea name="description" id="description" class="input-text" rows="5" cols="30"><?php echo esc_attr( $user->description ); ?></textarea>
	                                </dd>
	                                <hr>
	                            </dl>

	                            <?php wp_nonce_field( 'save_account_details' ); ?>
	                            <button type="submit" name="Cancel" class="button button_u">Cancel</button>
	                            <button type="submit" name="save_account_details" class="button"><?php esc_html_e( 'Save Changes', 'woocommerce' ); ?></button>
	                            <input type="hidden" name="action" value="save_account_details">

	                            <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
                            </form>
                        </div><!-- /profile -->

                        <div id="passwordTab" class="user_profile_edit fade">
                            <h2>Manage your Security Settings</h2>
                            <p>Change your password.</p>
                            <p></p>
                            
                            <div class="row">
								<div class="col-md-12">
									<span class="edit_profile button fa fa-pencil pull-right"></span>
								</div>
							</div>
							<hr>
                            <form action="" class="password_change" method="post">
                            	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
                                <dl class="dl-horizontal">
                                    <dt><?php esc_html_e( 'Username', 'listify_child' ); ?></dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon_append fa fa-user"></i>
                                                <input type="text" class="input-text" name="user_login" id="user_login" placeholder="Username" value="<?php echo esc_attr( $user->user_login ); ?>" disabled>
                                                <b class="tooltip tooltip-bottom-right">Needed to enter the website</b>
                                            </label>
                                        </section>
                                    </dd>
									<hr>
                                    <dt><?php esc_html_e( 'Email Address', 'listify_child' ); ?></dt>
                                    <dd>
                                        <section>
                                            <label for="account_email" class="input">
                                                <i class="icon_append fa fa-envelope"></i>
                                                <input type="email" class="input-text" name="account_email" id="account_email" placeholder="Email address" value="<?php echo esc_attr( $user->user_email ); ?>">
                                                <b class="tooltip tooltip-bottom-right">Needed to verify your account</b>
                                            </label>
                                        </section>
                                    </dd>
                                    <hr>
                                    <dt><?php esc_html_e( 'Enter current password', 'listify_child' ); ?></dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon_append fa fa-lock"></i>
                                                <input type="password" class="input-text" name="password_1" placeholder="Enter your current password" id="password_1">
                                                <b class="tooltip tooltip-bottom-right">Don't forget your password</b>
                                            </label>
                                        </section>
                                    </dd>
                                    <hr>
                                    <dt><?php esc_html_e( 'Confirm Password', 'listify_child' ); ?></dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon_append fa fa-lock"></i>
                                                <input type="password" class="input-text" name="password_2" placeholder="Confirm password" id="password_2" />
                                                <b class="tooltip tooltip-bottom-right">Don't forget your password</b>
                                            </label>
                                        </section>
                                    </dd>    
                                </dl>
                                <hr>
                                <br>
                                <section>
                                    <label class="checkbox"><input type="checkbox" id="terms" name="terms"><i></i><p>I agree with the Terms and Conditions</p></label>
                                </section>
                                <br>
                                <?php wp_nonce_field( 'save_account_details' ); ?>
	                            	<button type="submit" name="Cancel" class="button button_u">Cancel</button>
	                           		<button type="submit" name="save_account_details" class="button"><?php esc_html_e( 'Save Changes', 'woocommerce' ); ?></button>
	                            <input type="hidden" name="action" value="save_account_details">

	                            <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
                            </form>    
                        </div><!-- /passwordTab -->

                        <div id="payment" class="user_profile_edit fade">
                            <h2>Manage your Payment Settings</h2>
                            <p>Below are the payment options for your account.</p>
                            <br>
                            <form action="#" method="post" class="payment_method">
                                <!--Checkout-Form-->
                                <section>
                                    <div class="inline-group">
                                        <label class="radio"><input type="radio" checked="" name="radio-inline"><i class="rounded-x"></i>Visa</label>
                                        <label class="radio"><input type="radio" name="radio-inline"><i class="rounded-x"></i>MasterCard</label>
                                        <label class="radio"><input type="radio" name="radio-inline"><i class="rounded-x"></i>PayPal</label>
                                    </div>
                                </section>                  

                                <section>
                                    <label class="input">
                                        <input type="text" name="name" placeholder="Name on card">
                                    </label>
                                </section>
                                
                                <div class="row">
                                    <section class="col-md-10">
                                        <label class="input">
                                            <input type="text" name="card" id="card" placeholder="Card number">
                                        </label>
                                    </section>
                                    <section class="col-md-2">
                                        <label class="input">
                                            <input type="text" name="cvv" id="cvv" placeholder="CVV2">
                                        </label>
                                    </section>
                                </div>                                        
                                <br>
                                <div class="row">
                                    <label class="label col-md-4">Expiration date</label>
                                    <section class="col-md-5">
                                        <label class="select">
                                            <select name="month">
                                                <option disabled="" selected="" value="0">Month</option>
                                                <option value="1">January</option>
                                                <option value="1">February</option>
                                                <option value="3">March</option>
                                                <option value="4">April</option>
                                                <option value="5">May</option>
                                                <option value="6">June</option>
                                                <option value="7">July</option>
                                                <option value="8">August</option>
                                                <option value="9">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                            <i></i>
                                        </label>
                                    </section>
                                    <section class="col-md-3">
                                        <label class="input">
                                            <input type="text" placeholder="Year" id="year" name="year">
                                        </label>
                                    </section>
                                </div>
                                <br>
                                <button type="button" class="button button_u">Cancel</button>
                                <button type="submit" class="button">Save Changes</button>
                                <!--End Checkout-Form-->
                            </form>    

                        </div><!-- /payment -->

                        <div id="social" class="user_profile_edit fade">
                        	<h2>Manage your Social Media.</h2>
                            <p>Below are the notifications you may manage.</p>

							<div class="row">
								<div class="col-md-12">
									<span class="edit_profile button fa fa-pencil pull-right"></span>
								</div>
							</div>
							<hr>
                            <form action="" class="social_change password_change" method="post">
							<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
							
							<dl class="dl-horizontal">
                                <dt><?php esc_html_e( 'Facebook', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="facebook" class="input">
                                            <i class="icon_append fa fa-facebook"></i>
                                            <input type="text" class="input-text" name="facebook" id="facebook" value="<?php echo esc_attr( $user->facebook ); ?>">
                                            <b class="tooltip tooltip-bottom-right">Needed to enter the facebook</b>
                                        </label>
                                    </section>
                                </dd>
								<hr>
                                <dt><?php esc_html_e( 'Twitter', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="twitter" class="input">
                                            <i class="icon_append fa fa-twitter"></i>
                                            <input type="text" class="input-text" name="twitter" id="twitter" value="<?php echo esc_attr( $user->twitter ); ?>">
                                            <b class="tooltip tooltip-bottom-right">Needed to enter the twitter</b>
                                        </label>
                                    </section>
                                </dd>
                                <hr>
                                <dt><?php esc_html_e( 'Google +', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="googleplus" class="input">
                                            <i class="icon_append fa fa-google-plus"></i>
                                            <input type="text" class="input-text" name="googleplus" id="googleplus" value="<?php echo esc_attr( $user->googleplus ); ?>">
                                            <b class="tooltip tooltip-bottom-right">Needed to enter the google plus</b>
                                        </label>
                                    </section>
                                </dd>
                                <hr>
                                <dt><?php esc_html_e( 'Linkedin', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="linkedin" class="input">
                                            <i class="icon_append fa fa-linkedin"></i>
                                            <input type="text" class="input-text" name="linkedin" id="linkedin" value="<?php echo esc_attr( $user->linkedin ); ?>">
                                            <b class="tooltip tooltip-bottom-right">Needed to enter the linkedin</b>
                                        </label>
                                    </section>
                                </dd>
                                <hr>
                                <dt><?php esc_html_e( 'Instagram', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="instagram" class="input">
                                            <i class="icon_append fa fa-instagram"></i>
                                            <input type="text" class="input-text" name="instagram" id="instagram" value="<?php echo esc_attr( $user->instagram ); ?>">
                                            <b class="tooltip tooltip-bottom-right">Needed to enter the instagram</b>
                                        </label>
                                    </section>
                                </dd>
                                <hr>
                                <dt><?php esc_html_e( 'Pinterest', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="pinterest" class="input">
                                            <i class="icon_append fa fa-pinterest"></i>
                                            <input type="text" class="input-text" name="pinterest" id="pinterest" value="<?php echo esc_attr( $user->pinterest ); ?>">
                                            <b class="tooltip tooltip-bottom-right">Needed to enter the pinterest</b>
                                        </label>
                                    </section>
                                </dd>
                                <hr>
                                <dt><?php esc_html_e( 'Github', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="github" class="input">
                                            <i class="icon_append fa fa-github"></i>
                                            <input type="text" class="input-text" name="github" id="github" value="<?php echo esc_attr( $user->github ); ?>">
                                            <b class="tooltip tooltip-bottom-right">Needed to enter the github</b>
                                        </label>
                                    </section>
                                </dd> 
                                <hr>
                                <dt><?php esc_html_e( 'Google Talk', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="jabber" class="input">
                                            <i class="icon_append fa fa-google"></i>
                                            <input type="text" class="input-text" name="jabber" id="jabber" value="<?php echo esc_attr( $user->jabber ); ?>">
                                            <b class="tooltip tooltip-bottom-right">Needed to enter the google talk</b>
                                        </label>
                                    </section>
                                </dd> 
                                <hr>
                                <dt><?php esc_html_e( 'Yahoo IM', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="yim" class="input">
                                            <i class="icon_append fa fa-yahoo"></i>
                                            <input type="text" class="input-text" name="yim" id="yim" value="<?php echo esc_attr( $user->yim ); ?>">
                                            <b class="tooltip tooltip-bottom-right">Needed to enter the yahoo</b>
                                        </label>
                                    </section>
                                </dd> 
                                <hr>
                                <dt><?php esc_html_e( 'Aim', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="aim" class="input">
                                            <i class="icon_append fa fa-buysellads"></i>
                                            <input type="text" class="input-text" name="aim" id="aim" value="<?php echo esc_attr( $user->aim ); ?>">
                                            <b class="tooltip tooltip-bottom-right">Needed to enter the aim</b>
                                        </label>
                                    </section>
                                </dd> 
                                <hr>  
                            </dl>

                            <?php wp_nonce_field( 'save_account_details' ); ?>
                            	<button type="submit" name="Cancel" class="button button_u">Cancel</button>
                           		<button type="submit" name="save_account_details" class="button"><?php esc_html_e( 'Save Changes', 'woocommerce' ); ?></button>
                            	<input type="hidden" name="action" value="save_account_details">

                            <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
                            </form>
                        </div><!-- /social -->

                        <div id="settings" class="user_profile_edit fade">
                            <h2>Manage your Notifications.</h2>
                            <p>Below are the notifications you may manage.</p>
                            <br>
                            <form action="#" method="post" class="user_settings">
                                <label class="toggle"><input type="checkbox" checked="" name="checkbox-toggle-1"><i></i>Email notification</label>
                                <hr>
                                <label class="toggle"><input type="checkbox" checked="" name="checkbox-toggle-1"><i></i>Send me email notification when a user comments on my blog</label>
                                <hr>
                                <label class="toggle"><input type="checkbox" checked="" name="checkbox-toggle-1"><i></i>Send me email notification for the latest update</label>
                                <hr>
                                <label class="toggle"><input type="checkbox" checked="" name="checkbox-toggle-1"><i></i>Send me email notification when a user sends me message</label>
                                <hr>
                                <label class="toggle"><input type="checkbox" checked="" name="checkbox-toggle-1"><i></i>Receive our monthly newsletter</label>
                                <hr>    
                                <button type="button" class="button button_u">Reset</button>
                                <button type="submit" class="button">Save Changes</button>
                            </form>
                        </div><!-- /settings -->
                    </div><!-- /tab-content -->
                </div><!-- /user_profile_body -->
            </div><!-- /col-md-9 -->
        </div>   
    </div>
<?php endif; ?>