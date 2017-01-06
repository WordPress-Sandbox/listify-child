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
$email_code = get_user_meta($user_id, 'email_code', true);
$gender = get_user_meta($user_id, 'gender', true);
$billing_phone = get_user_meta($user_id, 'billing_phone', true);
$billing_company = get_user_meta($user_id, 'billing_company', true);
$bs_type = get_user_meta($user_id, 'bs_type', true);
$dd = get_user_meta($user_id, 'dd', true);
$apartmentsuite = get_user_meta($user_id, 'apartmentsuite', true);
$billing_country = get_user_meta($user_id, 'billing_country', true);
$facebook = get_user_meta($user_id, 'facebook', true);
$description = get_user_meta($user_id, 'description', true);
$banks = get_user_meta($user_id, 'banks', true);

$key = '';
if(array_key_exists('key', $_GET)) {
    $key = $_GET['key'];
}

?>

    <div class="woocommerce-message" style="display: none"></div>
    <?php //echo get_user_meta($user_id, 'email_code', true); ?>
    <?php //echo get_user_meta($user_id, 'email_status', true); ?>

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

    <div class="verification_badge">
        <ul>
            <?php $mysavingwallet->verificationBadge(); ?>
        </ul>
    </div>

	<div class="container user_profile">
        <div class="row">
            <div class="col-md-3">
                <div>
                <div class="qr_profile">
                    <?php if(get_user_role() == 'customer'): ?>
                        <div class="qr_code">
                            <img src="https://chart.googleapis.com/chart?chs=275x275&cht=qr&chl=<?php echo $mysavingwallet->qrurl(); ?>&choe=UTF-8" alt="" >
                        </div>
                        <style>
                            .qr_code {
                                position: absolute;
                                z-index: -1;
                            }
                             .user_profile_img:before {
                                content: "";
                                position: absolute;
                                background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/img/qr-corner.png');
                                background-repeat: no-repeat;
                                height: 50px;
                                width: 50px;
                                right:12px;
                                z-index: 99;
                                cursor: pointer;
                            }
                        </style>
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
                            <li><a href="<?php echo $facebook; ?>" class="fa fa-facebook"></a></li>
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
                        <li><a href="#payment">Payment</a></li>
                        <li><a href="#social">Social Media</a></li>
                        <li><a href="#settings">Notification</a></li>
                        <?php if(get_user_role() == 'business') : ?>
                        <li><a href="#stats">Stats</a></li>
                        <?php endif; ?>
                    </ul>

                    <div class="tab-content">
                        <div id="profile" class="user_profile_edit fade">
                            <h2> Upload profile photo</h2>
                            <?php echo do_shortcode('[avatar_upload]'); ?>
							<hr>
							<form id="basic_info" action="" class="form-edit-account" method="post">

	                            <dl class="dl-horizontal">
                                    <?php if(get_user_role() == 'business') : ?>
                                    <dt><strong><?php esc_html_e( 'Business name', 'listify_child' ); ?></strong></dt>
                                    <dd>
                                        <input type="text" name="billing_company" value="<?php echo esc_attr( $billing_company ); ?>"> 
                                    </dd>
                                    <hr>                                    

                                    <dt><strong><?php esc_html_e( 'Business type', 'listify_child' ); ?></strong></dt>
                                    <dd>
                                        <select name="bs_type">
                                            <option value="accountant" <?php if($bs_type == "accountant") echo "selected"; ?>>Accountant</option>
                                            <option value="arts&Entertainment" <?php if($bs_type == "arts&Entertainment") echo "selected"; ?>>Arts & Entertainment</option>
                                            <option value="automotive" <?php if($bs_type == "automotive") echo "selected"; ?>>Automotive</option>
                                            <option value="businessservices" <?php if($bs_type == "businessservices") echo "selected"; ?>>Business Services</option>
                                            <option value="clearningservices" <?php if($bs_type == "clearningservices") echo "selected"; ?>>Cleaning Services</option>
                                            <option value="doctores" <?php if($bs_type == "doctores") echo "selected"; ?>>Doctors & Health Professionals</option>
                                            <option value="grocery" <?php if($bs_type == "grocery") echo "selected"; ?>>Grocery Store</option>
                                            <option value="hearsalon" <?php if($bs_type == "hearsalon") echo "selected"; ?>>Hair Salon & Spa</option>
                                            <option value="homeimprovement" <?php if($bs_type == "homeimprovement") echo "selected"; ?>>Home Improvement</option>
                                            <option value="hotels" <?php if($bs_type == "hotels") echo "selected"; ?>>Hotels & Lodging</option>
                                            <option value="legalservices" <?php if($bs_type == "legalservices") echo "selected"; ?>>Legal Services</option>
                                            <option value="outdoors" <?php if($bs_type == "outdoors") echo "selected"; ?>>Outdoors</option>
                                            <option value="restaurants" <?php if($bs_type == "restaurants") echo "selected"; ?>>Restaurants & Bars</option>
                                            <option value="transportantion" <?php if($bs_type == "transportantion") echo "selected"; ?>>Transportation</option>
                                            <option value="other" <?php if($bs_type == "other") echo "selected"; ?>>other</option>
                                        </select>
                                    </dd>
                                    <hr>
                                    <?php endif; ?>
	                                <dt><strong><?php esc_html_e( 'First name', 'listify_child' ); ?></strong></dt>
	                                <dd>
							            <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $user->first_name ); ?>">
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Last name', 'listify_child' ); ?></strong></dt>
	                                <dd>
							            <input type="text" name="last_name" value="<?php echo esc_attr( $user->last_name ); ?>">
	                                </dd>
	                                <hr>
                                    <dt><strong><?php esc_html_e( 'Gender', 'listify_child' ); ?></strong></dt>
                                    <dd>
                                       <select name="gender" id="gender">
                                            <option value="male" <?php if($gender == "male") echo "selected"; ?>>Male</option>
                                            <option value="female" <?php if($gender == "female") echo "selected"; ?>>Female</option>
                                        </select>
                                    </dd>
                                    <hr>
	                                <dt><strong><?php esc_html_e( 'Date of birth', 'listify_child' ); ?></strong></dt>
	                                <dd>
                                        <input type="date" id="bs_dd" name="dd" value="<?php echo $dd; ?>">
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Phone Number', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="tel" name="billing_phone" id="billing_phone" autocomplete="tel" value="<?php echo esc_attr( $billing_phone ); ?>">
	                                </dd>
	                                <hr>
                                    <dt><strong><?php esc_html_e( 'Address', 'listify_child' ); ?></strong></dt>
                                    <dd>
                                        <input type="text" name="billing_address_1" value="<?php echo esc_attr( $user->billing_address_1 ); ?>">
                                    </dd>
                                    <hr>	                                

                                    <dt><strong><?php esc_html_e( 'Apartment/Suite #', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                    <input type="text" name="bs_apartmentsuite" value="<?php echo esc_attr($apartmentsuite); ?>">
	                                </dd>
	                                <hr>
                                    <dt><strong><?php esc_html_e( 'City', 'listify_child' ); ?></strong></dt>
                                    <dd>
                                        <input type="text" name="billing_city" value="<?php echo esc_attr( $user->billing_city ); ?>" >
                                    </dd>
                                    <hr>	                                
                                    <dt><strong><?php esc_html_e( 'State', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                   <input type="text" name="billing_state" value="<?php echo esc_attr( $user->billing_state ); ?>">
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'Country', 'listify_child' ); ?></strong></dt>
	                                <dd>
                                    <?php  
                                        $countries_obj   = new WC_Countries();
                                        $countries   = $countries_obj->get_allowed_countries();

                                        woocommerce_form_field('billing_country', array(
                                        'type'       => 'select',
                                        'input_class'=> array('input-text'),
                                        'options'    => $countries,
                                        ), $billing_country
                                        ); 
                                    ?>
	                                </dd>
	                                <hr>
	                                <dt><strong><?php esc_html_e( 'About', 'listify_child' ); ?></strong></dt>
	                                <dd>
	                                	<textarea name="description" rows="5" cols="30"><?php echo esc_attr( $description ); ?></textarea>
	                                </dd>
	                                <hr>
	                            </dl>

	                            <button type="submit" name="basic_info" class="button"><?php esc_html_e( 'Save Changes', 'woocommerce' ); ?></button>

                            </form>
                        </div><!-- /profile -->

                        <div id="passwordTab" class="user_profile_edit fade">
                            <h2>Manage your Security Settings</h2>
							<hr>
                            <form id="change_password" action="" class="password_change" method="post">
                            	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
                                <dl class="dl-horizontal">
                                    <dt><?php esc_html_e( 'Username', 'listify_child' ); ?></dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon_append fa fa-user"></i>
                                                <input type="text" name="user_login" placeholder="Username" value="<?php echo esc_attr( $user->user_login ); ?>" disabled>
                                            </label>
                                        </section>
                                    </dd>
									<hr>
                                    <dt><?php esc_html_e( 'Email Address', 'listify_child' ); ?></dt>
                                    <dd>
                                        <section>
                                            <label for="account_email" class="input">
                                                <i class="icon_append fa fa-envelope"></i>
                                                <input type="email" name="account_email" placeholder="Email address" value="<?php echo esc_attr( $user->user_email ); ?>" disabled>
                                            </label>
                                        </section>
                                    </dd>
                                    <hr>
                                    <dt><?php esc_html_e( 'Enter current password', 'listify_child' ); ?></dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon_append fa fa-lock"></i>
                                                <input type="password" name="password_1" placeholder="Enter your current password">
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
                                                <input type="password" name="password_2" placeholder="Confirm password">
                                                <b class="tooltip tooltip-bottom-right">Don't forget your password</b>
                                            </label>
                                        </section>
                                    </dd>    
                                </dl>
                                <hr>
                                <br>
	                           		<button type="submit" name="change_password" class="button"><?php esc_html_e( 'Save Changes', 'woocommerce' ); ?></button>
                            </form>    
                        </div><!-- /passwordTab -->

                        <div id="payment" class="user_profile_edit fade">
                            <h2>Manage your Payment</h2>
                            <p class="show_message"></p>
                            <?php if(get_user_role() != 'customer') : ?>
                                <p> Your current balance is: <?php echo $mysavingwallet->wallet_balance(); ?>
                                <br/>
                                <a class="button add_balance"> Add balance</a>
                                <h4> Transaction history </h4>
                                <?php 
                                echo $mysavingwallet->transactions();
                                require_once locate_template( 'inc/templates/add-balance.php' ); 
                                ?>

                            <?php endif; ?>
                            <?php if(get_user_role() == 'customer'): ?>
                            <br/>
                            <div class="password_change">
                                 <?php if(is_array($banks)) : foreach ($banks as $key => $bank) : ?>
                                    <dl class="dl-horizontal banklist">
                                        <dd class="banklist_title">
                                            <section>
                                                <label class="input">
                                                    <i class="icon_append fa fa-times" data-bankid="<?php echo $key; ?>"></i>
                                                    <?php $class = $bank['verification'] == 'verified' ? 'check' : 'cross'; ?>
                                                    <p class="label <?php echo $class; ?>">
                                                    <?php echo $bank['bank_name']; ?></p>
                                                </label>
                                            </section>
                                        </dd>
                                    </dl>
                                    <form>
                                        <dl class="dl-horizontal">
                                            <dt><?php esc_html_e( 'Bank name', 'listify_child' ); ?></dt>
                                            <dd>
                                                <section>
                                                    <label for="bank_name" class="input">
                                                        <i class="icon_append fa fa-university"></i>
                                                        <input type="text" name="bank_name" id="bank_name" value="<?php echo $bank['bank_name']; ?>">
                                                        <b class="tooltip tooltip-bottom-right">Enter Bank name</b>
                                                    </label>
                                                </section>
                                            </dd>
                                            <hr>
                                            <dt><?php esc_html_e( 'Bank Routing Number', 'listify_child' ); ?></dt>
                                            <dd>
                                                <section>
                                                    <label for="bank_routing" class="input">
                                                        <i class="icon_append fa fa-wifi"></i>
                                                        <input type="text" name="bank_routing" id="bank_routing" value="<?php echo $mysavingwallet->ccMasking($bank['bank_routing']); ?>">
                                                        <b class="tooltip tooltip-bottom-right">Bank Routing Number</b>
                                                    </label>
                                                </section>
                                            </dd>
                                            <hr>
                                            <dt><?php esc_html_e( 'Bank Account Number', 'listify_child' ); ?></dt>
                                            <dd>
                                                <section>
                                                    <label for="account_number" class="input">
                                                        <i class="icon_append fa fa-credit-card-alt"></i>
                                                        <input type="text" name="account_number" id="account_number" value="<?php echo $mysavingwallet->ccMasking($bank['account_number']); ?>">
                                                        <b class="tooltip tooltip-bottom-right">Bank Account Number</b>
                                                    </label>
                                                </section>
                                            </dd>
                                            <hr>
                                            <dt><?php esc_html_e( 'Account Type', 'listify_child' ); ?></dt>
                                            <dd>
                                                <section>
                                                    <label class="input">
                                                        <select name="account_type">
                                                            <option value="checking" <?php if($bank['account_type'] == "checking") echo "selected"; ?>>Checking</option>
                                                            <option value="savings" <?php if($bank['account_type'] == "savings") echo "selected"; ?>>Savings</option>
                                                        </select>
                                                    </label>
                                                </section>
                                            </dd>
                                            <hr>
                                            <dt><?php esc_html_e( 'Support Doc', 'listify_child' ); ?></dt>
                                            <dd>
                                                <section>
                                                    <label for="async-upload" class="input">
                                                        <i class="icon_append fa fa-life-ring"></i>
                                                        <p class="image-notice"></p>
                                                        <input type="file" name="async-upload" id="async-upload" class="bank_docs" accept="image/*">
                                                        <input type="hidden" name="image_id" class="image_id">
                                                    </label>
                                                </section>
                                            </dd>
                                        </dl>       
                                    </form> 
                                        
                                    <?php endforeach; else : ?>
                                        <p> You have no payment info </p>
                                    <?php endif; ?>
                                </div>
                            <br/>
                            <a class="button add_bank"> Add a new bank </a>
                            <div class="remodal user_profile" data-remodal-id="add_bank" data-remodal-options="hashTracking: false">
                                <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
                                <h2>Add a new bank info</h2>
                                <p class="add_bank_message"></p>
                                <div class="verification_content">
                                    <div id="modal1Desc">
                                        <div class="password_change">
                                            <form id="add_bank" class="password_change" action="#" method="post" enctype="multipart/form-data">
                                                <dl class="dl-horizontal">
                                                    <dt><?php esc_html_e( 'Bank name', 'listify_child' ); ?></dt>
                                                    <dd>
                                                        <section>
                                                            <label for="bank_name" class="input">
                                                                <i class="icon_append fa fa-university"></i>
                                                                <input type="text" name="bank_name" id="bank_name" placeholder="Your Bank name">
                                                                <b class="tooltip tooltip-bottom-right">Enter Bank name</b>
                                                            </label>
                                                        </section>
                                                    </dd>
                                                    <hr>
                                                    <dt><?php esc_html_e( 'Bank Routing Number', 'listify_child' ); ?></dt>
                                                    <dd>
                                                        <section>
                                                            <label for="bank_routing" class="input">
                                                                <i class="icon_append fa fa-wifi"></i>
                                                                <input type="text" name="bank_routing" id="bank_routing" placeholder="Bank Routing Number (9 Digits only)">
                                                                <b class="tooltip tooltip-bottom-right">Bank Routing Number (9 Digits only)</b>
                                                            </label>
                                                        </section>
                                                    </dd>
                                                    <hr>
                                                    <dt><?php esc_html_e( 'Bank Account Number', 'listify_child' ); ?></dt>
                                                    <dd>
                                                        <section>
                                                            <label for="account_number" class="input">
                                                                <i class="icon_append fa fa-credit-card-alt"></i>
                                                                <input type="text" name="account_number" id="account_number" placeholder="Bank Account Number (up to 16 digits)">
                                                                <b class="tooltip tooltip-bottom-right">Bank Account Number (up to 16 digits)</b>
                                                            </label>
                                                        </section>
                                                    </dd>
                                                    <hr>
                                                    <dt><?php esc_html_e( 'Account Type', 'listify_child' ); ?></dt>
                                                    <dd>
                                                        <section>
                                                            <label class="input">
                                                                <select name="account_type">
                                                                    <option>Checking</option>
                                                                    <option>Savings</option>
                                                                </select>
                                                            </label>
                                                        </section>
                                                    </dd>
                                                    <hr>
                                                    <dt><?php esc_html_e( 'Support Doc', 'listify_child' ); ?></dt>
                                                    <dd>
                                                        <section>
                                                            <label for="async-upload" class="input">
                                                                <i class="icon_append fa fa-life-ring"></i>
                                                                <p class="image-notice"></p>
                                                                <input type="file" name="async-upload" id="async-upload" class="bank_docs" accept="image/*">
                                                                <input type="hidden" name="image_id" class="image_id">
                                                                <b class="tooltip tooltip-bottom-right">Please provide proof of Account Info – Voided Check or Bank Letter</b>
                                                            </label>
                                                        </section>
                                                    </dd>
                                                    <hr>
                                                    <dt><?php esc_html_e( '', 'listify_child' ); ?></dt>
                                                    <dd>
                                                        <section>
                                                            <label class="input">
                                                                <input type="submit" class="button" value="Save bank info" >
                                                            </label>
                                                        </section>
                                                    </dd>
                                                    <hr>
                                                </dl>        
                                            </form>  
                                        </div>
                                    </div>
                                </div>
                            </div>  


                            <?php endif; ?>  
                            
                        </div><!-- /payment -->

                        <div id="social" class="user_profile_edit fade">
                        	<h2>Manage your Social Media.</h2>
							<hr>
                            <form id="save_social_details" action="" class="social_change password_change" method="post">
							
							<dl class="dl-horizontal">
                                <dt><?php esc_html_e( 'Facebook', 'listify_child' ); ?></dt>
                                <dd>
                                    <section>
                                        <label for="facebook" class="input">
                                            <i class="icon_append fa fa-facebook"></i>
                                            <input type="text" name="facebook" value="<?php echo esc_attr( $facebook ); ?>">
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
                                            <input type="text" name="twitter" value="<?php echo esc_attr( $user->twitter ); ?>">
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
                                            <input type="text" name="linkedin" value="<?php echo esc_attr( $user->linkedin ); ?>">
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
                                            <input type="text" name="instagram" value="<?php echo esc_attr( $user->instagram ); ?>">
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
                        <?php if(get_user_role() == 'business'): ?>
                        <div id="stats">
                            <?php echo do_shortcode('[stats_dashboard]'); ?>
                        </div> <!-- stats -->
                        <?php endif; ?>

                    </div><!-- /tab-content -->
                </div><!-- /user_profile_body -->
            </div><!-- /col-md-9 -->
        </div>   
    </div>
<?php endif; ?>