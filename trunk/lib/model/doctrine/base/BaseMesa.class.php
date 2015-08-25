<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseMesa extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('mesa');
        $this->hasColumn('numero', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('id_estado', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('id_mozo', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('id_pedido', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('fecha_abierta', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('fecha_cerrada', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('id_restaurant', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));

        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasOne('EstadoMesa as Estado', array(
             'local' => 'id_estado',
             'foreign' => 'id'));

        $this->hasOne('Mozo', array(
             'local' => 'id_mozo',
             'foreign' => 'id'));

        $this->hasOne('Pedido', array(
             'local' => 'id_pedido',
             'foreign' => 'id'));

        $this->hasMany('Factura', array(
             'local' => 'id',
             'foreign' => 'id_mesa'));

        $this->hasOne('Restaurant', array(
             'local' => 'id_restaurant',
             'foreign' => 'id'));
    }
}