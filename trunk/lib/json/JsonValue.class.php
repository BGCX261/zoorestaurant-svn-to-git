<?php
class JsonValue extends AbstractJsonObject {

	private $value;
	
	public function __construct($value) {
		$this->value = $value;
	}
	
	public function getString() {
		if ($this->value === true) {
			return 'true';
		} 
		if ($this->value === false) {
			return 'false';
		}
		return '"'. $this->value .'"';
	}
	
}


?>