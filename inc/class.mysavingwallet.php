<?php 

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

	public function sendPin() { 

		// $client = new Client($this->sid, $this->token);
		$pin = rand(1000, 9999);

		/* send verfication sms */
        // $client->messages->create(
        //     $to,
        //     array(
        //         'from' => $this->from_phone,
        //         'body' => 'Your mysavingswallet pin is: ' . $pin
        //     )
        // );

        echo json_encode(array('pin'=>$pin));
        die();

	}

	publick function isValidEmail($email){ 
	    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
	}

}