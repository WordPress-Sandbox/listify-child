<?php 

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

require get_stylesheet_directory() . '/inc/class.mysavingwallet.php';

function new_user_register() {

    $mysavingwallet = new Mysavingwallet;

    $err = array();

    // Verify nonce
    if( !isset( $_POST['reg_nonce'] ) || !wp_verify_nonce( $_POST['reg_nonce'], 'register_user' ) ) {
        $err[] = 'Ooops, something went wrong, please try again later.';
        die();
    }

    if($_POST['phone_verify'] == 'unverified') {
        $mysavingwallet->sendPin($_POST['phone']);
    }
 
    // Get data 
    $username= $_POST['username'];
    $firstname= $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $dd = $_POST['dd'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $phone_status = $_POST['phone_verify'];
    $streetaddress    = $_POST['streetaddress'];
    $apartmentsuite    = $_POST['apartmentsuite'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];
    $pass = $_POST['pass'];

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

    if($_POST['phone_verify'] == 'unverified') {
        $mysavingwallet2->sendPin($_POST['phone']);
    }
 
    // Get data 
    $bs_name = $_POST['bs_name'];
    $bs_type = $_POST['bs_type'];
    $bs_username= $_POST['bs_username'];
    $bs_fname= $_POST['bs_fname'];
    $bs_lname = $_POST['bs_lname'];
    $bs_gender = $_POST['bs_gender'];
    $bs_dd = $_POST['bs_dd'];
    $bs_email    = $_POST['bs_email'];
    $bs_phone    = $_POST['bs_phone'];
    $bs_streetaddress    = $_POST['bs_streetaddress'];
    $bs_apartmentsuite    = $_POST['bs_apartmentsuite'];
    $bs_city = $_POST['bs_city'];
    $bs_state = $_POST['bs_state'];
    $bs_zip = $_POST['bs_zip'];
    $bs_country = $_POST['bs_country'];
    $bs_pass = $_POST['bs_pass'];

    // Validate 
    if(empty($bs_name)){
        $err[] = 'Business name required';
    }        
    if(empty($bs_type)){
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
            //update_user_meta($user_id, 'phone_status', $phone_status);
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
    $username = $_POST['username'];
    $password = $_POST['password'];

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





