<?php

/**
 * administracion_mesas actions.
 *
 * @package    restaurant
 * @subpackage administracion_mesas
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class administracion_mesasActions extends CrudActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->modelSingular = "mesa";
  }
  
   protected function msgErrorBatchDelete() {
		return "Ocurrio un error cuando se borraron las mesas";
		
	}	

	protected function msgItemSuccessfullyDeleted() {
		return "Mesa borrada con éxito";
	}

	protected function msgItemsSuccessfullyDeleted() {
		return "Mesas borradas con éxito";
	}
	
	protected function msgItemSuccessfullyUpdated() {
		return "Mesa actualizada exitosamente";
	}
	
	protected function msgItemSuccessfullyAdded() {
		return "Mesa agregada con éxito";
	}
		
	
	protected function indexPageTitle() {
		return "Administración Mesas";
	}
	
	protected function showPageTitle() {
		return "Mesa";
	}
	
	protected function newPageTitle() {
		return "Crear Mesa";
	}

	protected function editPageTitle() {
		return "Editar Mesa";
	}
	
	protected function modelList() {
		return Restaurant::getInstance()->mesas();
	}
	
 	protected function modelName() {
  		return "Mesa";
  	}

  	protected function getModelById($id) {
		return Restaurant::getInstance()->mesas()->findByProperty('id', $id);  		
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
		return new MesaForm($model);  		
  	}  	

	protected function addToRoot($model) {
		Restaurant::getInstance()->agregarMesa($model);		
	}
	
	/**
	 * @see CurdActions
	 */
	public function entityName() {
		return "mesa";
	}
	
	/**
	 * @see CrudActions
	 */
	public function entityNamePlural() {
		return "mesas";
	}
	
	public function executeAsyncMesas() {
		$mesas = Restaurant::getInstance()->mesas();
		$json = $mesas->getJson();
		return $this->renderText($json->getString());
	}
	
	public function executeAsyncRecordFinderMozos() {
		try {
				return $this->renderText(AsyncSearch::getInstance()->mozos());
			} catch (Exception $e) {
				$this->response->setStatusCode(500, 'Server Error');
				$this->logMessage($e->getMessage(), 'err');
				return $this->renderText('Error '.$e->getMessage());
			}
	
	}
	
	public function executeAsyncRecordFinderProductos() {
		try {
				return $this->renderText(AsyncSearch::getInstance()->productos());
			} catch (Exception $e) {
				$this->response->setStatusCode(500, 'Server Error');
				$this->logMessage($e->getMessage(), 'err');
				return $this->renderText('Error '.$e->getMessage());
			}
	
	}
	
	public function executeAsyncAbrirMesa(sfWebRequest $request) {
		$mesa = $request->getParameter('mesa');
		$mesaSerializada = json_decode($mesa);
		$mozoId = $mesaSerializada->mozo;
		$mozo = Restaurant::getInstance()->buscarMozoPorId($mozoId);
		$mesaId = $mesaSerializada->id;
		$mesa = Restaurant::getInstance()->buscarMesaPorId($mesaId);
		$mesa->abrir($mozo);
		$mesa->save();
		return $this->renderText("Mesa abierta con éxito!");
		
	}
	
	public function executeAsyncModificarMozo(sfWebRequest $request) {
		$mesa = $request->getParameter('mesa');
		$mesaSerializada = json_decode($mesa);
		$mozoId = $mesaSerializada->mozo;
		$mozo = Restaurant::getInstance()->buscarMozoPorId($mozoId);
		$mesaId = $mesaSerializada->id;
		$mesa = Restaurant::getInstance()->buscarMesaPorId($mesaId);
		$mesa->setMozo($mozo);
		$mesa->save();
		return $this->renderText($mesa->getMozo()->getNombreCompleto());
		
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
				if($this->validarNumeroMesa($form)) {
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
				}    
		    } else {
				if ($form->getObject()->isNew()) {
					$this->setTitle($this->newPageTitle());
				} else {
					$this->setTitle($this->editPageTitle());
				}
		    	
		    } 
		} catch (NumeroMesaException $en) {
			$this->errorMessage($en->getMessage());
		} catch (ContextualObjectNotFoundException $e) {
			$this->errorMessage(self::MSG_ERROR_OBJECT_NOT_FOUND);
			$this->redirect($this->globalErrorUrl());
		}
	}
	
	private function validarNumeroMesa($form) {
		
		$mesaNumero = $form->getFormFieldSchema ()->offsetGet ( 'numero' )->getValue ();
		$mesa = Restaurant::getInstance()->buscarMesaPorNumero($mesaNumero);
		if($mesa != null) {
			throw new NumeroMesaException ( 'La mesa '.$mesaNumero.' ya se encuentra creada!' );
		}
		return true;
	}
	
	public function executeCargarProducto(sfWebRequest $request) {
		$idMesa = $request->getParameter('idMesa');
		$mesa = Restaurant::getInstance()->buscarMesaPorId($idMesa);
		if($mesa->getEstado()->equals(EstadoMesa::abierta())) {
			$numeroMesa = $mesa->getNumero();
			$nombreMozo = $mesa->getMozo()->getNombreCompleto();
			$numeroPedido = $mesa->getPedido()->getNumero();
			$fechaAbierta = DateUtil::formatToBarDateWithHour($mesa->getFechaAbierta());
			$this->numeroMesa = $numeroMesa;
			$this->nombreMozo = $nombreMozo;
			$this->fechaAbierta = $fechaAbierta;
			$this->numeroPedido = $numeroPedido;
		} else {
			$this->redirect('administracion_mesas/show?id='.$idMesa);
		}
	}
	
	public function executeAsyncCargarDetalle(sfWebRequest $request) {
		$idProducto = $request->getParameter('idProducto');
		$numeroPedido = $request->getParameter('numeroPedido');
		$cantidad = $request->getParameter('cantidad');
		$pedido = Restaurant::getInstance()->buscarPedidoPorNumero($numeroPedido);
		if($pedido == null) {
			//titat excep
		} else {
			$producto = Restaurant::getInstance()->buscarProductoPorId($idProducto);
			if($producto == null) {
				//tirar excep
			} else {
				$detalle = $pedido->buscarProducto($producto);
				if($detalle != null){
					$cantidadAux = $detalle->getCantidad() + $cantidad;
					$detalle->setCantidad($cantidadAux);
					$detalle->save();
				} else {
					$detalle = new Detalle();
					$detalle->setProducto($producto);
					$detalle->setCantidad($cantidad);
					//$detalle->save();
					$pedido->agregarDetalle($detalle);
					$pedido->save();
				}
				$json = $pedido->getJson();
				return $this->renderText($json->getString());
			}
		}	
	}
	
	public function executeAsyncPedido(sfWebRequest $request) {
		$numeroPedido = $request->getParameter('numeroPedido');
		$pedido = Restaurant::getInstance()->buscarPedidoPorNumero($numeroPedido);
		if($pedido == null) {
			//titat excep
		} else {
			$json = $pedido->getJson();
			return $this->renderText($json->getString());		
		}	
	}
	
	public function executeAsyncEliminarDetalle(sfWebRequest $request) {
		$numeroPedido = $request->getParameter('numeroPedido');
		$idProducto = $request->getParameter('idProducto');
		$pedido = Restaurant::getInstance()->buscarPedidoPorNumero($numeroPedido);
		if($pedido == null) {
			//titat excep
		} else {
			$producto = Restaurant::getInstance()->buscarProductoPorId($idProducto);
			if($producto == null) {
				//tirar excep
			} else {
				$detalle = $pedido->buscarProducto($producto);
				if($detalle != null){
					$pedido->eliminarDetalle($detalle);
					
				}
				return $this->renderText($pedido->calcularTotal());	
			}	
		}	
	}
	
	public function executeAsyncCambiarCantidadDetalle(sfWebRequest $request) {
		$numeroPedido = $request->getParameter('numeroPedido');
		$idProducto = $request->getParameter('idProducto');
		$cantidad = $request->getParameter('cantidad');
		$pedido = Restaurant::getInstance()->buscarPedidoPorNumero($numeroPedido);
		if($pedido == null) {
			//titat excep
		} else {
			$producto = Restaurant::getInstance()->buscarProductoPorId($idProducto);
			if($producto == null) {
				//tirar excep
			} else {
				$detalle = $pedido->buscarProducto($producto);
				if($detalle != null){
					$detalle->setCantidad($cantidad);
					$detalle->save();
				}
				$json = $pedido->getJson();
				return $this->renderText($json->getString());		
			}	
		}	
	}
  
}