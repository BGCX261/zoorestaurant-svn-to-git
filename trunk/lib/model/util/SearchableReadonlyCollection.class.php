<?php

/**
 * A collection that let one look for an item by a property value.
 * It is used to return wrap the arrays that returns the model objects.
 * @author Matías Castilla 2009-07-10
 *
 */
class SearchableReadonlyCollection implements Iterator, Countable, JsonSerializable {
	
	private $position = 0;
	private $array = array();
	
    public function __construct($array = array()) {
        $this->position = 0;
        $this->addAll($array);
    }
    
    public function reverse(){
    	$this->array = array_reverse($this->array);
    }

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->array[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->array[$this->position]);
    }

    function add($item) {
    	$this->array[] = $item;
    	return $this;
    }

	public function findByProperty($property, $value) {
		 foreach ($this->array as $item) {
		 	if ($item->$property == $value) {
		 		return $item;
			}
		}
		return null;
	}
	
	/**
	 * Countable interface method.
	 * Returns the number of items int the collection.
	 * @return int
	 */
	public function count() {
		return count($this->array);
	}

	/**
	 * Validates that the collection doesn't contain an object
	 * with the same value of $object in the property $field
	 *
	 * @param string $field
	 * @param Doctrine_Record $object
	 * @return boolean
	 */
	public function validateUnique($field, $object) {
		$existing = $this->findByProperty($field, $object->$field);
	    if (!is_null($existing)) {
			if ($object->isNew() || (!$object->isNew() && $object->getId() != $existing->getId())) {
	  			return false;				
			}
		}
		return true;	  		
	}

	/**
	 * Return an array with the items of the collection
	 *
	 * @return array
	 */
	public function asArray() {
		return $this->array;
	}
	
	/**
	 * Says whether the collection contains a record, using the property $searchField 
	 * as equality criteria
	 *
	 * @param Object $record
	 * @param string $searchField
	 * @return boolean
	 */
	public function contains($record, $searchField = 'id') {
		if ($record->$searchField) {
			return (!is_null($this->findByProperty($searchField, $record->$searchField)));
		}
		return in_array($record, $this->array);
	}
	
	/**
	 * Returns a JSON representation of this collection.
	 * @return String
	 *
	 */
	public function getJson($type = '') {
		//FIXME ???
		if ($type == JsonSerializable::TREE_SERIALIZATION){
			return $this->getTreeJson();
		} else {
			if ($type == JsonSerializable::LOB_SERIALIZATION){
				return $this->getLobJson();
			} else {
				if ($type == JsonSerializable::OBSERVATION_DATA_SERIALIZATION){
					return $this->getObservationDataJson();
				} else {
					if ($type == JsonSerializable::CRITICAL_VALUES_SERIALIZATION){
						return $this->getCriticalValuesJson();
					}
				}
			}
		}

		$json = new JsonArray();
		foreach ($this->array as $element) {
			$json->addObject($element->getJson($type));
		}
		return $json;
	}
	
	public function getTreeJson(){
		$json = new JsonArray();
		foreach ($this as $element) {
			$json->addObject($element->getJson(JsonSerializable::TREE_SERIALIZATION));
		}		
		return $json;
	}
	
	public function getLobJson(){
		$json = new JsonArray();
		foreach ($this as $element) {
			$json->addObject($element->getJson(JsonSerializable::LOB_SERIALIZATION));
		}		
		return $json;
	}	
	
	public function getCriticalValuesJson(){
		$json = new JsonArray();
		foreach ($this as $element) {
			$json->addObject($element->getJson(JsonSerializable::CRITICAL_VALUES_SERIALIZATION));
		}		
		return $json;
	}		
	
	public function getObservationDataJson(){
		$json = new JsonArray();
		foreach ($this as $element) {
			$json->addObject($element->getJson(JsonSerializable::OBSERVATION_DATA_SERIALIZATION));
		}		
		return $json;
	}		
	
	/**
	 * Returns a json string that represents the object data
	 * @return string
	 */
	public function getJsonString() {
		return $this->getJson()->getString();
	}

	/**
	 * Add all the items in the Iterable object received as argument
	 *
	 * @param Iterable $iterable
	 */
	public function addAll($iterable) {
		foreach ($iterable as $item) {
			$this->add($item);
		}
	}


	/**
	 * Removes a set of items from the colelction
	 * @param Iterable $iterable			The set to be removed
	 * @param string $property				The search field, by default 'id'
	 */
	public function removeAll($iterable, $property = 'id') {
		foreach ($iterable as $target) {
			foreach ($this->array as $i => $record) {
				if ($record->$property == $target->$property) {
					unset($this->array[$i]);
				}
			}
		}
		$this->arrange();
		return $this;
	}
	
	/**
	 * Arranges the items after a remove
	 */
	private function arrange() {
		$this->array = array_values($this->array);
	}
	
	/**
	 * Get a the sub collection of N items items from the given position 
	 * @param $position
	 * @return SearchableReadonlyCollection
	 */
	public function sliceNFrom($position, $n) {
		$col = new SearchableReadonlyCollection();
		if ($this->count() < $position + $n) {
			$size = $this->count() - $position;
		} else {
			$size = $n;
		}
		$col->addAll(array_slice($this->array, $position, $size));
		return $col;
	}
	
	
	/**
	 * Get a the sub collection of items from the given position to the end 
	 * @param $position
	 * @return SearchableReadonlyCollection
	 */
	public function sliceFrom($position) {
		$col = new SearchableReadonlyCollection();
		$col->addAll(array_slice($this->array, $position, $this->count() - $position));
		return $col;
	}
	
	
	/**
	 * Get a the sub collection of $quantity items, counting from the first
	 * @param $position
	 * @return SearchableReadonlyCollection
	 */
	public function sliceTo($quantity) {
		$col = new SearchableReadonlyCollection();
		$col->addAll(array_slice($this->array, 0, $quantity));
		return $col;
	}	

	public function asString($separator = '-') {
		$first = true;
		$str = '';
		foreach ($this as $item) {
			if ($first) {
				$first = false;
			} else {
				$str .= ' ' . $separator . ' ';
			} 
			$str .= $item;
		}
		return $str;
	}

	/**
	 * Returns the first element of the collection.
	 * If empty, returns null
	 * @return mixed
	 */
	public function first() {
		if (count($this->array) > 0) {
			return $this->array[0];
		}
		return null;
	}

        /**
	 * Returns the last element of the collection.
	 * If empty, returns null
	 * @return mixed
	 */
	public function last() {
		if (count($this->array) > 0) {
			return $this->array[$this->count()-1];
		}
		return null;
	}
	
	/**
	 * Sorts the array using a function
	 */
	public function sort($class, $sortFunction){
		usort($this->array, array($class,$sortFunction));
	}
	
	/**
	 * Agrega en el primer lugar de la coleccion el item pisando al existente
	 *
	 */
	 
	public function addFirst($item) {
		if (count($this->array) > 0) {
			$this->array[0] = $item;
		}
		
	}
	
	/**
	 * Compares this collection with another one.
	 * The collections are considered equals if they have the same number of items
	 * and the items are the same.   
	 * Check out this case c1 = (x, y, z, z) and c2 = (x, y, y, z): there will be considered equals    
	 * @param Iterable $col
	 * @return boolean
	 */
	public function equals($col) {
		if (count($this) != count($col)) {
			return false;
		}
		foreach ($col as $item) {
			if (!$this->contains($item)) {
				return false;
			}
		}
		return true;
	}
	
}
?>