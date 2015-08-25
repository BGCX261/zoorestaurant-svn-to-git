<?php

/**
 * mozos actions.
 *
 * @package    restaurant
 * @subpackage mozos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class mozosActions extends CrudActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->modelSingular = "mozo";
  }
  
   protected function msgErrorBatchDelete() {
		return "Ocurrio un error cuando se borraron los mozos";
		
	}	

	protected function msgItemSuccessfullyDeleted() {
		return "Mozo borrado con éxito";
	}

	protected function msgItemsSuccessfullyDeleted() {
		return "Mozos borrados con éxito";
	}
	
	protected function msgItemSuccessfullyUpdated() {
		return "Mozo actualizado exitosamente";
	}
	
	protected function msgItemSuccessfullyAdded() {
		return "Mozo agregado con éxito";
	}
		
	
	protected function indexPageTitle() {
		return "Administración Mesas";
	}
	
	protected function showPageTitle() {
		return "Mozo";
	}
	
	protected function newPageTitle() {
		return "Crear Mozo";
	}

	protected function editPageTitle() {
		return "Editar Mozo";
	}
	
	protected function modelList() {
		return Restaurant::getInstance()->mozos();
	}
	
 	protected function modelName() {
  		return "Mesa";
  	}

  	protected function getModelById($id) {
		return Restaurant::getInstance()->mozos()->findByProperty('id', $id);  		
  	}
  	
  	protected function createFilter(sfWebRequest $request) {
		$text = $request->getParameter("text");
  		$filter = new OrCompositeSearchFilter();
		$filter->addCriteria("code", $text);
		$filter->addCriteria("description", $text);
		return $filter;  		
  	}  	
  	
  	
  	protected function isFilterEmpty(sfWebRequest $request) {
  		$text = $request->getParameter("text");
  		return $text == '';
  	}
  	

 	protected function newForm($model = null) {
		return new MozoForm($model);  		
  	}  	

	protected function addToRoot($model) {
		Restaurant::getInstance()->agregarMozo($model);		
	}
	
	/**
	 * @see CurdActions
	 */
	public function entityName() {
		return "mozo";
	}
	
	/**
	 * @see CrudActions
	 */
	public function entityNamePlural() {
		return "mozos";
	}
	
	public function executeAsyncMozos() {
		$mozos = Restaurant::getInstance()->mozos();
		$json = $mozos->getJson();
		return $this->renderText($json->getString());
	}
	
	 
}