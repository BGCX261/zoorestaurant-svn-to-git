<?php


/**
 * 
 * @author Luciano Sifredo
 * Campo de titulo para el record finder que tiene como propiedad un criterio de busqueda
 * por el cual va a buscar el quick search
 *
 */
class AsyncSearchCriteriaTitleField extends AsyncSearchDisplayField {

	/**
	 * 
	 * @var $searchCriteria SearchCriteria
	 */
	private $searchCriteria;
	
	
	/**
	 * Devuelve el criterio de busqueda
	 */
	public function getSearchCriteria() {
		return $this->searchCriteria;
	}
	
	/**
	 * Setea el criterio de busqueda
	 * @param $searchCriteria SearchCriteria
	 */
	public function setSearchCriteria($searchCriteria) {
		return $this->searchCriteria = $searchCriteria;
	}
	
}