<?php
namespace Arevico\Core\Helper;

abstract class ClassHelper {
	/** @var Registry */
	protected $registry;
	
	/** @var Genesis */
	protected $genesis;
		
	/**
	 * Alternative to ::class which is introduced in php 5.5
	 */
	public static function fqcn(){ return get_called_class(); }
	
	public function postVar($name, $notFound = ''){
		return Util::val($this->request->post, $name, $notFound);
	}

	public function getVar($name, $notFound = ''){
		return Util::val($this->request->get, $name, $notFound);
	}

	public function requestVar($name, $notFound = ''){
		return Util::val($this->request->request, $name, $notFound);
	}

	public function setRequestVariables($request){
		$this->request = $request;
	}

	public function setRegistry( $registry ){
		$this->registry = $registry;
	}

	public function setGenesis($genesis){
		$this->genesis = $genesis;
	}

}