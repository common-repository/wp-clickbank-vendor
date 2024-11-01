<?php
namespace Arevico\CB;
use Arevico\CB\Service\PostType ;
use Arevico\Core\Helper\Util;
use Arevico\Core;

/**
 * Class for both Admin and Front-End requests
 * 
 */
class Common extends Core\Common
{

	/**
	 * Process ClickBank IPN Request
	 *
	 * @param WP_Query $query main query
	 * @return void
	 */
	public function ipn( $query ){
		if (!Core\Helper\WP::requestUrlMatches( $query, 'ipn/cb'))
			return;

		$message 	= file_get_contents('php://input');
	
		// if ($this->getVar('test',null))
		// 	$message 	= file_get_contents(__DIR__ . '/ipn-data.txt');
		if (empty($message)){
			header("HTTP/1.1 200 OK");
			die();
		}
		
		$options 	= get_option('arvcbOptions', array());
		$options 	= Util::val($options, 'cb', array());

		$ipn 	= new Controller\IPN\ClickBank($message, $options);
		$ipn->processIPN();		

		header("HTTP/1.1 200 OK");
		die(); 
	}

	/**
	 * Entry Point
	 *
	 * @return void
	 */
	public function init(){
		add_action('parse_request', array($this, 'ipn'));
		add_action( 'init', 'Arevico\CB\Controller\PostType::registerProductPostType' );

		add_shortcode( 'pay', 'Arevico\CB\Controller\Shortcode::paymentLink');
	}


}
