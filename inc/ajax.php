<?php 

function new_user_register() {

    global $msw;

    $err = array();

    // Verify nonce
    if( !isset( $_POST['reg_nonce'] ) || !wp_verify_nonce( $_POST['reg_nonce'], 'register_user' ) ) {
        $err[] = 'Ooops, something went wrong, please try again later.';
        die();
    }

    if($_POST['phone_status'] != 'verified') {
        $msw->sendPin($_POST['phone']);
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
    if(empty($email) || !$msw->isValidEmail($email) ){
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

    global $msw;

    $err = array();

    // Verify nonce
    if( !isset( $_POST['reg_nonce'] ) || !wp_verify_nonce( $_POST['reg_nonce'], 'register_business' ) ) {
        $err[] = 'Ooops, something went wrong, please try again later.';
        die();
    }

    if($_POST['phone_status'] != 'verified') {
        $msw->sendPin($_POST['bs_phone']);
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
    if(empty($bs_email) || !$msw->isValidEmail($bs_email) ){
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

    global $msw;
    $response = array();

    // Verify nonce
    if( !isset( $_POST['email_verify_nonce'] ) && !wp_verify_nonce( $_POST['email_verify_nonce'], 'email_verify' ) ) {
        $response['status'] = 'ERROR';
        $response['responsetext'] = 'Ooops, something went wrong, please try again later.';
    }

    // Get data 
    $user = new WP_User($msw->user_id);
    $email = sanitize_email($_POST['email']);
    $email_status = $msw->getMetaValue('email_status');

    $sub = "MySavingsWallet email verification";

    $code = mysql_escape_string(md5(rand(1000,5000))) ;
    $pagelink = get_permalink( get_option('woocommerce_myaccount_page_id') );
    $link = add_query_arg('key', $code, $pagelink);

    $message = "<html><body>";
    $message .= "Hi <strong>" . $user->display_name . "<strong>"; 
    $message .= "<h2> Thanks for registering with mysavingswallet. </h2>";
    $message .= "Click on the verify email button to confirm your email. <a style=\"display: inline-block; padding: 5px 10px; background-color: #2854A1; color: #FFF;\" href=\"{$link}\"> Verify email</a>";
    $message .= "</body></html>";

    $headers = "From:info@mysavingswallet.com" . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    if( $email_status == 'unverified' || $email_status == 'pending' ) {
        $mail = wp_mail( $email, $sub, $message, $headers);
        if($mail) {
            wp_update_user( array( 'ID' => $msw->user_id, 'user_email' => $email ) );
            $msw->updateUserMeta('email_code', $code);
            $msw->updateUserMeta('email_status', 'pending');
            $response['status'] = 'SUCCESS';
            $response['responsetext'] = 'Verification link sent to your email. Please check inbox.';
        } else {
            $response['responsetext'] = 'Couldn\'t send verification email.';
        }
    } else {
            $response['status'] = 'ERROR';
            $response['responsetext'] = 'Someting went wrong!';
    }

    echo json_encode($response);
    die();

}
// email_verify_func();
add_action('wp_ajax_email_verify', 'email_verify_func');

/* cashback */
function cashback_func() {
    global $msw;
    $business_id = get_current_user_id();
    $customer_id = $_POST['customer_id'];
    $amount = $_POST['cashback_amount'];

    if(!$msw->checkInteger($amount)) {
        echo json_encode(array('status' => 'error', 'message' => 'Amount must be digit character' ));
        die();
    }
    $business_balance = get_user_meta($business_id, 'wallet_balance', true);
    $customer_balance = get_user_meta($customer_id, 'wallet_balance', true);
    $cashbacks = get_option('cashbacks');
    $company_balance = get_option('company_balance');
    if( $business_balance >= $amount ) {
        $halfamount = bcdiv($amount, '2', 2);
        $business_new_balance = bcsub($business_balance, $amount, 2);
        $customer_new_balance = bcadd($customer_balance, $halfamount, 2);
        $company_new_balance = bcadd($company_balance, $halfamount, 2);
        update_user_meta($business_id, 'wallet_balance', $business_new_balance);
        update_user_meta($customer_id, 'wallet_balance', $customer_new_balance);

        $new_cashback = array();
        $new_cashback['id'] = rand(100000, 999999);
        $new_cashback['customer_id'] = $customer_id;
        $new_cashback['business_id'] = $business_id;
        $new_cashback['customer_balance'] = $customer_new_balance;
        $new_cashback['business_balance'] = $business_new_balance;
        $new_cashback['company_balance'] = $company_new_balance;
        $new_cashback['amount'] = $halfamount;
        $new_cashback['time'] = current_time('mysql');

        $cashbacks[] = $new_cashback;
        update_option('cashbacks', $cashbacks, false);
        update_option('company_balance', $company_new_balance, false);


        // emailing
        $business = new WP_User($business_id);
        $customer = new WP_User($customer_id);
        $b_email = $business->user_email;
        $c_email = $customer->user_email;
        $b_sub = "Cashback Sent Successful";
        $c_sub = "New Cashback Received!";
        $b_message = "<html><body>Hello {$business->first_name}, your cashback payment of {$msw->currency_symbol}{$amount} to {$customer->first_name} {$customer->last_name} has been completed. Your current wallet balance is {$msw->currency_symbol}{$business_new_balance}.</body></html>";
        $c_message = "<html><body>Hello {$customer->first_name}, you just received {$msw->currency_symbol}{$halfamount} cashback from {$business->billing_company}. The amount has been credited to your account.</body></html>";

        $headers = 'From:info@mysavingswallet.com' . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $b_mail = wp_mail( $b_email, $b_sub, $b_message, $headers);
        $c_mail = wp_mail( $c_email, $c_sub, $c_message, $headers);

        $replace_content = "<div class=\"col-md-6 col-md-offset-3\">
        <h2> Your Cashback to <strong>{$customer->first_name} {$customer->last_name}</strong> successful. <br/> Current Balance: {$msw->currency_symbol}{$business_new_balance}</h2>
        </div>";

        echo json_encode(array('status' => 'success', 'message' => 'Cashback amount ' . $msw->currency_symbol . $amount .' successful!', 'balance' => $business_new_balance, 'replace_content' => $replace_content, 'b_message' => $b_message, 'c_message' => $c_message));
        die();
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Balance insufficient. Please topup' ));
        die();
    }
}
add_action('wp_ajax_cashback', 'cashback_func');

/* edit profile */
function save_basic_func(){
    global $msw;
    $data = array_filter($_POST['dd']);

    foreach ($data as $key => $value) {
        $es_value = sanitize_text_field($value);
        $msw->updateUserMeta($key, $es_value);
    }

    echo json_encode('success');
    die();
}

add_action('wp_ajax_save_basic', 'save_basic_func');


function change_password_func(){
    global $msw;
    $data = array_filter($_POST['dd']);
    $current_pass = $data['user_password_current'];
    $user_password = $data['user_password'];
    $conf_user_password = $data['conf_user_password'];

    $user = get_user_by( 'ID', $msw->user_id );

    $status = 'PROCEED';

    if( $user_password !== $conf_user_password ) 
    {
        $message = 'Password didn\'t match';
        $status = 'FAILED';
    } 
    else if ($status == 'PROCEED' && 
        !wp_check_password( $current_pass, $user->data->user_pass, $msw->user_id)) 
    {
        $message = 'Incorrect current password!';
        $status = 'FAILED';
    }

    if($status == 'PROCEED') {
        $user_id = wp_update_user( array( 'ID' => $msw->user_id, 'user_pass' => $user_password ));
        if(is_wp_error($user_id)) {
            $message = 'Current Password Wrong!';
            $status = 'FAILED';
        } else {
            $message = 'Password changed successfully';
            $status = 'SUCCESS';
        }
    }

    echo json_encode( array('status' => $status, 'responsetext' => $message));
    die();
}

add_action('wp_ajax_change_password', 'change_password_func');


function user_settings_func(){
    global $msw;
    $data = array_filter($_POST['dd']);

    $error = array();
    $status = 'success';
    $message = array('Settings saved!');

    foreach ($data as $k => $v ) {
        if($k === 'cashback_percentage') {
            if(!intval($v) || $v < 5 || $v > 35 ) {
                $error[] = 'Input percentage needs to be a number between 5 and 35';
            } else {
                $msw->updateUserMeta($k, $v);
            }
        } else {
            $msw->updateUserMeta($k, $v);
        }
    }

    if(count($error) >= 1 ) {
        $status = 'error';
        $message = $error;
    }

    echo json_encode( array('status' => $status, 'responsetext' => $message[0]));
    die();
}

add_action('wp_ajax_user_settings', 'user_settings_func');

/* save uploaded bank doc */
function upload_bank_doc_func() {
    $usingUploader = 2;
    $fileErrors = array(
        0 => "There is no error, the file uploaded with success",
        1 => "The uploaded file exceeds the upload_max_files in server settings",
        2 => "The uploaded file exceeds the MAX_FILE_SIZE from html form",
        3 => "The uploaded file uploaded only partially",
        4 => "No file was uploaded",
        6 => "Missing a temporary folder",
        7 => "Failed to write file to disk",
        8 => "A PHP extension stoped file to upload" );
    $posted_data =  isset( $_POST ) ? $_POST : array();
    $file_data = isset( $_FILES ) ? $_FILES : array();
    $data = array_merge( $posted_data, $file_data );
   // check_ajax_referer( 'add_bank_docs_nonce', 'nonce' );
    $response = array();
    if( $usingUploader == 1 ) {
        $uploaded_file = wp_handle_upload( $data['upload_bank_doc'], array( 'test_form' => false ) );
        if( $uploaded_file && ! isset( $uploaded_file['error'] ) ) {
            $response['response'] = "SUCCESS";
            $response['filename'] = basename( $uploaded_file['url'] );
            $response['url'] = $uploaded_file['url'];
            $response['id'] = $uploaded_file['id'];
            $response['type'] = $uploaded_file['type'];
        } else {
            $response['response'] = "ERROR";
            $response['error'] = $uploaded_file['error'];
        }
    } elseif ( $usingUploader == 2) {
        $attachment_id = media_handle_upload( 'upload_bank_doc', 0 );
        
        if ( is_wp_error( $attachment_id ) ) { 
            $response['response'] = "ERROR";
            $response['error'] = $fileErrors[ $data['upload_bank_doc']['error'] ];
        } else {
            $fullsize_path = get_attached_file( $attachment_id );
            $pathinfo = pathinfo( $fullsize_path );
            $image_atts = wp_get_attachment_image_src( $attachment_id );
            $response['response'] = "SUCCESS";
            $response['filename'] = $pathinfo['filename'];
            $response['id'] = $attachment_id;
            $response['url'] = $image_atts[0];
            $type = $pathinfo['extension'];
            if( $type == "jpeg"
            || $type == "jpg"
            || $type == "png"
            || $type == "gif" ) {
                $type = "image/" . $type;
            }
            $response['type'] = $type;
        }
    }
    echo json_encode( $response );
    die();
}

add_action("wp_ajax_upload_bank_doc", "upload_bank_doc_func");


/* Delete attachement */
function delete_attachment_func() {
    $id = absint($_POST['id']);
    $response = array();
    if( false === wp_delete_attachment($id) ) {
        $response['status'] = 'ERROR';
        $response['responsetext'] = 'File could not be deleted';
    } else {
        $response['status'] = 'SUCCESS';
    }

    echo json_encode($response);
    die();

}
add_action("wp_ajax_delete_attachment", "delete_attachment_func");


/* save bank info */
function save_bank_func(){
    global $msw;
    $dd = $_POST['dd'];
    $error = array();

    $bank_name      = sanitize_text_field($dd['bank_name']);
    $account_type   = sanitize_text_field($dd['account_type']);
    $bank_routing   = $dd['bank_routing'];
    $account_number = $dd['account_number'];
    $attachment_ids = array_filter($dd['attachment_ids']);

    if(empty($bank_name)) {
        $error[] = 'Bank name required.';
    }

    if(!$msw->checkRoutingNumber($bank_routing)) {
        $error[] = 'Incorrect routing number.';
    }

    if(!$msw->checkAccountNumber($account_number)) {
        $error[] = 'Incorrect account number.';
    }

    if(!is_array($attachment_ids) || count($attachment_ids) == 0 ) {
        $error[] = 'Please upload a proof of bank account ownership, acceptable forms of verification are voided check, bank letter, or bank statement.';
    }

    $new_bank = array();
    $new_bank['verification']   = 'pending';
    $new_bank['bank_name']      = $bank_name;
    $new_bank['account_type']   = $account_type;
    $new_bank['bank_routing']   = $bank_routing;
    $new_bank['account_number'] = $account_number;
    $new_bank['attachment_ids'] = $attachment_ids;

    if(count($error) == 0 ) {
        $banks = $msw->getMetaValue('banks');
        $banks[] = $new_bank;
        $msw->updateUserMeta('banks', $banks);
        echo json_encode(array('status' => 'success', 'responsetext' => 'New bank added successfully!'));
    } else {
        echo json_encode(array('status' => 'error', 'responsetext' => $error[0], 'attachment_ids' => $attachment_ids));
    }
    
    die();

}

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
    global $gw;
    $amount = $_POST['topup_amount'];
    $card_number = $_POST['card_number']; // 4111111111111111
    $cvv = $_POST['cvv']; // 1010

    $gw->setLogin("demo", "password");
    $gw->setBilling("John","Smith","Acme, Inc.","123 Main St","Suite 200", "Beverly Hills",
            "CA","90210","US","555-555-5555","555-555-5556","support@example.com",
            "www.example.com");
    $gw->setShipping("Mary","Smith","na","124 Shipping Main St","Suite Ship", "Beverly Hills",
        "CA","90210","US","support@example.com");
    $gw->setOrder("1234","Big Order",1, 2, "PO1234","65.192.14.10");

    $r = $gw->doSale($amount, $card_number, $cvv);

    if( $gw->responses['response'] == 1 ) {
        $user_id = get_current_user_id();
        $prev_balance = get_user_meta($user_id, 'wallet_balance', true);
        $new_balance = bcadd($prev_balance, $amount, 2);
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


/* Add Balance to User */
function add_user_balance_func() {    
    global $msw;
    $userid = $_POST['userid'];
    $amount = $_POST['amount'];

    $user = get_userdata( $userid );

    $response = array('status' => 'PROCEED');

    if(!$user) {
        $response['status'] = 'ERROR';
        $response['responsetext'] = 'Invalid User ID'; 
    }
    if(!$msw->checkInteger($amount)) {
        $response['status'] = 'ERROR';
        $response['responsetext'] = 'Input must be valid number';
    }

    if($response['status'] === 'PROCEED') {
        $response['status'] = 'SUCCESS';
        $prev_balance = get_user_meta($userid, 'wallet_balance', true);
        $new_balance = bcadd($prev_balance, $amount, 2);
        update_user_meta($userid, 'wallet_balance', $new_balance);
        $response['responsetext'] = $msw->currency_symbol . $amount . ' has been added to ' . $user->display_name;
    }

    echo json_encode($response);
    die();

}

add_action('wp_ajax_add_user_balance', 'add_user_balance_func'); 

/* withdraw request */
function withdraw_request_func() {
    global $msw;
    $amount = $_POST['amount'];

    $response = array('status' => 'proceed');

    if(!$msw->checkInteger($amount)) {
        $response['status'] = 'ERROR';
        $response['responsetext'] = 'Amount must be digit character';
    } else if($msw->minwithdraw > $amount) {
        $response['status'] = 'ERROR';
        $response['responsetext'] = 'Minimum withdraw amount is ' . $msw->minwithdraw;
    }

    $verifiedbanks = $msw->verifiedbanks();
    if(is_array($verifiedbanks)) {
        $selected_bank = array_filter($verifiedbanks, function($v) { return $v['bank_name'] == $_POST['bank']; });
    }

    if(empty($selected_bank)) {
        $response['status'] = 'ERROR';
        $response['responsetext'] = 'Error in selecting bank.';
    }

    if($response['status'] === 'proceed' ) {
        $prev_balance = $msw->getMetaValue('wallet_balance');
        $new_balance = bcsub($prev_balance, $amount, 2);

        $new_withdraw = array();
        $new_withdraw['prev_balance'] = $prev_balance;
        $new_withdraw['new_balance'] = $new_balance;
        $new_withdraw['time'] = current_time('mysql');
        $new_withdraw['amount'] = $amount;
        $new_withdraw['id'] = rand(100000, 999999);
        $new_withdraw['status'] = 'pending';
        $new_withdraw['bank'] = $_POST['bank'];

        $withdrawls = $msw->getMetaValue('withdrawls');
        $withdrawls[] = $new_withdraw;
        $msw->updateUserMeta('withdrawls', $withdrawls);
        $msw->updateUserMeta('wallet_balance', $new_balance);

        $response['status'] = 'SUCCESS';
        $response['responsetext'] = 'Your withdraw is pending for approval.';
        $response['balance'] = $new_balance;
    } 

    echo json_encode($response);
    die();
}
add_action('wp_ajax_withdraw_request', 'withdraw_request_func');


?>