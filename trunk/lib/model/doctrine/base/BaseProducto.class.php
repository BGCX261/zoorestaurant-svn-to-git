<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseProducto extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('producto');
        $this->hasColumn('codigo', 'string', 30, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '30',
             ));
        $this->hasColumn('nombre', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('descripcion', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('precio', 'float', null, array(
             'type' => 'float',
             'notnull' => true,
             ));
        $this->hasColumn('id_restaurant', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('type', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('capacidad', 'string', 10, array(
             'type' => 'string',
             'length' => '10',
             ));

        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');

        $this->setSubClasses(array(
             'Entrada' => 
             array(
              'type' => 'ENT',
             ),
             'PlatoPrincipal' => 
             array(
              'type' => 'PPAL',
             ),
             'ParrillaLibre' => 
             array(
              'type' => 'PLIB',
             ),
             'Bebida' => 
             array(
              'type' => 'BEB',
             ),
             'Ensalada' => 
             array(
              'type' => 'ENS',
             ),
             'Postre' => 
             array(
              'type' => 'POS',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
    $this->hasMany('Detalle', array(
             'local' => 'id',
             'foreign' => 'id_producto'));

        $this->hasOne('Restaurant', array(
             'local' => 'id_restaurant',
             'foreign' => 'id'));
    }
}