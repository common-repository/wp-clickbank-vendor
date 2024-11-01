<?php
namespace Arevico\Core;

/**
 * PSR-04 Autolaoder
 * 
 * http://www.php-fig.org/psr/psr-4/examples/
 */
if (!class_exists('Autoload')){

class Autoload{

	private static $instance 	= null;
	private $classMap 			= array();

	/**
	 * Autoload a class
	 *
	 * @param string $class
	 * @return void
	 */
	public function load( $class ){

		foreach ($this->classMap as $namespace => $dir) 
			if ($this->matchLoad($class, $namespace, $dir));
				return true;

		return false;
	}

	public function matchLoad($class, $namespace, $dir){
		$len 		= strlen( $namespace );
		$matches 	= strncasecmp($class, $namespace, $len) === 0;

		if ($matches){
			$path 	= str_replace('\\', '/', substr($class, $len + 1) );
			require $dir . '/' . $path . '.php';
		}
		
		return $matches;
	}
	

	/**
	 * Register a autoload path
	 *
	 * @param string $namespace
	 * @param string $dir
	 * @param boolean $lib
	 * @return void
	 */
	public function register( $namespace, $dir, $lib = false ){
		$newclassMapping = array($namespace => $dir);

		if ($lib) // A library is less important, a namespace will overwrite the library
			$this->classMap = array_merge($newclassMapping, $this->classMap);

		if (!$lib)
			$this->classMap = array_merge($this->classMap, $newclassMapping);
	}

	public static function get(){
		return isset(self::$instance) ? self::$instance : self::$instance = new self();
	}

	function __construct(){
		$this->register(__NAMESPACE__, __DIR__, false);
		spl_autoload_register( array($this, 'load'));
	}

}

}