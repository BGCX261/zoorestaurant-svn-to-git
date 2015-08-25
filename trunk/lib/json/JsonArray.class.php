<?php

class JsonArray extends CompositeJsonObject {
	
	private $data = array();
	
	
	public function withValue($value) {
		$this->addValue($value);
		return $this;
	}
	
	public function withObject($object) {
		$this->addObject($object);
		return $this;
	}
	
	public function addValue($value) {
		$this->data[] = new JsonValue($value);
	}
	
	public function addObject($object) {
		$this->data[] = $object->getJson();
	}
	
	public function getString() {
		$json = '[';
		$first = true;
		foreach ($this->data as $item) {
			if ($first) {
				$first = false;
			} else {
				$json .= ',';
			}
			$json .= $item->getString();
		}
		$json .= ']';
		return $json;
	}


	public function getJson() {
		return $this;
	}

}


?>