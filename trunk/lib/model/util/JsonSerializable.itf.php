<?php
/**
 * Defines behaviour for objects that can be serialized to json format.
 * @author Matías Castilla 2009-09-30
 *
 */


interface JsonSerializable {

	const COMPLETE_SERIALIZATION = 'C';
	const SKETCH_SERIALIZATION = 'S';
	const TREE_SERIALIZATION = 'T';
	const LOB_SERIALIZATION = 'L';
	const OBSERVATION_DATA_SERIALIZATION = 'O';
	const SAMPLES_TREE_SERIALIZATION = 'ST';
	const PATIENT_RESULT_SERIALIZATION = 'PR';
	const FILTER_RESULT = 'FR';
	const CRITICAL_VALUES_SERIALIZATION = 'CV';
	const PATIENT_ORDERS_SERIALIZATION = 'POS';	
	
	
	/**
	 * Returns a string with representation of the object in JSON format.
	 * @return AbstractJsonObject
	 *
	 */
	public function getJson($type = '');

}


?>