<?php

/**
 * Singleton que genera un valor para el filtro.
 *Crea un valor para el filtro y lo devuelve con los criterios de busqueda correspondientes
 *  @author Luciano Sifredo 2010-06-10
 */
class AsyncFilterValue {
	
	private static $instance;
	
	/**
	 * Devuelve la instancia del singleton
	 *
	 * @return AsyncSearch
	 */
	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new AsyncFilterValue();
		}
		return self::$instance;
	}
	
	/**
	 * Arma un valor para buscar en las muestras donde las ordenes estan confirmadas
	 *@return FilterValue $filterValue
	 */
	public function sampleOrderStateConfirmed() {
		$filterValue = new FilterValue();
		
		$searchCriteria = new ToOneRelationPropertyFilter();
		$searchCriteria->setCriteriaAttribute('Order');
		$criteriaProperty = LaboratorySystem::getInstance()->findSearchCriteriaByCode('ORST');//ver fixture filter.yml
		$searchCriteria->setSearchCriteria($criteriaProperty);
		
		$filterType = $searchCriteria->getSearchFilterType();
		$code = "";
		$filterData = $filterType->newData($code);
		$state = OrderState::confirmed();
		$arrayValue = array();
		$arrayValue[0] = $state->getId();
		
		$filterData->unserializeData($arrayValue, $filterType->getSearchDataType());
		
		$filterValue->setSearchCriteria($searchCriteria);
		$filterValue->setFilterData($filterData);
		
		return $filterValue;
		
	}
}