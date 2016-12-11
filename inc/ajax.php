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
    }

    if($_POST['phone_verify'] === 'required') {
        echo json_encode('phone_not_verified');
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


function send_sms_function()
 {

    $action = $_POST['action'];
    $nonce = $_POST['nonce'];

    // Your Account SID and Auth Token from twilio.com/console
    $sid = 'ACf2609e774c67bbfc8af7844558d57608';
    $token = '59b014bf2054a4e636a2daa66df6a08f';
    $client = new Client($sid, $token);

    // Use the client to do fun stuff like send text messages!
    $client->messages->create(
        // the number you'd like to send the message to
        '+8801734415341',
        array(
            // A Twilio phone number you purchased at twilio.com/console
            'from' => '561 800-0461',
            // the body of the text message you'd like to send
            'body' => "Hey Azizul! Good luck on the bar exam!"
        )
    );

    echo json_encode('Message successfully sent');
    die();
 }

add_action('wp_ajax_send_sms_localhost', 'send_sms_function');
add_action('wp_ajax_nopriv_send_sms_localhost', 'send_sms_function');






