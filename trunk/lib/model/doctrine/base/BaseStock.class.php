<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseStock extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('stock');
        $this->hasColumn('codigo', 'string', 30, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '30',
             ));
        $this->hasColumn('cantidad', 'integer', null, array(
             'type' => 'integer',
             ));

        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

}