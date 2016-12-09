<?php 

function isValidEmail($email){ 
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function new_user_register() {

    $err = array();

    // Verify nonce
    if( !isset( $_POST['reg_nonce'] ) || !wp_verify_nonce( $_POST['reg_nonce'], 'register_user' ) ) {
        $err[] = 'Ooops, something went wrong, please try again later.';
    }
 
    // Get data 
    $username= $_POST['username'];
    $firstname= $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $dd = $_POST['dd'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $streetaddress    = $_POST['streetaddress'];
    $apartmentsuite    = $_POST['apartmentsuite'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];
    $confpass = $_POST['confpass'];
    $pass = $_POST['pass'];

    // echo $username;

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
    if(empty($streetaddress)) {
        $err[] = 'Streetaddress required';
    }    
    if(empty($apartmentsuite)) {
        $err[] = 'Apartmentsuite required';
    }
    if(empty($city)) {
        $err[] = 'City required';
    }
    if(empty($postal_code)) {
        $err[] = 'Postal Code required';
    }
    if(empty($country)) {
        $err[] = 'Country required';
    }

    if(empty($pass)) {
        $err[] = 'Password required';
    }    
    if(empty($confpass)) {
        $err[] = 'Confirm your password';
    }
    if ($pass != $confpass) {
        $err[] = 'Passwords do not match';
    }

    // data in array
    $userdata = array(
        'user_login' => $username,
        'user_pass'  => $pass,
        'user_email' => $email,
        'first_name' => $firstname,
        'last_name'  => $lastname,
        'role'       => 'subscriber'
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
            update_user_meta($user_id, 'confpass', $confpass);
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