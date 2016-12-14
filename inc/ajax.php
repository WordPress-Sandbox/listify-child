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





