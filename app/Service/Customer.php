<?php
namespace Arevico\CB\Service;
use Arevico\CB;
use Arevico\Cb\Model;
use Arevico\Core\Helper\Error;

/**
 * 
 * @version 1.0.0
 */
class Customer{

	/**
	 * Add a customer to the database. If it already exist, don't do anything, but return it's
	 * ID anyways
	 *
	 * @param string $email 		Email
	 * @param string $password 		Password
	 * @param string $firstName 	First Name
	 * @param string $lastName 		Last name
	 * @param string $fullName 		Full name
	 * @return Model\Customer 
	 */
	public static function add($email, $password, $firstName = '', $lastName = '', $fullName = '', $testTransaction = false){	
		global $wpdb;

		if ($customer 	= Model\Customer::getBy('email', $email))
			return $customer;

		$passwordHash  = wp_hash_password($password);

		if (strcasecmp($firstName, 'clickbank') ===0 ){
			$firstName 	= '';
			$lastName 	= '';
			$fullName 	= '';
		}

		$newCustomer = 	array(
				'firstName' 	=> $firstName,
				'lastName'	 	=> $lastName,
				'fullName' 		=> $fullName,
				'email' 		=> $email,
				'password' 		=> $passwordHash,
				'registered' 	=> substr(time(),0,20),
				'isTest' 		=> $testTransaction
			);
			
		$customer  		 	= new Model\Customer();
		$customer->setData($newCustomer);
		$customer->save();
		$customer->isNew 	= true;
		return $customer;
	}
}