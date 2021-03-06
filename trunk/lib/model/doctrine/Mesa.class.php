<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Mesa extends BaseMesa implements JsonSerializable
{
	
	public function construct() {
		
		if ($this->isNew()) {
			try {
				parent::construct();
				$this->setEstado(EstadoMesa::cerrada());
			} catch (Exception $e) {
				 /**
                 * This try/catch clause is necessary to prevent the Doctrine data loader command from
                 * failing.
                 *
                 */
			}
			
		}
	}
	
	/**
	 * abre la mesa y setea el mozo
	 * @param Mozo $mozo
	 */
	public function abrir($mozo) {
		//$this->getPedido()->vaciar();
		if( !$this->getEstado()->equals(EstadoMesa::abierta()) ) {
			$this->setFechaAbierta(DateUtil::formatAsTimeStamp(time()));
			$this->setearMozoYEstado($mozo, EstadoMesa::abierta());
			$pedido = new Pedido();
			//$pedido->setNumero(21);//clase aca
			//$pedido->setFecha($this->getFechaAbierta());
			$restaurant = Restaurant::getInstance()->agregarPedido($pedido);
			$pedido->save();
			$this->setPedido($pedido);
			return true;
		}
		return false;
	}
	
	public function cerrar() {
		if( !$this->getEstado()->equals(EstadoMesa::cerrada()) ) {
			$this->setearMozoYEstado("", EstadoMesa::cerrada());
			return true;
		}
		return false;
	}
	
	public function facturar() {
		$total = 0;
		//crear factura y devolverla?
		foreach( $this->getPedido()->detalle() as $detalle ) {
			$total = $total + ($detalle->getProducto()->getPrecio() * $detalle->getCantidad());
		}
		return $total;
	}
	
	public function reabrir($mozo) {
		if( !$this->getEstado()->equals(EstadoMesa::abierta()) ) {
			$this->setearMozoYEstado($mozo, EstadoMesa::abierta());
			return true;
		}
		return false;
	}
	
	private function setearMozoYEstado($mozo, $estado) {
		$this->setMozo($mozo);
		$this->setEstado($estado);
	}
	
	public function getJson($type = '') {
		$json = new JsonObject();
		$json
			->withValue('id', $this->getId())
			->withValue('numero', $this->getNumero())
			->withValue('fechaAbierta', $this->getFechaAbierta())
			->withValue('mozo', $this->getMozo()->getNombreCompleto())
			->withValue('estado', $this->getEstado()->getNombre())
			->withObject('pedido', $this->getPedido());
			
		return $json;
		
	}
	
	
}