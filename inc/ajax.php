<?php 

require_once locate_template('/inc/class.mysavingwallet.php'); 
require_once locate_template('/inc/class.MagicPayGateway.php');

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
            update_user_meta($user_id, 'billing_address_1', $streetaddress);
            update_user_meta($user_id, 'billing_state', $state);
            update_user_meta($user_id, 'apartmentsuite', $apartmentsuite);
            update_user_meta($user_id, 'billing_city', $city);
            update_user_meta($user_id, 'billing_postcode', $postal_code);
            update_user_meta($user_id, 'billing_country', $country);

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

    $mysavingwallet = new Mysavingwallet;

    $err = array();

    // Verify nonce
    if( !isset( $_POST['reg_nonce'] ) || !wp_verify_nonce( $_POST['reg_nonce'], 'register_business' ) ) {
        $err[] = 'Ooops, something went wrong, please try again later.';
        die();
    }

    if($_POST['phone_status'] != 'verified') {
        $mysavingwallet->sendPin($_POST['bs_phone']);
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
    if(empty($bs_email) || !$mysavingwallet->isValidEmail($bs_email) ){
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
            update_user_meta($user_id, 'billing_company', $bs_name);
            update_user_meta($user_id, 'bs_type', $bs_type);
            update_user_meta($user_id, 'billing_phone', $bs_phone);
            update_user_meta($user_id, 'gender', $bs_gender);
            update_user_meta($user_id, 'dd', $bs_dd);
            update_user_meta($user_id, 'billing_address_1', $bs_streetaddress);
            update_user_meta($user_id, 'billing_state', $bs_state);
            update_user_meta($user_id, 'apartmentsuite', $bs_apartmentsuite);
            update_user_meta($user_id, 'billing_city', $bs_city);
            update_user_meta($user_id, 'billing_postcode', $bs_zip);
            update_user_meta($user_id, 'billing_country', $bs_country);
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
    $pagelink = get_permalink( get_option('woocommerce_myaccount_page_id') );
    $link = add_query_arg('key', $code, $pagelink);

    $message = "<html><body>";
    $message .= "Hi <strong>" . $user->display_name . "<strong>"; 
    $message .= "<h2> Thanks for registering with mysavingwallet. </h2>";
    $message .= 'Click on the verify email button to confirm your email. <a style="display: inline-block; padding: 5px 10px; background-color: #2854A1; color: #FFF;" href="'.$link.'"> Verify email</a>';
    $message .= "</body></html>";

    $headers = 'From:info@mysavingswallet.com' . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    //
    if( $user->user_email == $email && $email_status != 'verified' ) {
        $mail = wp_mail( $email, $sub, $message, $headers);
        if($mail) {
            update_user_meta($user_id, 'email_code', $code);
            update_user_meta($user_id, 'email_status', 'pending');
            $err[] = 'success';
        } else {
            $err[] = 'Couldn\'t sent the mail.';
        }
    } else {
        $err[] = 'Something went wrong!';
    }

    echo json_encode($err[0]);
    die();

}

add_action('wp_ajax_email_verify', 'email_verify_func');

/* cashback */
function cashback_func() {
    $mysavingwallet = new Mysavingwallet;
    $business_id = get_current_user_id();
    $customer_id = $_POST['customer_id'];
    $amount = $_POST['cashback_amount'];

    if(!$mysavingwallet->checkInteger($amount)) {
        echo json_encode(array('status' => 'error', 'message' => 'Amount must be digit character' ));
        die();
    }
    $business_balance = get_user_meta($business_id, 'wallet_balance', true);
    $customer_balance = get_user_meta($customer_id, 'wallet_balance', true);
    if( (int) $business_balance >= (int) $amount ) {
        $business_new_balance = (int) $business_balance - (int) $amount;
        $customer_new_balance = (int) $customer_balance + (int) $amount;
        update_user_meta($business_id, 'wallet_balance', $business_new_balance);
        update_user_meta($customer_id, 'wallet_balance', $customer_new_balance);
        echo json_encode(array('status' => 'success', 'message' => 'Cashback amount successful!' ));
        die();
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Balance insufficient. Please topup' ));
        die();
    }
}
add_action('wp_ajax_cashback', 'cashback_func');

/* edit profile */
function save_basic_func(){

    $dd = $_POST['dd'];
    $user_id = get_current_user_id();

    foreach ($dd as $key => $value) {
        if(!empty($value['value'])) {
            $es_value = mysql_escape_string($value['value']);
            update_user_meta($user_id, $value['name'], $value['value']);
        }
    }

    echo json_encode('success');
    die();

}

add_action('wp_ajax_save_basic', 'save_basic_func');


function save_social_func(){
    $dd = $_POST['dd'];
    $user_id = get_current_user_id();

    foreach ($dd as $key => $value) {
        if(!empty($value['value'])) {
            $es_value = mysql_escape_string($value['value']);
            update_user_meta($user_id, $value['name'], $es_value);
        }
    }

    echo json_encode('success');
    die();
}

add_action('wp_ajax_save_social', 'save_social_func');

/* save bank info */
function save_bank_func(){

    $mysavingwallet = new Mysavingwallet;

    $dd = $_POST['dd'];
    $new_bank = array();
    $new_bank['verification'] = 'unverified';
    $error = array();
    foreach($dd as $k => $v) {
        if($v['name'] == 'bank_name') {
            $new_bank['bank_name'] = $v['value'];
        } else if ($v['name'] == 'account_type') {
            $new_bank['account_type'] = $v['value'];
        } else if ($v['name'] == 'bank_routing') {
            if($mysavingwallet->checkRoutingNumber($v['value'])) {
                $new_bank['bank_routing'] = $v['value'];
            } else {
                $error[] = 'Incorrect routing number';
            }
        } else if ($v['name'] == 'account_number') {
            if($mysavingwallet->checkAccountNumber($v['value'])) {
                $new_bank['account_number'] = $v['value'];
            } else {
                $error[] = 'Incorrect account number';
            }
        }

        // else if ($v['name'] == 'image_id') {
        //     if($v['value']) {
        //         $data_array['image_id'] = $v['value'];
        //     } else {
        //         $error[] = 'No Bank Doc Uploaded.';
        //     }
        // }
    }
    if(count($error) == 0 ) {
        $user_id = get_current_user_id();
        $banks = get_user_meta($user_id, 'banks', true);
        $banks[] = $new_bank;
        update_user_meta($user_id, 'banks', $banks);
        echo json_encode('Bank info updated successfully');
    } else {
        echo json_encode(array('error' => $error));
    }
    
    die();

}

// save_bank_func();

add_action('wp_ajax_save_bank', 'save_bank_func');

/* remove a bank */
function remove_bank_func() {
    $user_id = get_current_user_id();
    $banks = get_user_meta($user_id, 'banks', true);
    $bankid = $_POST['bankid'];
    if(array_key_exists($bankid, $banks)) {
        unset($banks[$bankid]);
        update_user_meta($user_id, 'banks', $banks);
        $message = array('status' => 'success', 'responsetext' => 'Bank removed successfully.');
    } else {
        $message = array('status' => 'error', 'responsetext' => 'Bank wasn\'t found.');
    }
    echo json_encode($message);
    die();
}

// remove_bank_func();


add_action('wp_ajax_remove_bank', 'remove_bank_func');

/* add balance */
function topup_func() {
    $data = $_POST;
    $amount = '50.00';
    $gw = new gwapi;
    $gw->setLogin("demo", "password");
    $gw->setBilling("John","Smith","Acme, Inc.","123 Main St","Suite 200", "Beverly Hills",
            "CA","90210","US","555-555-5555","555-555-5556","support@example.com",
            "www.example.com");
    $gw->setShipping("Mary","Smith","na","124 Shipping Main St","Suite Ship", "Beverly Hills",
        "CA","90210","US","support@example.com");
    $gw->setOrder("1234","Big Order",1, 2, "PO1234","65.192.14.10");

    $r = $gw->doSale($amount,"4111111111111111","1010");

    if( $gw->responses['response'] == 1 ) {
        $user_id = get_current_user_id();
        $prev_balance = get_user_meta($user_id, 'wallet_balance', true);
        $new_balance = (int) $prev_balance + (int) $amount;
        update_user_meta($user_id, 'wallet_balance', $new_balance);

        $new_topup = array();
        $new_topup['prev_balance'] = $prev_balance;
        $new_topup['new_balance'] = $new_balance;
        $new_topup['time'] = current_time('mysql');
        $new_topup['trans_amount'] = $amount;
        $new_topup['trans_id'] = $gw->responses['transactionid'];
        $new_topup['response'] = $gw->responses['response'];
        $new_topup['responsetext'] = $gw->responses['responsetext'];

        $topup = get_user_meta($user_id, 'topup', true);
        $topup[] = $new_topup;
        update_user_meta($user_id, 'topup', $topup);
    }


    echo json_encode(
            array(
                'response' => $gw->responses['response'], 
                'responsetext' => $gw->responses['responsetext'],
                'amount' => $amount
            )
        );
    die();
}

add_action('wp_ajax_topup', 'topup_func');

/* verify bank account */
function verify_unverify_customer_account_func() {
    $userid = $_POST['userid'];
    $bankkey = $_POST['bankkey'];
    $status = $_POST['status'];

    $banks = get_user_meta($userid, 'banks', true);
    $banks[$bankkey]['verification'] = $status;
    update_user_meta($userid, 'banks', $banks);
    echo json_encode(array('status' => $status));
    die();

}
add_action('wp_ajax_verify_unverify_customer_account', 'verify_unverify_customer_account_func'); 

/* withdraw request */
function withdraw_request_func() {
    $amount = $_POST['amount'];
    $mysavingwallet = new Mysavingwallet;
    if(!$mysavingwallet->checkInteger($amount)) {
        echo json_encode(array('status' => 'error', 'responsetext' => 'Amount must be digit character'));
        die();
    } else if($mysavingwallet->minwithdraw > $amount) {
        echo json_encode(array('status' => 'error', 'responsetext' => 'Minimum withdraw amount is ' . $mysavingwallet->minwithdraw ));
        die();
    }
    $verifiedbanks = $mysavingwallet->verifiedbanks();
    $selected_bank = array_filter($verifiedbanks, function($v) { return $v['bank_name'] == $_POST['bank']; });
    if(is_array($selected_bank) && count($selected_bank) === 1) {
        $prev_balance = $mysavingwallet->getMetaValue('wallet_balance');
        $new_balance = (int) $prev_balance - (int) $amount;

        $new_withdraw = array();
        $new_withdraw['prev_balance'] = $prev_balance;
        $new_withdraw['new_balance'] = $new_balance;
        $new_withdraw['time'] = current_time('mysql');
        $new_withdraw['amount'] = $amount;
        $new_withdraw['id'] = rand(100000, 999999);
        $new_withdraw['status'] = 'pending';
        $new_withdraw['bank'] = $_POST['bank'];

        $withdrawls = $mysavingwallet->getMetaValue('withdrawls');
        $withdrawls[] = $new_withdraw;
        $mysavingwallet->updateUserMeta('withdrawls', $withdrawls);
        $mysavingwallet->updateUserMeta('wallet_balance', $new_balance);
        echo json_encode(array('status' => 'success', 'responsetext' => 'Your withdraw is pending for approval.'));
        die();
    } else {
        echo json_encode(array('status' => 'error', 'responsetext' => 'Error in selecting bank.'));
        die();
    }
}
add_action('wp_ajax_withdraw_request', 'withdraw_request_func');


?>