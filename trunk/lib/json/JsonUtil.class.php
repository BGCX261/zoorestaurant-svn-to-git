<?php

class JsonUtil {

	/**
	 * Escapes al not allowed characters
	 * @return String
	 */
	public static function jsonString($string){
		$replacePairs = array("\"" => "\\\"", 
							  "\\" => "\\\\");
		
		return strtr($string,$replacePairs);
//		return $string;
	}
}

?>