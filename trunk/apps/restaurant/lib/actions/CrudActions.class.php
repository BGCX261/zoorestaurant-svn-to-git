<?php

/**
 * A template class to be extended by basic CRUD modules.
 * @author Matías Castilla 2009-07-03
 *
 */
abstract class CrudActions extends sfActions
{
	
	const MSG_ERROR_OBJECT_NOT_FOUND = "An error ocurred: the object was not found";	
	const MSG_ERROR_INVALID_OPERATION = "Invalid operation";
	
	/**
	 * Returns the messages to be displayed when an error occurs performing the batch delete action.
	 * 
	 * @return string
	 */
	protected abstract function msgErrorBatchDelete();
	
	/**
	 * Returns the message to be displayed when the delete action was successfully acomplished.
	 *
	 * @return string
	 */
	protected abstract function msgItemSuccessfullyDeleted();

	/**
	 * Returns the message to be displayed when the batch delete action was successfully acomplished.
	 *
	 * @return string
	 */
	protected abstract function msgItemsSuccessfullyDeleted();
	
	/**
	 * Returns the message to be displayed when the update action was successfully acomplished.
	 *
	 * @return string
	 */	
	protected abstract function msgItemSuccessfullyUpdated();
	
	/**
	 * Returns the message to be displayed when the insert action was successfully acomplished.
	 *
	 * @return string
	 */	
	protected abstract function msgItemSuccessfullyAdded();
	
	
	/**
	 * Returns the index title
	 * 
	 * @return string
	*/
	protected abstract function indexPageTitle();

	/**
	 * Returns the visualization page title
	 *
	 * @return sting
	 */
	protected abstract function showPageTitle();
	
	/**
	 * Returns the "new" page title
	 * 
	 * @return string
	 */
	protected abstract function newPageTitle();

	/**
	 * Returns the edition page title
	 *
	 * @return string
	 */
	protected abstract function editPageTitle();
	
	/**
	 * Returns the list of objects of the class beeing manteined by the CRUD
	 *
	 * @return array()
	 */
	protected abstract function modelList();
	
	/**
	 * Returns the module name (the route name)
	 *
	 * @return string
	 */
	protected function moduleName() {
		return $this->moduleName;
	}
	
	/**
	 * Returns subsystem title.
	 * By default no subsystem title is displayed.
	 * @return unknown_type
	 */
	protected function subsystemTitle() {
		return '';
	}
	
	/**
	 * Returns the name of the class maintained by the CRUD
	 *
	 * @return string
	 */
	protected abstract function modelName();
  	
	/**
	 * Return an object of the model class by its id.
	 *
	 * @param Object $id
	 * @return Object
	 */
  	protected abstract function getModelById($id);
  	
  	/**
  	 * Says whether the filter is empty. It must be extended by subclasses
  	 * because it depends on the fields of the search form.
  	 * Nevertheless it could be deduced from it. FIXME
  	 *
  	 * @param sfWebRequest $request
  	 * @return boolean
  	 */
  	protected function isFilterEmpty(sfWebRequest $request) {
  		return true;
  	}
  	
  	/**
  	 * Creates the filter from the search form request.
  	 * It must be redefined by the modules that use a filter.
  	 * @param sfWebRequest $request
  	 * @return SearchFilter
  	 */
  	protected function createFilter(sfWebRequest $request) {
  		return null;
  	}

  	/**
  	 * Returns a form to edit the model object.
  	 *
  	 * @param Object $model
  	 * @return sfForm
  	 */
  	protected abstract function newForm($model = null);

  	/**
  	 * Returns the url of the index page (the list page).
  	 * Default behaviour returns module_name/index.
  	 * @return string
  	 */
	protected function indexUrl() {
		return $this->moduleName().'/index';
	}
	
	/**
	 * Returns the url of the visualization page.
	 * Default behaviour returns module_name/show.
	 *
	 * @return string
	 */
	protected function showUrl() {
		return $this->moduleName().'/show';
	}
  	
	/**
	 * Returns the name of the entity to be displayed.
	 * @return string
	 */
	public abstract function entityName();
	
	/**
	 * Returns the name of the entity (in plural) to be displayed.
	 * @return string
	 */
	public abstract function entityNamePlural();
	


	/**
	 * Adds the new object to the root object.
	 * It's called by the processForm function.
	 * It has to be extended because it depends on the model being created.
	 * For instance, a Contact must be added to a Laboratory, a Laboratory to a LaboratorySystem, etc. 
	 * Returns the root object.
	 * 
	 * @param Object $model
	 * @return Object
	 */
	protected abstract function addToRoot($model);

	/**
	 * Validate unique fields before inserting or updating.
	 * FIXME It MUST be implemented using a Doctrine vlaidator. OK.
	 * TODO Refactor al the modules and delete the method. Temporary
	 * returns always true. Thus, new modules don't need to bother about it.
	 *
	 * @param sfForm $form
	 */
	protected function validateUniqueFields($form) {
		return true;	
	}
	
	/**
	 * Sets an error message to be sent to the template
	 *
	 * @param string $message
	 * @param string $detail
	 */
  	protected function errorMessage($message, $detail = '') {
  		$this->getUser()->setFlash('error', $message);
  		$this->getUser()->setFlash('error_detail', $detail);
  	}
  	
  	/**
  	 * Sets a message to be displayed in the template
  	 *
  	 * @param string $message
  	 * @param string $detail
  	 */
	protected function message($message, $detail = '') {
  		$this->getUser()->setFlash('message', $message);
  		$this->getUser()->setFlash('message_detail', $message);
	}
	
	/**
	 * Returns the paginator that will be used.
	 * It will be overriden for cruds that need to perform the pagination directly by a query
	 * for performance improvement.
	 * @return string
	 */
	protected function getPaginatorClass() {
		return 'SimplePaginator';
	}
	

	/**
	 * Execute the index of the Crud, containing the objet list.
	 *
	 * @param sfWebRequest $request
	 */	
	public function executeIndex(sfWebRequest $request) {
  		try {
			$this->setTitle($this->indexPageTitle());
  			$this->list = $this->modelList();
  			if ($this->showSearch = $this->showSearch()) {
	  			ActionSearchUtil::applyFilter($this, "list", $this->moduleName(), $this->getPaginatorClass());
				$this->search_form = new SimpleSearchForm();
	   		}
  			$page = $this->getRequestParameter('page');
	   		ActionPaginationUtil::applyPagination($this, "list", $page, $this->getPaginatorClass());
  		} catch (ContextualObjectNotFoundException $e) {
  			$this->list = array();
  			$this->errorMessage(self::MSG_ERROR_OBJECT_NOT_FOUND);
  		}
  	}
  	
  	
  	/**
  	 * Sets a filter and redirect to the index.
  	 * This actions is called when the search form is submitted. 
  	 *
  	 * @param sfWebRequest $request
  	 */
  	public function executeSearch(sfWebRequest $request) {
		if (!$this->isFilterEmpty($request)) {
			$filter = $this->createFilter($request);
			ActionSearchUtil::setFilter($this, $filter, $this->moduleName());
		} else {
			die('filter empy');
			ActionSearchUtil::removeFilter($this, $this->moduleName());
		}
		$this->redirect($this->indexUrl());
	}  

  
	/**
	 * Removes the filter and redirects to the index.
	 *
	 * @param sfWebRequest $request
	 */
	public function executeRemoveFilter(sfWebRequest $request) {
		ActionSearchUtil::removeFilter($this, $this->moduleName());
		$this->redirect($this->indexUrl());
	}
    
	/**
	 * Displays the information of an object
	 *
	 * @param sfWebRequest $request
	 */
	public function executeShow(sfWebRequest $request) {
		try {
			$this->setTitle($this->showPageTitle());
			$this->model = $this->getModelById($request->getParameter('id'));
			if(!$this->model) {
				$this->getUser()->setFlash("error", self::MSG_ERROR_OBJECT_NOT_FOUND);
				$this->redirect($this->indexUrl());
			}
			$this->show($this->model);
		} catch (ContextualObjectNotFoundException $e) {
			$this->errorMessage(self::MSG_ERROR_OBJECT_NOT_FOUND);
			$this->redirect($this->globalErrorUrl());
		}
	}

	/**
	 * Hook method that can be overriden by subclasses in order to implement polymorfic behavior.
	 *
	 * @param Object $model
	 */
	protected function show($model) {
		$this->setTemplate('show');
	}
	
	/**
	 * Shows the list of historic version of and object
	 *
	 * @param sfWebRequest $request
	 */
	public function executeShowHistory(sfWebRequest $request) {		
		try {
			$this->setTitle($this->showHistoryPageTitle());
			$this->model = $this->getModelById($request->getParameter('id'));
			
			$arrayModel = array();
			$prev = $this->getModelById($request->getParameter('id'));
		    $i = 0;
			do{				  				
				$arrayModel[$i] = $prev;
				$i++;
			} while ($prev = $prev->getPreviousVersion());
			$this->previousList = $arrayModel;
			if(!$this->model) {
				$this->getUser()->setFlash("error", self::MSG_ERROR_OBJECT_NOT_FOUND);
				$this->redirect($this->indexUrl());
			}
			$this->showHistory($this->model);
		} catch (ContextualObjectNotFoundException $e) {
			$this->errorMessage(self::MSG_ERROR_OBJECT_NOT_FOUND);
			$this->redirect($this->globalErrorUrl());
		}
	}
	
	/**
	 * Hook method that can be overriden by subclasses.
	 *
	 * @param unknown_type $model
	 */
	protected function showHistory($model) {
		$this->setTemplate('showHistory');
	}
	
	/**
	 * Axaj action that complements executeShowHistory
	 *
	 * @param sfRequest $request
	 * @return unknown
	 */
	public function executeAsyncHistoryRecord(sfRequest $request) {
		$this->model = $this->getModelById($request->getParameter('id'));
		return $this->model->showPartialOn($this);
	}
	
	/**
	 * Shows the form to create an object
	 *
	 * @param sfWebRequest $request
	 */
  	public function executeNew(sfWebRequest $request) {
		$this->setTitle($this->newPageTitle());
  		$this->form = $this->newForm();
  		 
  	}

  	/**
  	 * This action is performed when the object form is submitted in "new" mode.
  	 *
  	 * @param sfWebRequest $request
  	 * @return unknown
  	 */
	public function executeCreate(sfWebRequest $request) {
    	if(!$request->isMethod('post')) {
    		$this->errorMessage(self::MSG_ERROR_INVALID_OPERATION);
    		return $this->redirect($this->indexUrl());
    	}

    	$this->form = $this->newForm();
		
    	$this->processForm($request, $this->form);
		$this->setTemplate('new');
  	}
  	
  	
	/**
	 * Shows an object in the form in order to edit it.
	 *
	 * @param sfWebRequest $request
	 * @return unknown
	 */
	public function executeEdit(sfWebRequest $request) {
		$this->setTitle($this->editPageTitle());
		$id = $request->getParameter('id');
		$model = $this->getModelById($id);
		if (!$model) {
			//FIXME Usar excepciones
			$this->errorMessage(self::MSG_ERROR_OBJECT_NOT_FOUND, $id);
			return $this->redirect($this->indexUrl());
		}
		
		$this->form = $this->newForm($model);;
	}

	/**
	 * This action is called when the form is submitted in "edit" mode.
	 *
	 * @param sfWebRequest $request
	 * @return unknown
	 */
	public function executeUpdate(sfWebRequest $request) {
		$id = $request->getParameter('id');
		if(!($request->isMethod('post') || $request->isMethod('put'))) {
			$this->errorMessage(self::MSG_ERROR_INVALID_OPERATION);
			$this->redirect($this->showUrl());
	    }
	    $model = $this->getModelById($id);
	    if (!$model) {
			$this->errorMessage(self::MSG_ERROR_OBJECT_NOT_FOUND, $id);
			return $this->redirect($this->indexUrl());
		}
	    $this->form = $this->newForm($model);
	
	    $this->processForm($request, $this->form);
	
	    $this->setTemplate('edit');
	}

	/**
	 * Deletes an object
	 *
	 * @param sfWebRequest $request
	 */
	public function executeDelete(sfWebRequest $request) {
		try {
			$request->checkCSRFProtection();
			$id = $request->getParameter('id');
		    $model = $this->getModelById($id);
	    	if (!$model) {
	    		throw new ObjectNotFoundException($id);
	    	}
			//TODO Setear el usuario actual	    	
		    $model->delete();
	    	$model->save();
			$this->message($this->msgItemSuccessfullyDeleted());
	    	$this->redirect($this->indexUrl());
		} catch (ObjectNotFoundException $e) {
			$this->errorMessage(self::MSG_ERROR_OBJECT_NOT_FOUND);
			$this->redirect($this->globalErrorUrl());
		}
	}

	/**
	 * Executes a batch action specified in the request parameters.
	 *
	 * @param sfWebRequest $request
	 */
	public function executeBatchAction(sfWebRequest $request) {
 		$this->executeBatchDelete($request);
	}
  
	/**
	 * Deletes a set of objects.
	 *
	 * @param sfWebRequest $request
	 */
	public function executeBatchDelete(sfWebRequest $request) {
		try {
			$ids = $request->getParameter("ids");
	 		$deleteList = split(",", $ids);
			$success = true;
	 		foreach ($deleteList as $id) {
				if ($id) {
					$model = $this->getModelById($id);
					if (!$model) {
						throw new ObjectNotFoundException();
					}
					//TODO Setear el usuario actual
					$model->delete();
					$model->save();
				}
	 		}
 			$this->message($this->msgItemsSuccessfullyDeleted());
	 		$this->redirect($this->indexUrl());
		} catch (ObjectNotFoundException $e) {
			$this->errorMessage(self::MSG_ERROR_OBJECT_NOT_FOUND);
			$this->logMessage("Error deleting objects: ".$e->getMessage(), 'ERR');
			$this->redirect($this->globalErrorUrl());
		}
	}  
  
	/**
	 * Process the form. If the for is not valid, then redirect to the form template.
	 *
	 * @param sfWebRequest $request
	 * @param sfForm $form
	 */
	protected function processForm(sfWebRequest $request, sfForm $form) {
		try {
			$form->bind($request->getParameter($form->getName()));
			if ($form->isValid()) {
				$model = $form->getObject();
				/*if ($this->isTraceable()) {
					$user = SecuritySystem::getInstance()->findUser($this->getUser()->getAttribute("username"));
					$model->setCurrentUser($user);
				}*/
				
				if ($model->isNew()) {
					$root = $this->addToRoot($model);
				}
				if ($model->isNew()) {
					$this->message($this->msgItemSuccessfullyAdded());
				} else {
					$this->message($this->msgItemSuccessfullyUpdated());
				}
				$model = $form->save();
				if ($this->isSaveRoot()) {
					if (!$root) {
						$root = $this->getRoot();	
					}
					$root->save();
				}
			    $this->redirect($this->showUrl().'?id='.$model->getId());
		    } else {
				if ($form->getObject()->isNew()) {
					$this->setTitle($this->newPageTitle());
				} else {
					$this->setTitle($this->editPageTitle());
				}
		    	
		    } 
		    
		} catch (ContextualObjectNotFoundException $e) {
			$this->errorMessage(self::MSG_ERROR_OBJECT_NOT_FOUND);
			$this->redirect($this->globalErrorUrl());
		}
	}

	/**
	 * Returns the action that will be shown when a global error ocurr.
	 * 
	 *
	 * @return string
	 */
	protected function globalErrorUrl() {
		return $this->indexUrl();
	}
	protected function setTitle($text) {
		$this->getContext()->set('subsystem_title', $this->subsystemTitle());
		$this->getContext()->set("page_title", $text);
	}
	
	

	/**
	 * @see sfActions
	 *
	 */
	public function preExecute() {
		$this->setGlobalVariables();		
	}
	
	/**
	 * Set global variables that every templates of the CRUD can access.
	 *
	 */
	protected function setGlobalVariables() {
		$this->entityName = $this->entityName();
		$this->entityNamePlural = $this->entityNamePlural();
	}

	/**
	 * Says whether the filter form will be displyed.
	 *
	 * @return boolean
	 */
	protected function showSearch() { 
		return true;
	}
	
	/**
	 * Says whether it's necessary to save the root object. By default it's not.
	 * @return boolean
	 */
	protected function isSaveRoot() {
		return false;
	}

	/**
	 * Returns the object that holds or register the model.
	 * FIXME Make it abstract, and use it in addToRoot.
	 * @return Object
	 */
	protected function getRoot() {
		return null;
	}

	/**
	 * Says whether the entity managed is traceable. It's used when the entity is saved.
	 * If it's traceable, the user that have made the modification is setted.
	 * By default returns true. For a non traceable entity CRUD, it must be overriden to return false.
	 *
	 * @return boolean
	 */
	public function isTraceable() {
		return true;
	}

}

?>