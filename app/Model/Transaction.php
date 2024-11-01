<?php
namespace Arevico\CB\Model;
use Arevico\Core;

/**
 * Store Transactions
 * 
 * @version 1.0.0
 */
class Transaction extends Core\DBModel{
	
	protected $objects = array('transaction');
	
	public static function getBy($column, $value){
		if (!$transaction = parent::getBy($column, $value))
			return null;

			if (!empty($transaction->transaction))
				$transaction->transaction = unserialize($transaction->transaction);
		
			return $transaction;
	}

	public function save(){
		if (!empty($this->transaction))
			$this->transaction = serialize($this->transaction);

			parent::save();
	}
	
	public function __construct($transaction = array()){
		parent::__construct('cb_transactions');
		$this->setData($transaction);
	}
	
}
 

