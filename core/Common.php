<?php
namespace Arevico\Core;

abstract class Common extends Helper\ClassHelper
{
	/** 
	 * The Common classes (Admin, Front, Ajax) may be instantiated once 
	 *
	 * 
	 */

	protected static $instance;

	protected $request;

	public abstract function init();

	public function __construct(){
		
	}

	// public static function create(){
	// 	$class = get_called_class();
	// 	return $class::$instance ? $class::$instance : ($class::$instance = new $class() );
	// }
}
