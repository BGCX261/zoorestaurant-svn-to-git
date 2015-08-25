<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Pedido filter form base class.
 *
 * @package    filters
 * @subpackage Pedido *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BasePedidoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'numero'    => new sfWidgetFormFilterInput(),
      'fecha'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'id_estado' => new sfWidgetFormDoctrineChoice(array('model' => 'EstadoPedido', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'numero'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fecha'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'id_estado' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'EstadoPedido', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('pedido_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Pedido';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'numero'    => 'Number',
      'fecha'     => 'Date',
      'id_estado' => 'ForeignKey',
    );
  }
}