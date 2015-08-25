<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EstadoMesa extends BaseEstadoMesa
{
	const ABIERTA = 'AB';
	const CERRADA = 'CE';
	
	/**
	 * busca por codigo y devuelve el estado "abierta"
	 *
	 * @return string
	 */
	public static function abierta() {
		return Restaurant::getInstance()->estadosMesa()->findByProperty('codigo', self::ABIERTA);
	}
	
	/**
	 * busca por codigo y devuelve el estado "cerrada"
	 *
	 * @return string
	 */
	public static function cerrada() {
		return Restaurant::getInstance()->estadosMesa()->findByProperty('codigo', self::CERRADA);
	}
	
	public function equals($estado) {
		if (get_class($this) == get_class($estado)) {
			return $this->getCodigo() == $estado->getCodigo();
		}
		return false;
	}

	
}