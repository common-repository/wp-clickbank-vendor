<?php

namespace Arevico\Core\Helper;

class WP{

	/**
	 * Make sure a query in parse_reqeust matches a specified string
	 *
	 * @param WP_Query $query
	 * @param string $needle
	 * @return void
	 */
	public static function requestUrlMatches( $query, $needle ){	
		if (!isset($query, $query->request))
			return;

		$request  	= trim($query->request, '/');
		$needle 	= trim($needle, '/');

		return  (strncmp($needle, $query->request, strlen($needle) ) === 0 ) ;
	}

}