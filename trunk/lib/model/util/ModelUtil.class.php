<?php

/**
 * Helper functions form model classes.
 * @author Matías Castilla 2009-07-06
 *
 */

class ModelUtil {
	
	/**
	 * Filters a relationship of <Traceable> and <SoftDelete> objects excluding historic records 
	 * and deleted records (if the contrary isn't said)
	 *
	 * @param Iterable $collection
	 * @param boolean $onlyActive
	 * @return array
	 */
	public static function getTraceableList($collection, $onlyActive) {
		$result = array();
		foreach ($collection as $item) {
			if ((!$onlyActive || ($onlyActive && !$item->getDeleted()))
				&& !$item->getHistoric()) {
				$result[count($result)] = $item;	
			}
		}
		return new SearchableReadonlyCollection($result);
	}	

	/**
	 * Filters a relationship of <SoftDelete> objects excluding  
	 * deleted records (if the contrary isn't said)
	 *
	 * @param Iterable $collection
	 * @param boolean $onlyActive
	 * @return array
	 */
	public static function getList($collection, $onlyActive = true) {
		$result = array();
		foreach ($collection as $item) {
			if ((!$onlyActive || ($onlyActive && !$item->getDeleted()))) {
				$result[count($result)] = $item;	
			}
		}
		return new SearchableReadonlyCollection($result);
	}	
	
	
	/**
	 * Put all the items of an Iterable object in an array
	 *
	 * @param Iterator $iterator
	 * @returns array
	 */
	public static function collectionToArray($iterator) {
		$result = array();
		foreach ($iterator as $item) {
			$result[count($result)] = $item;
		}
		return $result;
	}

	/**
	 * Find an item in a collection by a given property.
	 * @param Iterator $iterator;
	 * @param string $property;
	 * @param mixed $value
	 * @return Object
	 */
	public static function findByProperty($iterator, $property, $value) {
		 foreach ($iterator as $item) {
			if ($item->$property == $value) {
				return $item;
			}
		}
		return null;
	}

	/**
	 * Returns a doctrine query to obtain the all the entities of a class that
	 * implements SoftDelete behavior.
	 * @param string $class				The extent
	 * @param boolean $onlyActive		Whether to retrive only the active records or all of them
	 * @return Doctrine_Query
	 */
	public static function getListQuery($class, $onlyActive) {
		$query = Doctrine_Query::create()
			->from($class);
		if ($onlyActive) {
			$query = $query->andWhere('deleted = ?', false);
		}
		return $query;
	}
	
	
	/**
	 * Returns a doctrine query to obtain the all the entities of a class that
	 * implements Traceable behavior.
	 * @param string $class				The extent
	 * @param boolean $onlyActive		Whether to retrive only the active records or all of them
	 * @return Doctrine_Query
	 */
	public static function getTraceableListQuery($class, $onlyActive) {
		$query = Doctrine_Query::create()
			->from($class)
			->where('historic = ?', false);
		if ($onlyActive) {
			$query = $query->andWhere('deleted = ?', false);
		}
		return $query;
	}

	
}

?>