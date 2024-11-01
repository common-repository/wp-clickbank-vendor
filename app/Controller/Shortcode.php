<?php
namespace Arevico\CB\Controller;
use Arevico\Core;
use Arevico\Core\Helper\Util;
use Arevico\CB\Model;

/**
 * Process Shortcodes
 * 
 * @version 1.0.0
 */
class Shortcode extends Core\Controller{

	/**
	 * Shortcode [pay productId=""]
	
	 * 
	 * @param array $atts
	 * @param string $content
	 * @return void
	 */
	public static function paymentLink($atts, $content = ''){
		$queryString = array();

		$o			= get_option('arvcbOptions', array());
		$atts		= array_filter($atts, 'strlen');

		// WordPress converts shortcode attributes automatically to lower case, so we need to construct
		// a new array to build the query variables for the payment link.
		
		$vendor 	= Util::val($o, 'cb->vendor', '');
		$productId 	= Util::val($atts, 'productid', '');
		$newTab		= Util::val($atts, 'newtab') == '1' ? 'target="_blank"' : '';

		if (isset($atts['cbskin'])) $queryString['cbskin'] = $atts['cbskin'];
		if (isset($atts['paymentmethod'])) $queryString['paymentmethod'] = $atts['paymentmethod'];

		$queryString = \build_query( $queryString );

		self::renderTrustBadge($vendor);

		return "<a {$newTab} href='http://{$productId}.{$vendor}.pay.clickbank.net/?{$queryString}'>{$content}</a>";
	}

	public static function renderTrustBadge($vendor){
		wp_enqueue_script( 'cb-trust-badget', "//cbtb.clickbank.net/?vendor={$vendor}", array(), true );
	}
}