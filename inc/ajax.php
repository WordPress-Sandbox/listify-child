<?php 


// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

function isValidEmail($email){ 
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function new_user_register() {

    $err = array();

    // Verify nonce
    if( !isset( $_POST['reg_nonce'] ) || !wp_verify_nonce( $_POST['reg_nonce'], 'register_user' ) ) {
        $err[] = 'Ooops, something went wrong, please try again later.';
        die();
    }

    if($_POST['phone_verify'] == 'unverified') {

        $sid = 'ACf2609e774c67bbfc8af7844558d57608';
        $token = '59b014bf2054a4e636a2daa66df6a08f';
        $pin = rand(1000, 9999);
        $client = new Client($sid, $token);

        /* send verfication sms */
        $client->messages->create(
            $_POST['phone'],
            array(
                'from' => '561 800-0461',
                'body' => 'Your mysavingswallet pin is: ' . $pin
            )
        );

        echo json_encode(array('pin'=>$pin));
        die();
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
    if(empty($email) || !isValidEmail($email) ){
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

function new_business_user_register() {

    $err = array();

    // Verify nonce
    if( !isset( $_POST['register_business_nonce'] ) || !wp_verify_nonce( $_POST['register_business_nonce'], 'register_business' ) ) {
        $err[] = 'Ooops, something went wrong, please try again later.';
        die();
    }

    if($_POST['phone_verify'] == 'unverified') {

        $sid = 'ACf2609e774c67bbfc8af7844558d57608';
        $token = '59b014bf2054a4e636a2daa66df6a08f';
        $pin = rand(1000, 9999);
        $client = new Client($sid, $token);

        /* send verfication sms */
        $client->messages->create(
            $_POST['phone'],
            array(
                'from' => '561 800-0461',
                'body' => 'Your mysavingswallet pin is: ' . $pin
            )
        );

        echo json_encode(array('pin'=>$pin));
        die();
    }
 
    // Get data 
    $business_name= $_POST['business_name'];
    $bs_username= $_POST['bs_username'];
    $bs_fname= $_POST['bs_fname'];
    $bs_lname = $_POST['bs_lname'];
    $bs_gender = $_POST['bs_gender'];
    $bs_dd = $_POST['bs_dd'];
    $bs_email    = $_POST['bs_email'];
    $bs_phone    = $_POST['bs_phone'];
    //$phone_status = $_POST['phone_verify'];
    $bs_streetaddress    = $_POST['bs_streetaddress'];
    $bs_apartmentsuite    = $_POST['bs_apartmentsuite'];
    $bs_city = $_POST['bs_city'];
    $bs_state = $_POST['bs_state'];
    $bs_zip = $_POST['bs_zip'];
    $bs_country = $_POST['bs_country'];
    $bs_pass = $_POST['bs_pass'];

    // Validate 
    if(empty($business_name)){
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
    if(empty($bs_email) || !isValidEmail($bs_email) ){
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
        'role'       => 'customer'
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

add_action('wp_ajax_register_user', 'new_business_user_register');
add_action('wp_ajax_nopriv_register_user', 'new_business_user_register');


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





