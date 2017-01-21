<?php 


/* search user by id */
function SearchUser_func() {
	global $msw;
	$user_id = $_POST['user_id'];
	$status = 'FAILED';
	$user = get_user_by('ID', $user_id);

	if ( ! empty( $user ) ) {
		$message = array();
		$message['name'] = $user->data->display_name;
		$message['email'] = $user->data->user_email;
		$message['roles'] = $user->roles;
		$message['avatar'] = get_avatar_url($user->data->ID);
		$message['balance'] = get_user_meta( $user->data->ID, 'wallet_balance', true);
		$message['currency'] = $msw->currency_symbol;
		$status = 'SUCCESS';
	} else {
		$message = 'User not found!';
		$status = 'FAILED';
	}

	echo json_encode(array( 'status' => $status, 'responsetext' => $message ));
	die();
}

/* debit credit user balance */
function debitCreditUserBalance_func() {
    global $msw;
    $userid = $_POST['user_id'];
    $process = $_POST['process'];
    $amount = $_POST['debit_credit_amount'];

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
        // debit or credit by action 
        if($process == 'credit') {
        	$new_balance = bcadd($prev_balance, $amount, 2);
        } else if ( $process == 'debit') {
        	$new_balance = bcsub($prev_balance, $amount, 2);
        }

        // update new balance 
        if($new_balance) {
        	update_user_meta($userid, 'wallet_balance', $new_balance);
        	$response['responsetext'] = $msw->currency_symbol . $amount . ' has been ' . $process . 'ed to ' . $user->display_name;
        } else {
        	$response['responsetext'] = 'Failed to adjust the balance. No changes made in balance';
        }
    }

    echo json_encode($response);
    die();

}




