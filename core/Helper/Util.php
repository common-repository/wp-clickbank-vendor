<?php

namespace Arevico\Core\Helper;

class Util{


	/**
	 * Get value from a multidimensional array;  - To iterate is human, to recurse divine-
	 *
	 * @todo fix access with numerical key (public stdClass property can't be numerical only')
	 * @param mixed 	$object 	the object we want to get
	 * @param string 		$name 		Key we want to query. Can be in array or object syntax.
 	 * @param mixed 		$notFOund 	Value to return when entry is not found
	 * @example Helper\Util::val( $_POST, 'o->person->name' )
	 */
	public static function val($object, $name, $notFound = null){
		$name    	= is_array($name) ? $name : preg_split('/(\\-\\>|\\[)/', $name); // we can use both the array syntax )[123] or the object one (->)
		$name[0] 	= rtrim($name[0],']');

		/* 1. Make sure sub item is accessible or return it */
		if ( is_array($object) && !isset($name[0], $object[$name[0]] ) ){
			return $notFound; 
			
		} elseif (( is_object($object) && !isset($name[0], $object->{$name[0]} ) )){
			return $notFound;}

		/* 2. Recurse*/
		if (is_array($object))
			return count($name) === 1 ?  $object[$name[0]] : self::val($object[$name[0]], array_slice($name, 1), $notFound);

		if (is_object($object))
			return count($name) === 1 ?  $object->{$name[0]} : self::val($object->{$name[0]}, array_slice($name, 1), $notFound);

		return $notFound;
	}

	/**
	 * Check if it is empty for a multi-dimensional array
	 *
	 * @param object $object
	 * @param string $name
	 * @return void
	 */
	public static function isEmpty($object, $name){
		$val 		= self::val($object,$name, null);
		return empty($val);
	}

	public static function safe($object, $name, $notFound = ''){
		echo htmlentities( self::val($object, $name, $notFound ) );
	}

	public static function escape($object, $name, $notFound = ''){
		return htmlentities( self::val($object, $name, $notFound ) );		
	}
	
	/* Array Functions
	 ----------------------------------------------------- */
	/**
	 * Get all values of an array not having a specific key
	 *
	 * @param array $arr The array subjected
	 * @param string $x1 .. $n  A variable argument list with array keys.
	 */
	public static function excArray($arr /* , .. , */){
		$args = func_get_args();
		array_shift($args);

		if ( is_array($args[0]) )
			$args = $args[0];

		return array_diff_key($arr, array_flip($args) );
	}
	
	/**
	 * Get all values of an array with a specific key
	 *
	 * @param array $arr The array subjected
	 * @param string $x1 .. $n  A variable argument list with array keys.
	 */
	public static function incArray($arr /* , .. , */){
		$args = func_get_args();
		array_shift($args);

		if ( is_array($args[0]) )
			$args = $args[0];

		return array_intersect_key($arr, array_flip($args) );
	}
	
	/**
	 * Generate a random string
	 *
	 * @param integer $length
	 * @return void
	 */
	public static function randomString($length = 5){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';		
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}

	/**
	 * Return wether or not the current request is a post one
	 * @return bool true if the request is a post
	 */
	public static function isPost(){
		return 	$_SERVER['REQUEST_METHOD']==="POST";
	}

	public static function ArrayEntities(&$arr){
		array_walk_recursive($arr, function (&$value) {
			$value = htmlentities($value);
		});			
	}

	/**
	 * Fill in template string and return it. The format is {$string_abc}
.	 *
	 * @param string $template the template to be filled in
	 * @param String[] $kvp
	 * @return void
	 */
	public static function template($template, $kvp, $removeUnUsed = true){
		foreach ($kvp as $key => $replace)
			$template = str_replace('{$' . $key . '}', $replace, $template);

		$template = $removeUnUsed ? preg_replace('/\{\$.*?\}/','',$template) : $template;

		return $template;
	}

	public static function noop(){return;}
}