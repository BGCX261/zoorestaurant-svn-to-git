<?php

class XmlUtil {
	
	public static function validateString($char, $replaceWith, $string) {
		$string = str_replace($char, $replaceWith, $string);
		return $string;
		
	}
	
}

?>