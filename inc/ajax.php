<?php 

function isValidEmail($email){ 
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function new_user_register() {
    
    $err = array();

	// Verify nonce
	if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'vz_new_user' ) ) {
		$err[] = 'Ooops, something went wrong, please try again later.';
	}
 
 	// Get data 
    $firstname= $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $company    = $_POST['company'];
    $password = $_POST['pass'];
    $conf_password = $_POST['conf_pass'];

    // Validate 
    if(empty($firstname)){
    	$err[] = 'First name required';
    }    
    if(empty($lastname)){
    	$err[] = 'Last name required';
    }
    if(empty($username)) {
    	$err[] = 'User name required';
    } 
    if(empty($email) || !isValidEmail($email) ){
    	$err[] = 'Valid Email required';
    }
    if(empty($phone)){
    	$err[] = 'Phone number required';
    }
    if(empty($password)) {
        $err[] = 'Password required';
    }    
    if(empty($conf_password)) {
    	$err[] = 'Confirm your password';
    }
    if ($password != $conf_password) {
        $err[] = 'Passwords do not match';
    }

    // data in array
    $userdata = array(
        'user_login' => $username,
        'user_pass'  => $password,
        'user_email' => $email,
        'first_name' => $firstname,
        'last_name'	 => $lastname,
        'role'   	 => 'subscriber'
    );

    $now = current_time( 'mysql' );

    if(empty($err)) {
    	$user_id = wp_insert_user( $userdata );
	    if( !is_wp_error($user_id) ) {
	    	// user meta field
            update_user_meta($user_id, 'phone', $phone);
	    	update_user_meta($user_id, 'passupdate', $now);
	    	// login user
			$creds = array(
			    'user_login'    => $username,
			    'user_password' => $password,
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