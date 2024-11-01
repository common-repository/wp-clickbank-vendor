<?php

class HTTP{

	public static function preserveQuery($query=null){
		$query = $query ? $query : $_GET();

	}
}