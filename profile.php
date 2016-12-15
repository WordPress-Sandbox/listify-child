<?php 
/*
Template name: Profile 
*/

if(!is_user_logged_in()){
    wp_redirect(get_template_page_link('login_register.php'));
    exit();
}

get_header();

$user_id = get_current_user_id();
$user = new WP_User($user_id);
$email_status = get_user_meta($user_id, 'email_status', true);

if($user->roles[0] != 'administrator' && $email_status != 'verified') : ?>
    <?php echo get_user_meta($user_id, 'email_code', true); ?>
    <?php echo get_user_meta($user_id, 'email_status', true); ?>
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

<?php else : ?>

<div id="primary">
	<div class="container user_profile">
        <div class="row">
            <div class="col-md-3">
                <div class="user_profile_img">
                    <?php echo get_avatar($user_id, 170); ?>
                </div>
                <div class="user_profile_desc">
                    <h2 class="user_name"><?php echo $user->display_name; ?></h2>
                    <p class="user_bio">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet magnam enim totam inventore nostrum qui unde perferendis, velit. Eligendi neque inventore commodi est numquam vitae voluptatem necessitatibus maiores consequatur in.</p>
                </div>
            </div>

            <div class="col-md-9">
                <div class="user_profile_body">
                    <ul class="tab-group">
                        <li><a href="#profile">Edit Profile</a></li>
                        <li><a href="#passwordTab">Change Password</a></li>
                        <li><a href="#payment">Payment Options</a></li>
                        <li><a href="#settings">Notification Settings</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="profile" class="user_profile_edit fade">
                            <h2>Manage your Name, ID and Email Addresses.</h2>
                            <p>Below are the name and email addresses on file for your account.</p>
                            <br>

                            <?php echo do_shortcode('[avatar_upload]'); ?>

                            <dl class="dl-horizontal">
                                <dt><strong>Your name </strong></dt>
                                <dd>
                                    Edward Rooster 
                                    <span>
                                        <a class="pull-right" href="#">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </span>
                                </dd>
                                <hr>
                                <dt><strong>Your ID </strong></dt>
                                <dd>
                                    FKJ-032440 
                                    <span>
                                        <a class="pull-right" href="#">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </span>
                                </dd>
                                <hr>
                                <dt><strong>Company name </strong></dt>
                                <dd>
                                    Htmlstream 
                                    <span>
                                        <a class="pull-right" href="#">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </span>
                                </dd>
                                <hr>
                                <dt><strong>Primary Email Address </strong></dt>
                                <dd>
                                    edward-rooster@gmail.com 
                                    <span>
                                        <a class="pull-right" href="#">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </span>
                                </dd>
                                <hr>
                                <dt><strong>Phone Number </strong></dt>
                                <dd>
                                    (304) 33-2867-499 
                                    <span>
                                        <a class="pull-right" href="#">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </span>
                                </dd>
                                <hr>
                                <dt><strong>Office Number </strong></dt>
                                <dd>
                                    (304) 44-9810-296 
                                    <span>
                                        <a class="pull-right" href="#">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </span>
                                </dd>
                                <hr>
                                <dt><strong>Address </strong></dt>
                                <dd>
                                    California, US 
                                    <span>
                                        <a class="pull-right" href="#">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </span>
                                </dd>
                                <hr>
                            </dl>
                            <button type="button" class="button button_u">Cancel</button>
                            <button type="button" class="button">Save Changes</button>
                        </div><!-- /profile -->

                        <div id="passwordTab" class="user_profile_edit fade">
                            <h2>Manage your Security Settings</h2>
                            <p>Change your password.</p>
                            <br>
                            <form action="#" method="post" class="password_change">
                                <dl class="dl-horizontal">
                                    <dt>Username</dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon_append fa fa-user"></i>
                                                <input type="text" placeholder="Username" name="username">
                                                <b class="tooltip tooltip-bottom-right">Needed to enter the website</b>
                                            </label>
                                        </section>
                                    </dd>
                                    <dt>Email address</dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon_append fa fa-envelope"></i>
                                                <input type="email" placeholder="Email address" name="email">
                                                <b class="tooltip tooltip-bottom-right">Needed to verify your account</b>
                                            </label>
                                        </section>
                                    </dd>
                                    <dt>Enter your password</dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon_append fa fa-lock"></i>
                                                <input type="password" id="password" name="password" placeholder="Password">
                                                <b class="tooltip tooltip-bottom-right">Don't forget your password</b>
                                            </label>
                                        </section>
                                    </dd>
                                    <dt>Confirm Password</dt>
                                    <dd>
                                        <section>
                                            <label class="input">
                                                <i class="icon_append fa fa-lock"></i>
                                                <input type="password" name="passwordConfirm" placeholder="Confirm password">
                                                <b class="tooltip tooltip-bottom-right">Don't forget your password</b>
                                            </label>
                                        </section>
                                    </dd>    
                                </dl>
                                <br>
                                <section>
                                    <label class="checkbox"><input type="checkbox" id="terms" name="terms"><i></i><p>I agree with the Terms and Conditions</p></label>
                                </section>
                                <br>
                                <button type="button" class="button button_u">Cancel</button>
                                <button type="submit" class="button">Save Changes</button>
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
</div>
<?php endif; ?>

<?php get_footer(); ?>