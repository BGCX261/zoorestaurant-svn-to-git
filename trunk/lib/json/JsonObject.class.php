<?php
	
class JsonObject extends CompositeJsonObject {

	private $data = array();
	
	/**
	 * Adds a basic type property to the json object
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return JsonObject
	 */
	public function withValue($name, $value) {
		if (is_string($value))
			$value = JsonUtil::jsonString($value);
		$this->addValue($name, $value);
		return $this;
	}
	
	/**
	 * Adds a json-serializable-object property
	 *
	 * @param string $name
	 * @param JsonSerializable $object
	 * @return JsonObject
	 */
	public function withObject($name, $object) {
		$this->addObject($name, $object);
		return $this;
	}
	
	public function addValue($name, $value) {
		if (is_string($value))
			$value = JsonUtil::jsonString($value);		
		$this->data[$name] = new JsonValue($value);
	}
	
	public function addObject($name, $object) {
		$this->data[$name] = $object->getJson();
	}
	
	public function addObjectLob($name, $object) {
		$this->data[$name] = $object->getJson(JsonSerializable::LOB_SERIALIZATION);
	}
		
	public function addObjectComplete($name, $object) {
		$this->data[$name] = $object->getJson(JsonSerializable::COMPLETE_SERIALIZATION);
	}	
	
	public function addObjectResult($name, $object) {
		$this->data[$name] = $object->getJson(JsonSerializable::TREE_SERIALIZATION);
	}
		
	public function addArray($name, $jsonArray) {
		if (get_class($jsonArray) != 'JsonArray') {
			throw new InvalidArgumentException('JsonArray expected');
		}
		$this->data[$name] = $jsonArray;
	}
	
	/**
	 * Encodes the object to a string
	 * @see json/AbstractJsonObject#getString()
	 * @return string
	 */
	public function getString() {
		$json = '{';
		$first = true;
		foreach ($this->data as $key => $value) {
			if ($first) {
				$first = false;
			} else {
				$json .= ',';
			}
			$json .= '"' . $key . '":' . $value->getString();
		}
		$json .= '}';
		return $json;
	}
	
	/**
	 * Returns a json representation of this object, namely: returns itself.
	 * @return JsonObject
	 */
	public function getJson($type = '') {
		return $this;
	}
	
}


?>