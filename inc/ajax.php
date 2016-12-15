<?php 

// Use the REST API Client to make requests to the Twilio REST API
require get_stylesheet_directory() . '/inc/class.mysavingwallet.php';
use Twilio\Rest\Client;

function new_user_register() {

    $mysavingwallet = new Mysavingwallet;

    $err = array();

    // Verify nonce
    if( !isset( $_POST['reg_nonce'] ) || !wp_verify_nonce( $_POST['reg_nonce'], 'register_user' ) ) {
        $err[] = 'Ooops, something went wrong, please try again later.';
        die();
    }

    if($_POST['phone_status'] != 'verified') {
        $mysavingwallet->sendPin($_POST['phone']);
    }
 
    // Get data 
    $username= mysql_escape_string($_POST['username']);
    $firstname= mysql_escape_string($_POST['firstname']);
    $lastname = mysql_escape_string($_POST['lastname']);
    $gender = mysql_escape_string($_POST['gender']);
    $dd = mysql_escape_string($_POST['dd']);
    $email    = mysql_escape_string($_POST['email']);
    $phone    = mysql_escape_string($_POST['phone']);
    $phone_status = mysql_escape_string($_POST['phone_status']);
    $streetaddress    = mysql_escape_string($_POST['streetaddress']);
    $apartmentsuite    = mysql_escape_string($_POST['apartmentsuite']);
    $city = mysql_escape_string($_POST['city']);
    $state = mysql_escape_string($_POST['state']);
    $postal_code = mysql_escape_string($_POST['postal_code']);
    $country = mysql_escape_string($_POST['country']);
    $pass = mysql_escape_string($_POST['pass']);

    // Validate 
    if(empty($username)){
        $err[] = 'User name required';
    }    
    if(empty($firstname)){
        $err[] = 'First name required';
    }    
    if(empty($lastname)){
        $err[] = 'Last name required';
    }
    if(empty($gender)) {
        $err[] = 'Gender required';
    } 
    if(empty($dd)) {
        $err[] = 'Date required';
    } 
    if(empty($email) || !$mysavingwallet->isValidEmail($email) ){
        $err[] = 'Valid Email required';
    }
    if(empty($phone)){
        $err[] = 'Phone number required';
    }
    if(empty($pass)) {
        $err[] = 'Password required';
    }

    // data in array
    $userdata = array(
        'user_login' => $username,
        'user_pass'  => $pass,
        'user_email' => $email,
        'first_name' => $firstname,
        'last_name'  => $lastname,
        'role'       => 'customer'
    );

    if(empty($err)) {
        $user_id = wp_insert_user( $userdata );
        if( !is_wp_error($user_id) ) {
            // user meta field
            update_user_meta($user_id, 'billing_phone', $phone);
            update_user_meta($user_id, 'gender', $gender);
            update_user_meta($user_id, 'dd', $dd);
            update_user_meta($user_id, 'billing_state', $streetaddress);
            update_user_meta($user_id, 'apartmentsuite', $apartmentsuite);
            update_user_meta($user_id, 'billing_city', $city);
            update_user_meta($user_id, 'billing_postcode', $postal_code);
            update_user_meta($user_id, 'country', $country);
            update_user_meta($user_id, 'phone_status', $phone_status);
            update_user_meta($user_id, 'email_status', 'unverified');
            // login user after successful registration
            $creds = array(
                'user_login'    => $username,
                'user_password' => $pass,
                'remember'      => true
            );
            wp_signon( $creds, false );
            echo json_encode('success');
        } else {
            $wp_err = $user_id->get_error_message();
            echo json_encode($wp_err);
        }
    } else {
        echo json_encode($err[0]);
    }

  die();
 
}

add_action('wp_ajax_register_user', 'new_user_register');
add_action('wp_ajax_nopriv_register_user', 'new_user_register');



/* business user registration */

function new_business_register() {

    $mysavingwallet2 = new Mysavingwallet;

    $err = array();

    // Verify nonce
    if( !isset( $_POST['reg_nonce'] ) || !wp_verify_nonce( $_POST['reg_nonce'], 'register_business' ) ) {
        $err[] = 'Ooops, something went wrong, please try again later.';
        die();
    }

    if($_POST['phone_status'] != 'verified') {
        $mysavingwallet2->sendPin($_POST['phone']);
    }
 
    // Get data 
    $bs_name = mysql_escape_string($_POST['bs_name']);
    $bs_type = mysql_escape_string($_POST['bs_type']);
    $bs_username= mysql_escape_string($_POST['bs_username']);
    $bs_fname= mysql_escape_string($_POST['bs_fname']);
    $bs_lname = mysql_escape_string($_POST['bs_lname']);
    $bs_gender = mysql_escape_string($_POST['bs_gender']);
    $bs_dd = mysql_escape_string($_POST['bs_dd']);
    $bs_email    = mysql_escape_string($_POST['bs_email']);
    $bs_phone    = mysql_escape_string($_POST['bs_phone']);
    $phone_status = mysql_escape_string($_POST['phone_status']);
    $bs_streetaddress    = mysql_escape_string($_POST['bs_streetaddress']);
    $bs_apartmentsuite    = mysql_escape_string($_POST['bs_apartmentsuite']);
    $bs_city = mysql_escape_string($_POST['bs_city']);
    $bs_state = mysql_escape_string($_POST['bs_state']);
    $bs_zip = mysql_escape_string($_POST['bs_zip']);
    $bs_country = mysql_escape_string($_POST['bs_country']);
    $bs_pass = mysql_escape_string($_POST['bs_pass']);

    // Validate 
    if(empty($bs_name)){
        $err[] = 'Business name required';
    }        
    if(empty($bs_username)){
        $err[] = 'User name required';
    }    
    if(empty($bs_fname)){
        $err[] = 'First name required';
    }    
    if(empty($bs_lname)){
        $err[] = 'Last name required';
    }
    if(empty($bs_gender)) {
        $err[] = 'Gender required';
    } 
    if(empty($bs_dd)) {
        $err[] = 'Date required';
    } 
    if(empty($bs_email) || !$mysavingwallet2->isValidEmail($bs_email) ){
        $err[] = 'Valid Email required';
    }
    if(empty($bs_phone)){
        $err[] = 'Phone number required';
    }
    if(empty($bs_pass)) {
        $err[] = 'Password required';
    }

    // data in array
    $userdata = array(
        'user_login' => $bs_username,
        'user_pass'  => $bs_pass,
        'user_email' => $bs_email,
        'first_name' => $bs_fname,
        'last_name'  => $bs_lname,
        'role'       => 'business'
    );

    if(empty($err)) {
        $user_id = wp_insert_user( $userdata );
        if( !is_wp_error($user_id) ) {
            // user meta field
            update_user_meta($user_id, 'billing_phone', $bs_phone);
            update_user_meta($user_id, 'gender', $bs_gender);
            update_user_meta($user_id, 'dd', $bs_dd);
            update_user_meta($user_id, 'billing_state', $bs_streetaddress);
            update_user_meta($user_id, 'apartmentsuite', $bs_apartmentsuite);
            update_user_meta($user_id, 'billing_city', $bs_city);
            update_user_meta($user_id, 'billing_postcode', $bs_zip);
            update_user_meta($user_id, 'country', $bs_country);
            update_user_meta($user_id, 'phone_status', $phone_status);
            update_user_meta($user_id, 'email_status', 'unverified');
            // login user after successful registration
            $creds = array(
                'user_login'    => $bs_username,
                'user_password' => $bs_pass,
                'remember'      => true
            );
            wp_signon( $creds, false );
            echo json_encode('success');
        } else {
            $wp_err = $user_id->get_error_message();
            echo json_encode($wp_err);
        }
    } else {
        echo json_encode($err[0]);
    }

  die();
 
}

add_action('wp_ajax_register_business', 'new_business_register');
add_action('wp_ajax_nopriv_register_business', 'new_business_register');


/**
 * User login
 *
 */

function msw_user_login() {
 
    $err = array();

    // Verify nonce
    if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'msw_login_user' ) ) {
        $err[] = 'Ooops, something went wrong, please try again later.';
    }
 
    // Get data 
    $username = mysql_escape_string($_POST['username']);
    $password = mysql_escape_string($_POST['password']);

    // Validate 
    if(empty($username)){
        $err[] = 'Username required';
    }    
    if(empty($password)){
        $err[] = 'Password required';
    }

    if(empty($err)) {
        // login user
        $creds = array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => true
        );
        $user = wp_signon( $creds, false );
        if ( !is_wp_error($user) ) {
            echo json_encode('success');
        } else {
            $wp_err = $user->get_error_message();
            echo json_encode($wp_err);
        }
    } else {
        echo json_encode($err[0]);
    }

  die();
 
}
 
add_action('wp_ajax_nopriv_user_login', 'msw_user_login');



/* email verify */
function email_verify_func() {

    $err = array();

    // Verify nonce
    if( !isset( $_POST['email_verify_nonce'] ) && !wp_verify_nonce( $_POST['email_verify_nonce'], 'email_verify' ) ) {
        $err[] = 'Ooops, something went wrong, please try again later.';
    }

    // Get data 
    $user_id = mysql_escape_string($_POST['user_id']);
    $email = mysql_escape_string($_POST['email']);
    $user = new WP_User($user_id);
    $email_status = get_user_meta($user_id, 'email_status', true);

    $sub = "Mysavingwallet email verification";
    $code = mysql_escape_string(md5(rand(1000,5000))) ;
    $message = "Hi {$user->display_name}, your email verfication code is: {$code}";
    $headers = 'From:admin@mysavingswallet.com' . "\r\n";

    //
    if( $user->user_email == $email && $email_status != 'vkerified' ) {
        $mail = wp_mail( $email, $sub, $message, $headers);
        if($mail) {
            update_user_meta($user_id, 'email_code', $code);
            update_user_meta($user_id, 'email_status', 'pending');
            $err[] = 'success';
        } else {
            $err[] = 'Counldn\'t sent the mail.';
        }
    } else {
        $err[] = 'Something went wrong!';
    }

    echo json_encode($err);
    die();

}

add_action('wp_ajax_email_verify', 'email_verify_func');


function code_verify_func() {

    $err = array();

    // Get data 
    $user_id = mysql_escape_string($_POST['user_id']);
    $code = mysql_escape_string($_POST['code']);
    $email_status = get_user_meta($user_id, 'email_status', true);
    $email_code = get_user_meta($user_id, 'email_code', true);

    if( !empty($code) && $email_code == $code ) {
        update_user_meta($user_id, 'email_status', 'verified');
        $err[] = 'success';
    } else {
        $err[] = 'someting went wrong!';
    }

    echo json_encode($err[0]);
    die();

}

add_action('wp_ajax_code_verify', 'code_verify_func');

