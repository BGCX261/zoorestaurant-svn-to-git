<?php
/**
 * Utilities for string processing
 * @author Matías Castilla 2009-06-17
 */

class StringUtil {

	public static function camelize($str) {
		return strtoupper(substr($str, 0, 1)).strtolower(substr($str, 1));
	}
	
}

?>
