<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Detalle filter form base class.
 *
 * @package    filters
 * @subpackage Detalle *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseDetalleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id_producto' => new sfWidgetFormDoctrineChoice(array('model' => 'Producto', 'add_empty' => true)),
      'cantidad'    => new sfWidgetFormFilterInput(),
      'id_pedido'   => new sfWidgetFormDoctrineChoice(array('model' => 'Pedido', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id_producto' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Producto', 'column' => 'id')),
      'cantidad'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'id_pedido'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Pedido', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('detalle_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Detalle';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'id_producto' => 'ForeignKey',
      'cantidad'    => 'Number',
      'id_pedido'   => 'ForeignKey',
    );
  }
}