<?php
namespace Arevico\Core\Helper;

/**
 * Small wrapper for \WP_Error
 */
class Error extends \WP_Error{

	public function render($code){
		$messages = $this->get_error_messages($code);
		if (empty($messages ))
			return;

		$messages = implode($messages);
		$classes 	= 'explain arevico-error';
		echo "<div class=\"{$classes}\">{$messages}</div>";
	}


	public function hasError(){
		return $this->get_error_code() != '';
	}
}
