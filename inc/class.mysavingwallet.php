<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require get_stylesheet_directory() . '/inc/twilio-php-master/Twilio/autoload.php';
use Twilio\Rest\Client;

class Mysavingwallet {

	private $sid;
	private $token;
	private $from_phone;

	public function __construct() {

		$this->sid 			= 'ACf2609e774c67bbfc8af7844558d57608';
		$this->token 		= '59b014bf2054a4e636a2daa66df6a08f';
		$this->from_phone 	= '561 800-0461';

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

        echo json_encode(array('pin'=>$pin));
        die();

	}

	public function isValidEmail($email){ 
	    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
	}

	public function qrurl() {
		$cashback_page = get_template_page_link('cashback.php');
		$user_id = get_current_user_id();
		return add_query_arg('customer_id', $user_id, $cashback_page);
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
	    if(strlen($accountNumber) < 16 ) {
	        return false;  
	    } else {
	    	return true;
	    }
	}

	public function getMetaValue($k) {
		$user_id = get_current_user_id();
		return get_user_meta($user_id, $k, true);
	}

	public function verificationBadge() {
		$items = array(
			'email_status' => 'Email',
			'phone_status' => 'Phone',
			'bank_status'  => 'Bank'
			);
		foreach ($items as $k => $v) {
			$class = $this->getMetaValue($k) == 'verified' ? 'check' : 'cross';
			echo '<li class="'.$class.'">'.$v.' Verification</li>';
		}
	}

	public function ccMasking($number, $maskingCharacter = 'X') {
	    return substr($number, 0, 4) . str_repeat($maskingCharacter, strlen($number) - 8) . substr($number, -4);
	}

	public function transactions() {
		$transactions = $this->getMetaValue('transactions');
		$html = '<table>';
		$html .= '<tr><th>Transaction ID</th><th>Amount</th><th>Previous Balance</th><th>New Balance</th><th>Time</th></tr>';
		foreach ($transactions as $key => $trans) {
			$html .= '<tr>';
				$html .= '<td>' . $trans['trans_id'] . '</td>';
				$html .= '<td>' . $trans['trans_amount'] . '</td>';
				$html .= '<td>' . $trans['prev_balance'] . '</td>';
				$html .= '<td>' . $trans['new_balance'] . '</td>';
				$html .= '<td>' . $trans['time'] . '</td>';
			$html .= '</tr>';
		}
		$html .= '</table>';
		return $html;
	}

}

?>