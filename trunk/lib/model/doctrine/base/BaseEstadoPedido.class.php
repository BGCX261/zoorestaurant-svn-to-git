<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseEstadoPedido extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('estado_pedido');
        $this->hasColumn('codigo', 'string', 30, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '30',
             ));
        $this->hasColumn('nombre', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             'notnull' => true,
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
    $this->hasMany('Pedido', array(
             'local' => 'id',
             'foreign' => 'id_estado'));

        $this->hasOne('Restaurant', array(
             'local' => 'id_restaurant',
             'foreign' => 'id'));
    }
}