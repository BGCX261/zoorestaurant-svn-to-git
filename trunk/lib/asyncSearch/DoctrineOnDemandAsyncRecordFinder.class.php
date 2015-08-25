<?php
/*
 *  Crea un buscador dinamico
 *  A medida que se ingresan caracteres, realiza la query y devuelve los registros
 */

class DoctrineOnDemandAsyncRecordFinder extends AbstractAsyncRecordFinder {
	
	/**
	 * Nombre de la clase donde va a buscar
	 */
	private $className;
	
	/**
	 * Titulo que aparece en el finder
	 */
	private $title;
	
	/**
	 * Constructor
	 * @param $title string 
	 * @param $className string
	 */
	public  function __construct($title, $className) {
		$this->className = $className;
		$this->title = $title;
	}
	
	
	/**
	 * Adds a free text search field 
	 * Agrega los criteria fields y setea los criterios de busqueda que implementa el filtro
	 * @param mixed $field Could be string if is a property, or array if it's a field accessed through novigation  
	 * @param $label
	 */
	public function addSearchFieldFree($field, $label, $quantity = "") {
		if (gettype($field) == 'array') {
			if (count($field) == 0) {
				throw new InvalidArgumentException('Invalid field');
			}
			$criteriaCollection = new SearchableReadonlyCollection();
			$searchCriteriaProperty = new ToOneRelationPropertyFilter();
			$searchCriteriaProperty->setCriteriaAttribute($field[0]);
			
			//$searchField = new AsyncSearchCriteriaField($field[count($field) - 1], $label);
			
			$searchField = new AsyncSearchCriteriaField($field[0], $label, $quantity);
			//for ($i = count($field) - 2; $i >= 0; $i--) {
				//$searchField = new AsyncSearchRelationField($field[$i], $searchField);
			for ($i = 1; $i < count($field); $i++) {
				if($i == count($field) - 1) {
					
					$searchCriteria = new SearchCriteria();
					$searchCriteria->setCriteriaAttribute($field[$i]);
					$criteriaCollection->add($searchCriteria);
				} else {
					
					$searchCriteriaPropertyAux = new ToOneRelationPropertyFilter();
					$searchCriteriaPropertyAux->setCriteriaAttribute($field[$i]);
					$criteriaCollection->add($searchCriteriaPropertyAux);
				}
			}
			//$criteriaCollection->reverse();
			$i=1;
			foreach($criteriaCollection as $criteria){
				if($i < $criteriaCollection->count() ) {
					$collectionAux = $criteriaCollection->sliceFrom($i);
					$criteria->setSearchCriteria($collectionAux->first());
					$i++;
				}
			}
			$searchCriteriaProperty->setSearchCriteria($criteriaCollection->first());
			
			$criteriaCollection->reverse();
			$searchCriteria = $criteriaCollection->first();
			
		} else {
			$searchCriteria = new SearchCriteria();
			$searchCriteria->setCriteriaAttribute($field);
			$searchField = new AsyncSearchCriteriaField($field, $label, $quantity);			
		}
		
		$filterType = new LikeFilterType();
		$dataType = new SearchDataTypeStringLike();
		$filterType->setSearchDataType($dataType);
		$searchCriteria->setSearchFilterType($filterType);
		
		if (gettype($field) == 'array') {
			$searchField->setSearchCriteria($searchCriteriaProperty);
		} else {
			$searchField->setSearchCriteria($searchCriteria);
		}
		$this->addSearchField($searchField);
	}
	
	/**
	 * Devuelve el nombre de la clase donde se va a buscar 
	 *
	 */
	public function getClassName() {
		return $this->className;
	}
	
	/**
	 * Devuelve el titulo del finder 
	 *
	 */
	public function getTitle() {
		return $this->title;
	}
	
	
	/*
	 * Devuelve una coleccion vacia
	 */
	public function getRecords() {
		$collection = new SearchableReadonlyCollection();
		return $collection;
	}
	
	/**
	 * busca un nombre del campo en la coleccion
	 * @param $fieldName string
	 * @return $field AsyncSearchCriteriaField | null
	 * 
	 */
	public function findField($fieldName){
		foreach($this->searchFields() as $field) {
			if($field->getName() == $fieldName){
				return $field;
			}
		}
		return null;
		
	}
	
	public function findTitleField($fieldName){
		foreach($this->titleFiels() as $field) {
			if($field->getName() == $fieldName){
				return $field;
			}
		}
		return null;
		
	}
	
	
	/**
	 * Adds a display field
	 * to the collection $titleFields
	 *
	 * @param string or array $field the field name
	 * @param string $label the field label
	 */
	
	public function addTitleField($field, $label) {
		
		if (gettype($field) == 'array') {
			if (count($field) == 0) {
				throw new InvalidArgumentException('Invalid field');
			}
			$criteriaCollection = new SearchableReadonlyCollection();
			$searchCriteriaProperty = new ToOneRelationPropertyFilter();
			$searchCriteriaProperty->setCriteriaAttribute($field[0]);
			
			$titleField = new AsyncSearchCriteriaTitleField($field[0], $label);
			
			for ($i = 1; $i < count($field); $i++) {
				if($i == count($field) - 1) {
					$searchCriteria = new SearchCriteria();
					$searchCriteria->setCriteriaAttribute($field[$i]);
					$criteriaCollection->add($searchCriteria);
				} else {
					$searchCriteriaPropertyAux = new ToOneRelationPropertyFilter();
					$searchCriteriaPropertyAux->setCriteriaAttribute($field[$i]);
					$criteriaCollection->add($searchCriteriaPropertyAux);
				}
			}
			$i = 1;
			foreach($criteriaCollection as $criteria){
				if($i < $criteriaCollection->count() ) {
					$collectionAux = $criteriaCollection->sliceFrom($i);
					$criteria->setSearchCriteria($collectionAux->first());
					$i++;
				}
			}
			$searchCriteriaProperty->setSearchCriteria($criteriaCollection->first());
			
			$criteriaCollection->reverse();
			$searchCriteria = $criteriaCollection->first();
			
		} else {
			$searchCriteria = new SearchCriteria();
			$searchCriteria->setCriteriaAttribute($field);
			$titleField = new AsyncSearchCriteriaTitleField($field, $label);
		}
			$filterType = new OrStringFilterType();
			$dataType = new SearchDataTypeString();
			$filterType->setSearchDataType($dataType);
			$searchCriteria->setSearchFilterType($filterType);
			
		if (gettype($field) == 'array') {
			$titleField->setSearchCriteria($searchCriteriaProperty);
		} else {
			$titleField->setSearchCriteria($searchCriteria);
		}
		
		
		$this->titleFields[] = $titleField; 
		
		
	}
	
}