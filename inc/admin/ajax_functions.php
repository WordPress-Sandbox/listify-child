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

        $prev_balance = get_user_meta($userid, 'wallet_balance', true);
        // debit or credit by action 
        if($process == 'credit') {
        	$new_balance = bcadd($prev_balance, $amount, 2);
        } else if ( $process == 'debit') {
        	$new_balance = bcsub($prev_balance, $amount, 2);
        }

        // update new balance 
        if($new_balance !== $prev_balance ) {
            $response['status'] = 'SUCCESS';
        	update_user_meta($userid, 'wallet_balance', $new_balance);
        	$response['responsetext'] = $msw->currency_symbol . $amount . ' has been ' . $process . 'ed to ' . $user->display_name;
        } else {
            $response['status'] = 'ERROR';
        	$response['responsetext'] = 'Failed to adjust the balance. No changes made in balance';
        }
    }

    echo json_encode($response);
    die();

}

/* withdrawls report */
function withdrawls_report_admin_func() {
    global $msw;
    $query = stripslashes($_GET['query']);
    $query = json_decode($query, true);
    $user_ids = get_users(array('role__in' => array('customer'), 'fields' => 'ID'));
    $res = array();
    foreach ($user_ids as $id) {
        $user = get_userdata($id); 
        $withdrawls = get_user_meta($id, 'withdrawls', true);
        if(is_array($withdrawls) && count($withdrawls) > 0 ) {
            foreach ($withdrawls as $key => $with)  {
                $each_withdraw = array();
                $each_withdraw['name'] = $user->display_name;
                $each_withdraw['id'] = $id;
                $each_withdraw['email'] = $user->user_email;
                $each_withdraw['username'] = $user->user_login;
                $each_withdraw['date'] =  date("M/d/Y", strtotime($with['time']));
                $each_withdraw['time'] = date("h:i A", strtotime($with['time']));
                $each_withdraw['bank'] = $with['bank'];
                $each_withdraw['amount'] = $msw->currency_symbol . number_format($with['amount'], 2, '.', ',');
                if( $query['load'] == $with['status']) {
                    $res["data"][] = $each_withdraw;
                } else if ( $query['load'] == 'all' ) {
                    $res["data"][] = $each_withdraw;
                }
            }
        }
    }

    echo json_encode($res);
    die();
}


/* cashback report */
function cashback_report_admin_func() {
    global $msw;
    $res = array();
    $cashbacks = array_reverse(get_option('cashbacks'));
    if(is_array($cashbacks) && count($cashbacks) > 0 ) {
        foreach ($cashbacks as $key => $cash)  {
            $each_cashback = array();
            $each_cashback['cashback_id'] = $cash['id'];
            $each_cashback['customer_id'] = $cash['customer_id'];
            $each_cashback['business_id'] = $cash['business_id'];
            $each_cashback['customer_balance'] = $msw->currency_symbol . number_format($cash['customer_balance'], 2, '.', ',');
            $each_cashback['business_balance'] = $msw->currency_symbol . number_format($cash['business_balance'], 2, '.', ',');
            $each_cashback['company_balance'] = $msw->currency_symbol . number_format($cash['company_balance'], 2, '.', ',');
            $each_cashback['amount'] = $msw->currency_symbol . number_format($cash['amount'], 2, '.', ',');
            $each_cashback['date'] = date("M/d/Y", strtotime($cash['time']));
            $each_cashback['time'] = date("h:i A", strtotime($cash['time']));
            $res["data"][] = $each_cashback;
        }
    } 
    echo json_encode($res);
    die();
}


/* banks report */
function bank_report_admin_func() {
    global $msw;
    $query = stripslashes($_GET['query']);
    $query = json_decode($query, true);
    $user_ids = get_users(array('role__in' => array('customer'), 'fields' => 'ID'));
    $res = array();
    foreach ($user_ids as $id) {
        $user = get_userdata($id); 
        $banks = get_user_meta($id, 'banks', true);
        if(is_array($banks) && count($banks) > 0 ) {
            foreach ($banks as $k => $b)  {

                // get attachments 
                $docs = '';
                if(is_array($b['attachment_ids'])) {
                    $docs = '<ul>';
                    foreach ($b['attachment_ids'] as $k => $aid) {
                        $image_atts = wp_get_attachment_image_src( $aid , array(50));
                        $docs .= '<li><a class="magnific-popup" href="'. wp_get_attachment_url($aid) .'"><img src="'. $image_atts[0] .'" /></a></li>';
                    }
                    $docs .= '</ul>';
                }

                $btns = '<a class="verify_btn" data-status="verified" data-userid="'.$id.'" data-bankkey="'.$k.'">Check as verified</a><a class="verify_btn" data-status="declined" data-userid="'.$id.'" data-bankkey="'.$k.'">Check as Declined</a>';

                $each_bank = array();
                $each_bank['customer_id'] = $id;
                $each_bank['customer_username'] = $user->user_login;
                $each_bank['customer_email'] = $user->user_email;
                $each_bank['customer_name'] = $user->display_name;
                $each_bank['bank_name'] = $b['bank_name'];
                $each_bank['account_type'] = $b['account_type'];
                $each_bank['routing_number'] = $b['bank_routing'];
                $each_bank['account_number'] = $b['account_number'];
                $each_bank['support_doc'] = $docs;
                $each_bank['action_btn'] = $btns;

                if( $query['load'] == $b['verification']) {
                    $res["data"][] = $each_bank;
                } else if ( $query['load'] == 'all' ) {
                    $res["data"][] = $each_bank;
                }
            }
        }
    }

    echo json_encode($res);
    die();
};

