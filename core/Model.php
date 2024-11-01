<?php
namespace Arevico\Core;

class Model extends Helper\ClassHelper
{
	/** 
	 * @var Registry 
	 * 
	 */
	protected $registry;

	/** 
	 * @var Genesis 
	 * */
	protected $genesis;
	
	public function setRegistry( $registry ){
		$this->registry = $registry;
	}
	
	public function setGenesis($genesis){
		$this->genesis = $genesis;
	}
}
