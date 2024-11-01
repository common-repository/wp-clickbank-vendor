<?php
// https://support.clickbank.com/hc/en-us/articles/220376507-Instant-Notification-Service
namespace Arevico\CB\Controller\IPN;

use Arevico\Core;
use Arevico\Core\Helper\Util;
use Arevico\CB\Service;
use Arevico\CB\Model;

/**
 * Process ClickBank Instant Payment Notifications
 * 
 * @version 1.0.0
 * 
 * @todo implement rebill - post to GA, disable access when the payment period is over or immidiatly
 * @todo uncancel rebill -  cancel
 */
class ClickBank extends Core\Controller
{

    private $secretKey 		= "";
	private $allowTest 		= false;

	private $message;
	private $transaction;

	/**
	 * Process incoming IPN Requests
	 *
	 * @return void
	 */
    public function processIPN()
    {
		/* 1. Decrypt the IPN Message
		 ----------------------------------------------------- */
		$message    = json_decode($this->message);
		
		if ( json_last_error() != JSON_ERROR_NONE )
			die();

        $encrypted  = $message->notification; 
		$key 		= substr(sha1($this->secretKey), 0, 32);
        $iv         = base64_decode($message->iv);

		/* 2. Do something with all the data
		 ----------------------------------------------------- */
		$transaction 		= $this->transaction 	= $this->decryptMessage($encrypted,$key, $iv);

		if ( $transaction == null )
			die();

		$customer 			= $transaction->customer->billing;

 		if ($this->isAffiliate())
			return;

		if ($this->isTest() && !$this->allowTest)
			return;

		$this->customer = Service\Customer::add(
				$customer->email, 
				$transaction->receipt, 
				$customer->firstName, 
				$customer->lastName, 
				$customer->fullName,
				$this->isTest()
			);
		
		if ($this->isSale())
			$this->processSale();

		if ($this->isRefund() || $this->isChargeback() )
			$this->processRefund();
			
		$this->processTransaction();
	}

	/**
	 * Save Transaction
	 *
	 * @return void
	 */
	public function processTransaction(){
		$dateTime = new \DateTime( $this->transaction->transactionTime );
	
		$transaction = array(
			'customerId' 	 	=> $this->customer->id,
			'transactionTime' 	=> $dateTime->getTimestamp(),
			'receipt' 		 	=> $this->transaction->receipt,
			'transactionType'	=> $this->transaction->transactionType,
			'currency' 			=> $this->transaction->currency,
			'totalOrderAmount'	=> $this->transaction->totalOrderAmount,
			'totalTaxAmount' 	=> $this->transaction->totalTaxAmount,
			'transaction' 		=> serialize($this->transaction),
			
		);

		$transaction = new Model\Transaction($transaction);
		return $transaction->save();
	}
	
	/* 3. Process the data
	 ----------------------------------------------------- */
	/**
	 * Process the Sale
	 *
	 * @param Customer $customer
	 * @param stdClass $transaction
	 * @return void
	 */
	public function processSale(){
		
		foreach ($this->transaction->lineItems as $item){
			$dateTime = new \DateTime( $this->transaction->transactionTime );
			$productAccess = array(
				'customerId' 	 => $this->customer->id,
				'productId' 	 => $item->itemNo,
				'receipt' 		 => $this->transaction->receipt,
				'access' 		 => true,
				'datePurchased'  => $dateTime->getTimestamp(),
				'recurring' 	 => $item->recurring
			);
				
			$productAccess = new Model\ProductAccess( $productAccess );
			$productAccess->save();
		}
	}	

	/**
	 * Customer refunds, access is updated
	 *
	 * @return void
	 */
	public function processRefund(){
		$receipt = $this->transaction->receipt;

		foreach ($this->transaction->lineItems as $item){
			ProductAccess::disableAccess($this->customer->id, $item->itemNo);
		}
		
	}
	
	/* Private Members
	 ----------------------------------------------------- */
 	/**
	 * Decrypt incoming IPN message with a function that exists on the system installation
	 *
	 * @param Base64 $encrypted
	 * @param string $key
	 * @param string $iv
	 * @return void
	 */
	private function decryptMessage($encrypted, $key, $iv){
		if (function_exists('mcrypt_decrypt') ){ 	/* mcrypt */
			$decrypted 	= mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv);
			
		} else { /* Open_SSL */
			$decrypted 	= openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_ZERO_PADDING, $iv); 
		}

		$decrypted 	= trim($decrypted,"\0..\32"); 			/* Remove Padding */
		$decrypted 	= utf8_encode(stripslashes($decrypted));
		return json_decode($decrypted);
	}

	/* Conditionals
	 ----------------------------------------------------- */
	private function isAffiliate(){
		return \strcasecmp('AFFILIATE', $this->transaction->role) == 0;
	}

	 /**
	  * Check if we are on a test transaction
	  *
	  * @return boolean
	  */
	 public function isTest(){
		return (strncasecmp($this->transaction->transactionType, 'TEST',4) === 0); 
	 }

	 /**
	  * Test if the transaction is a sales transaction
	  *
	  * @return boolean
	  */
	 public function isSale( ){
		$type = trim(strtolower($this->transaction->transactionType));

		return in_array($type, array(
			'sale',
			'jv_sale',
			'test', 
			'test_sale'
		));
	 }

	 /**
	  * Check if it is a new installment
	  */
	 public function isRebill(){

	 }
	 
	 /**
	  * Test if the transaction is a refund transaction
	  *
	  * @return boolean
	  */
	 public function isRefund( ){
		$type = trim( strtolower($this->transaction->transactionType) );

		return in_array($type, array(
			'rfnd', 
			'test_rfnd'
		));
	 }

	 /**
	  * Test if the transaction is a refund transaction
	  *
	  * @return boolean
	  */
	 public function isChargeback( ){
		$type = trim(strtolower($this->transaction->transactionType));

		return in_array($type, array(
			'insf', 	
			'cgbk'
		));
	 }	 


	/* Getters & Setters
	 ----------------------------------------------------- */
	 /**
	  * Construct the IPN object
	  *
	  * @param string 	$o['secretKey'] 	The secret key
	  * @param boolean 	$o['allowTest'] 	Allow test transactions
	  */
	public function __construct($message, $o = array()){
		$this->message 		= $message;
		$this->secretKey 	= Util::val($o, 'secretKey', '');
		$this->allowTest 	= Util::val($o, 'allowTest', false);
	}
}