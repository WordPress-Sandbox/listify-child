<?php 


/* search user
http://wordpress.stackexchange.com/questions/105168/how-can-i-search-for-a-worpress-user-by-display-name-or-a-part-of-it
*/
function SearchUser_func() {
	global $msw;
    $select = sanitize_text_field($_POST['select']);
	$search_input = sanitize_text_field($_POST['search_input']);
	$status = 'FAILED';
    $usersids = array();
    $message = array();

    if($select != 'name' && $search_input ) {
        $user = get_user_by($select, $search_input);
        $usersids[] = $user->data->ID;
    } else if( $select == 'name') {
         $args = array (
            'order' => 'ASC',
            'orderby' => 'display_name',
            'search' => '*'.esc_attr( $search_input ).'*',
            // 'search_columns' => array( 'ID', 'user_login', 'user_nicename', 'user_email'),
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key'     => 'first_name',
                    'value'   => $search_input,
                    'compare' => 'LIKE'
                ),
                array(
                    'key'     => 'last_name',
                    'value'   => $search_input,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'description',
                    'value' => $search_input ,
                    'compare' => 'LIKE'
                )
            )
        );

    $wp_user_query = new WP_User_Query($args);
    $users_found = $wp_user_query->get_results();
    $usersids = wp_list_pluck($users_found, 'ID');
    }

    $usersids = array_filter($usersids);

	if ( is_array($usersids) && !empty($usersids) ) {
        $status = 'SUCCESS';
        foreach ($usersids as $id) {
            $each_user = array();
            $user = get_user_by('ID', $id);
            $each_user['userid'] = $user->data->ID;
            $each_user['name'] = $user->data->display_name;
            $each_user['email'] = $user->data->user_email;
            $each_user['roles'] = $user->roles;
            $each_user['avatar'] = get_avatar_url($user->data->ID);
            $each_user['balance'] = get_user_meta( $user->data->ID, 'wallet_balance', true);
            $each_user['currency'] = $msw->currency_symbol;
            $message[] = $each_user;
        }
	} else {
		$message = 'No user found!';
		$status = 'FAILED';
	}

	echo json_encode(array( 'status' => $status, 'responsetext' => $message ));
	die();
}

/* verify unverify bank account */
function verify_unverify_customer_account_func() {
    $userid = $_POST['userid'];
    $routing = sanitize_text_field($_POST['routing']);
    $bank_status = sanitize_text_field($_POST['status']);

    $banks = get_user_meta($userid, 'banks', true);

    if($routing && is_array($banks)) {
        foreach($banks as &$bank){
            if($bank['bank_routing'] == $routing ){
                $bank['verification'] = $bank_status;
                update_user_meta($userid, 'banks', $banks);
                break;
            }
        }    
    }

    echo json_encode(array('status' => $bank_status));
    die();
}


/* Update Bank Note */
function update_admin_bank_notes_func() {
    $userid = $_POST['userid'];
    $routing = sanitize_text_field($_POST['routing']);
    $note = sanitize_text_field($_POST['note']);

    $banks = get_user_meta($userid, 'banks', true);

    if($note && $routing && is_array($banks)) {
        foreach($banks as &$bank){
            if($bank['bank_routing'] == $routing ){
                $bank['note'] = $note;
                update_user_meta($userid, 'banks', $banks);
                break;
            }
        }    
    }

    echo json_encode(array('responsetext' => $banks));
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
            $response['balance'] = $new_balance;
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
                    $docs = '<ul id="list_preview_doc">';
                    foreach ($b['attachment_ids'] as $k => $aid) {
                        $image_atts = wp_get_attachment_image_src( $aid , array(50));
                        $docs .= '<li><a class="magnific-popup" href="'. wp_get_attachment_url($aid) .'"><img src="'. $image_atts[0] .'" /></a></li>';
                    }
                    $docs .= '</ul>';
                }

                $btns = '<a class="verify_btn" data-status="verified" data-userid="'.$id.'" data-routing="'.$b['bank_routing'].'">Verify</a><a class="verify_btn" data-status="declined" data-userid="'.$id.'" data-routing="'. $b['bank_routing'].'">Decline</a>';

                $notetext = $b['note'] ? $b['note'] : 'empty';

                $notes = '<p class="bankNote" data-userid="'.$id.'" data-routing="'.$b['bank_routing'].'">'. $notetext .'</p>';

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
                $each_bank['status'] = ucfirst($b['verification']);
                $each_bank['note'] = $notes;
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

