<?php
namespace Arevico\CB;
use Arevico\Core;

/**
 * Front-end Requests
 * 
 */
class Front extends Core\Front{
	
	private $DownloadPageAccess;

	public function init(){
		
		/**
		 * Protect Pages
		 */
		$this->DownloadPageAccess = $this->registry->inject(new Controller\DownloadPageAccess());
	}
	
}