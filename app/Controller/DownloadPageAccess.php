<?php
namespace Arevico\CB\Controller;
use Arevico\Core;
use Arevico\CB\Model;
use Arevico\Core\Helper\Util;

/**
 * Guard the Download Pages / Thank You Pages
 * 
 * @version 1.0.0
 */
class DownloadPageAccess extends Core\Controller
{
	/**
	 * Indicated wether or not a user could be found with the receipt or ID or if a user is logged in
	 *
	 * @var boolean
	 */
	protected $userAccess 	= false;
	protected $options 		= array();

	protected $receipt;
	protected $item;

	/**
	 * CHeck if the current use has access to the item
	 *
	 * @var boolean
	 */
	protected $itemAccess 			= false;
	
	protected $associatedproductIds 	= array();

	/**
	 * Parse Download Page Queries
	 *
	 * @param WP_Query $wp
	 * @return void
	 */
	public function parse( $wp ){
		if ( !$this->isThankYou($wp) )
			return;	

		// Set options
		$this->options = get_option('arvcbOptions' ,array());		
	
		// The admin has free access :)
		if (current_user_can('administrator') && Util::val($this->options, 'cb->allowAdmin', null))
			return;

			$this->associatedproductIds = get_post_meta( get_the_ID(), '_arvcb_product', false );
			

		/* 1. 	Check if cbreceipt and item parameters are set
				if so, we assume being redirect from ClickBank*/
		$this->receipt 	= $receipt 	= $this->getVar('cbreceipt', null);
		$this->item 	= $item 	= $this->getVar('item', null);

		$this->redirectedFromCB($wp, $receipt, $item);
	}	

	/**
	 * Process request directed from ClickBank order confirmation page
	 *
	 * @param WP_Query $wp
	 * @param string $receipt
	 * @param string $item
	 * @return void
	 */
	private function redirectedFromCB( $wp, $receipt, $item){
		/* 1. User has access to the specified receipt / item */
		$customer = Model\Customer::getByReceiptItem($receipt, $item);
		if (!$customer)
			wp_die('Customer not found!');

		/* 2. Check if this page is associated with the purchase we want to request access too! */
		if (!in_array($item, $this->associatedproductIds) )
			wp_die('this page doesn\'t belong to the request product ID');

		add_action( 'wp_footer', array($this, 'renderGoogleAnalytics') );
	}

	/**
	 *
	 * @param WP_Query $wp
	 * @return boolean
	 */
	private function isThankYou($wp){
		return is_single() && get_post_type() === 'download-pages';
	}

	/**
	 * Render Google Analytics Details
	 *
	 * @return void
	 */
	public function renderGoogleAnalytics(){
		 if (current_user_can('administrator')) // Don't Track The Admin
			return;

		$transaction = Model\Transaction::getBy('receipt', $this->receipt);
		
		if (Util::val($this->options,'cb->ga')=='1')
			$view = $this->genesis->view('GA')->with('transaction', $transaction)->render();
	}
	
	/* Getters / Setters
	 ----------------------------------------------------- */	
	public function __construct()
	{
		add_action( 'wp', array($this, 'parse') );
	} 
	
}
