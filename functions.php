<?php
/**
 * Listify child theme.
 */
function listify_child_styles() {
    wp_enqueue_style( 'listify-child', get_stylesheet_uri() );
    wp_enqueue_script( 'listify-child-script', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery'), '1.0', true);
}
add_action( 'wp_enqueue_scripts', 'listify_child_styles', 999 );

/** Place any new code below this line */

function custom_css_add_func() {
	if(is_page( array('login_page', 'Login Page'))) {
		echo '<style type="text/css">
		.content-box {
			box-shadow: none;
		}
		</style>';
	}
};
add_action( 'wp_head', 'custom_css_add_func', 99 );


/* shortcode */
function log_reg_form_func() {
	ob_start();
?>

<div class="log_reg_form">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="form">

			      <ul class="tab-group">
			        <li class="tab active"><a href="#signup">Sign Up</a></li>
			        <li class="tab"><a href="#login">Log In</a></li>
			      </ul>
			      
			      <div class="tab-content">
			        <div id="signup">   
			          <h1>Sign Up for Free</h1>
			          
			          <form action="/" method="post">
			          
			          <div class="top-row">
			            <div class="field-wrap">
			              <label>
			                First Name<span class="req">*</span>
			              </label>
			              <input type="text" required autocomplete="off" />
			            </div>
			            <div class="field-wrap">
			              <label>
			                Last Name<span class="req">*</span>
			              </label>
			              <input type="text"required autocomplete="off"/>
			            </div>
			          </div>
			          <div class="field-wrap">
			            <label>
			              Email Address<span class="req">*</span>
			            </label>
			            <input type="email"required autocomplete="off"/>
			          </div>
			          
			          <div class="field-wrap">
			            <label>
			              Set A Password<span class="req">*</span>
			            </label>
			            <input type="password"required autocomplete="off"/>
			          </div>
			          
			          <button type="submit" class="button button-block"/>Get Started</button>
			          
			          </form>

			        </div>
			        
			        <div id="login">   
			          <h1>Welcome Back!</h1>
			          
			          <form action="/" method="post">
			          
			            <div class="field-wrap">
			            <label>
			              Email Address<span class="req">*</span>
			            </label>
			            <input type="email"required autocomplete="off"/>
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
			        
			      </div><!-- tab-content -->
			      
			</div> <!-- /form -->
			</div>
			<div class="col-md-6">
				<div class="form-sign-in-with-social">
	                <div class="form-row form-title-row">
	                    <span class="form-title">Sign in with</span>
	                </div>
	                <a href="#" class="form-google-button">Google</a>
	                <a href="#" class="form-facebook-button">Facebook</a>
	                <a href="#" class="form-twitter-button">Twitter</a>
	            </div>
			</div>
		</div>
	</div>
</div>

<?php
$html = ob_get_contents();
ob_get_clean();
return $html; 
}
add_shortcode('log_reg_form', 'log_reg_form_func');
?>