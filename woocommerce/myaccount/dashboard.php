<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* custom code*/
if(!is_user_logged_in()){
    wp_redirect(get_template_page_link('login_register.php'));
    exit();
}

$mysavingwallet = new Mysavingwallet;
$user_id = get_current_user_id();
$user = new WP_User($user_id);
$email_status = get_user_meta($user_id, 'email_status', true);
$email_code = get_user_meta($user_id, 'email_code', true);
$key = '';
if(array_key_exists('key', $_GET)) {
    $key = $_GET['key'];
}

?>

    <div class="woocommerce-message" style="display: none"></div>
    <?php //echo get_user_meta($user_id, 'email_code', true); ?>
    <?php //echo get_user_meta($user_id, 'email_status', true); ?>
    <?php //if($key) echo $key; ?>

<?php 

if( ( $key == $email_code ) && ( $email_status == 'pending' ) )  :  ?>

    <div class="container">
        <div class="email_confirmed">
            <?php update_user_meta($user_id, 'email_status', 'verified'); ?>
            <h2> Your Email Verified Successfully </h2>
            <script>window.setTimeout(function(){location.reload()}, 2000);</script>
        </div>
    </div>

<?php elseif ( $email_status != 'verified' ) :  ?>

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

<?php elseif ($email_status == 'verified') : ?>
	<div class="container user_profile">
        <div class="row">
            <div class="col-md-3">
                <div>
                <div class="qr_profile">
                    <?php if(get_user_role() == 'customer'): ?>
                        <div class="qr_code">
                            <img src="https://chart.googleapis.com/chart?chs=262x262&cht=qr&chl=<?php echo $mysavingwallet->qrurl(); ?>&choe=UTF-8" alt="" >
                        </div>
    <!--                     <style>
                        .qr_profile {
                            position: relative;
                        }
                        .user_profile_img,
                        .qr_code {
                            position: absolute;
                            top: 0;
                        }
                         .qr_code {
                          display: none;
                          z-index: 9;
                        }
                        .user_profile_img:before {
                            content: "";
                            position: absolute;
                            background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/img/qr-corner.png');
                            height: 50px;
                            width: 50px;
                            right:0;
                            z-index: 99;
                            cursor: pointer;
                        }</style> -->
                    <?php endif; ?>
                    <div class="user_profile_img">
                        <?php echo get_avatar($user_id, 262); ?>
                    </div>
                    <br/>
                    <?php if(get_user_role() == 'customer'): ?>
                        <h2> Customer ID: <?php echo $user_id; ?></h2>
                    <?php endif; ?>
                </div>
                </div>
                <div class="user_profile_desc">
                    <h2 class="user_name"><?php echo $user->first_name; ?> <?php echo $user->last_name; ?></h2>
                    <p class="user_bio"><?php echo esc_attr( $user->description ); ?></p>
                    <ul class="social_media model-2">
                        <?php if ($user->facebook): ?>
                            <li><a href="<?php echo $user->facebook; ?>" class="fa fa-facebook"></a></li>
                        <?php endif ?>
                        
                        <?php if ($user->twitter): ?>
                            <li><a href="<?php echo $user->twitter; ?>" class="fa fa-twitter"></a></li>
                        <?php endif ?>

                        <?php if ($user->linkedin): ?>
                            <li><a href="<?php echo $user->linkedin; ?>" class="fa fa-linkedin"></a></li>
                        <?php endif ?>

                        <?php if ($user->instagram): ?>
                            <li><a href="<?php echo $user->instagram; ?>" class="fa fa-instagram"></a></li>
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
                            <h2> Upload profile photo</h2>
                            <?php echo do_shortcode('[avatar_upload]'); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <span class="edit_profile button fa fa-pencil pull-right"></span>
                                </div>
                            </div>
							<hr>
							<form id="basic_info" action="" class="form-edit-account" method="post">

	                            <dl class="dl-horizontal">
	                                <dt><strong><?php esc_html_e( 'First name', 'listify_child' ); ?></strong></dt>
	                                <dd>
							            <input type="text" class="input-text" name="first_name" id="first_name" value="<?php echo esc_attr( $user->first_name ); ?>" disabled>
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Last name', 'listify_child' ); ?></strong></dt>
	                                <dd>
							            <input type="text" class="input-text" name="first_name" id="first_name" value="<?php echo esc_attr( $user->last_name ); ?>" disabled>
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Your ID', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="text" class="input-text" name="user_login" id="user_login" value="<?php echo esc_attr( $user->user_login ); ?>" disabled>
	                                </dd>
	                                <hr>

	                                <dt><strong><?php esc_html_e( 'Company name', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="text" class="input-text " name="billing_company" id="billing_company" value="<?php echo esc_attr( $user->billing_company ); ?>" disabled> 
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Primary Email Address', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="email" class="input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" disabled>
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Phone Number', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="tel" class="input-text " name="billing_phone" id="billing_phone" autocomplete="tel" value="<?php echo esc_attr( $user->billing_phone ); ?>" disabled>
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Address', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="text" class="input-text " name="billing_address_1" id="billing_address_1" autocomplete="address-line1" value="<?php echo esc_attr( $user->billing_address_1 ); ?>" disabled>
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'City', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="text" class="input-text " name="billing_city" id="billing_city" value="<?php echo esc_attr( $user->billing_city ); ?>" disabled>
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Country', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="text" class="input-text " name="country" id="country" value="<?php echo esc_attr( $user->country ); ?>" disabled>
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'About', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                	<textarea name="description" id="description" class="input-text" rows="5" cols="30" disabled><?php echo esc_attr( $user->description ); ?></textarea>
	                                </dd>
	                                <hr>
	                            </dl>

	                            <button type="submit" name="basic_info" class="button"><?php esc_html_e( 'Save Changes', 'woocommerce' ); ?></button>

                            </form>
                        </div><!-- /profile -->

                        <div id="passwordTab" class="user_profile_edit fade">
                            <h2>Manage your Security Settings</h2>
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
                                                <input type="text" name="user_login" id="user_login" placeholder="Username" value="<?php echo esc_attr( $user->user_login ); ?>" disabled>
                                            </label>
                                        </section>
                                    </dd>
									<hr>
                                    <dt><?php esc_html_e( 'Email Address', 'listify_child' ); ?></dt>
                                    <dd>
                                        <section>
                                            <label for="account_email" class="input">
                                                <i class="icon_append fa fa-envelope"></i>
                                                <input type="email" name="account_email" id="account_email" placeholder="Email address" value="<?php echo esc_attr( $user->user_email ); ?>" disabled>
                                            </label>
                                        </section>
                                    </dd>
                                    <hr>
                                    <dt><?php esc_html_e( 'Enter current password', 'listify_child' ); ?></dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon_append fa fa-lock"></i>
                                                <input type="password" class="input-text" name="password_1" placeholder="Enter your current password" id="password_1" disabled>
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
                                                <input type="password" class="input-text" name="password_2" placeholder="Confirm password" id="password_2" disabled>
                                                <b class="tooltip tooltip-bottom-right">Don't forget your password</b>
                                            </label>
                                        </section>
                                    </dd>    
                                </dl>
                                <hr>
                                <br>
                                <?php wp_nonce_field( 'save_account_details' ); ?>
	                            	<button type="submit" name="Cancel" class="button button_u">Cancel</button>
	                           		<button type="submit" name="save_account_details" class="button"><?php esc_html_e( 'Save Changes', 'woocommerce' ); ?></button>
	                            <input type="hidden" name="action" value="save_account_details">

	                            <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
                            </form>    
                        </div><!-- /passwordTab -->

                        <div id="payment" class="user_profile_edit fade">
                            <h2>Manage your Payment</h2>
                            <?php if(get_user_role() == 'customer'): ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <span class="edit_profile button fa fa-pencil pull-right"></span>
                                </div>
                            </div>
                            <br/>
                            <?php 
                                $bank = get_user_meta($user_id, 'bank', true);
                                $bank = json_decode($bank);
                                // var_dump($bank); 
                            ?>
                            <form id="bank_account" action="#" method="post">
                                <dl class="dl-horizontal">
                                    <dt><?php esc_html_e( 'Bank name', 'listify_child' ); ?></dt>
                                    <dd>
                                        <input type="text" class="input-text" name="bank_name" value="<?php echo esc_attr( $bank->bank_name ); ?>">
                                    </dd>                                 

                                    <dt><?php esc_html_e( 'Bank Routing Number (9 Digits only)', 'listify_child' ); ?></dt>
                                    <dd>
                                        <input type="text" class="input-text" name="bank_routing" value="<?php echo esc_attr( $bank->bank_routing ); ?>">
                                    </dd>

                                    <dt><?php esc_html_e( 'Bank Account Number (up to 16 digits)', 'listify_child' ); ?></dt>
                                    <dd>
                                        <input type="text" class="input-text" name="account_number" value="<?php echo esc_attr( $bank->account_number ); ?>">
                                    </dd>
                                    <dt><?php esc_html_e( 'Account Type', 'listify_child' ); ?></dt>
                                    <dd>
                                        <select name="account_type">
                                            <option>Checking</option>
                                            <option>Savings</option>
                                        </select>
                                    </dd>

                                    <dt><?php esc_html_e( 'Please provide proof of Account Info – Voided Check or Bank Letter', 'listify_child' ); ?></dt>

                                        <!-- http://www.kvcodes.com/2013/12/create-front-end-multiple-file-upload-wordpress/ 

                                        https://hugh.blog/2014/03/20/wordpress-upload-user-submitted-files-frontend/

                                        -->
                                    <dd>
                                        <input type="file" name="kv_multiple_attachments[]"  multiple="multiple" >
                                    </dd>     

                                </dl>

                                <button type="submit" class="button">Save bank info </button>
                            </form>  
                            <?php endif; ?>  
                            
                            <?php if(get_user_role() == 'customer')
                                {
                                    echo do_shortcode('[wpdeposit_withdrawals]');
                                } else if(get_user_role() != 'customer') 
                                {
                                    echo do_shortcode('[wpdeposit_payment_interface]');
                                }
                            ?>

                            <p> Deposit balance: </p>
                            <?php echo do_shortcode('[wpdeposit_deposit_balance]'); ?>

                            <p> Deposit history: </p>
                            <?php echo do_shortcode('[wpdeposit_deposit_history]'); ?>

                            <p> Transaction history: </p>
                            <?php echo do_shortcode('[wpdeposit_deposit_history]'); ?>

                        </div><!-- /payment -->

                        <div id="social" class="user_profile_edit fade">
                        	<h2>Manage your Social Media.</h2>
							<div class="row">
								<div class="col-md-12">
									<span class="edit_profile button fa fa-pencil pull-right"></span>
								</div>
							</div>
							<hr>
                            <form id="save_social_details" action="" class="social_change password_change" method="post">
							<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
							
							<dl class="dl-horizontal">
                                <dt><?php esc_html_e( 'Facebook', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="facebook" class="input">
                                            <i class="icon_append fa fa-facebook"></i>
                                            <input type="text" class="input-text" name="facebook" id="facebook" value="<?php echo esc_attr( $user->facebook ); ?>" disabled>
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
                                            <input type="text" class="input-text" name="twitter" id="twitter" value="<?php echo esc_attr( $user->twitter ); ?>" disabled>
                                            <b class="tooltip tooltip-bottom-right">Needed to enter the twitter</b>
                                        </label>
                                    </section>
                                </dd>
                                <hr>
                                <dt><?php esc_html_e( 'Linkedin', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="linkedin" class="input">
                                            <i class="icon_append fa fa-linkedin"></i>
                                            <input type="text" class="input-text" name="linkedin" id="linkedin" value="<?php echo esc_attr( $user->linkedin ); ?>" disabled>
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
                                            <input type="text" class="input-text" name="instagram" id="instagram" value="<?php echo esc_attr( $user->instagram ); ?>" disabled>
                                            <b class="tooltip tooltip-bottom-right">Needed to enter the instagram</b>
                                        </label>
                                    </section>
                                </dd>
                                <hr>  
                            </dl>
                           	<button type="submit" name="save_social_details" class="save_social_details button"><?php esc_html_e( 'Save Changes', 'woocommerce' ); ?></button>
                            </form>
                        </div><!-- /social -->

                        <div id="settings" class="user_profile_edit fade">
                            <h2>Manage your Notifications.</h2>
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
