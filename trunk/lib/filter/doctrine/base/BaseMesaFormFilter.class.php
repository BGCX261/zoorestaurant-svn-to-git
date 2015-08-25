<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Mesa filter form base class.
 *
 * @package    filters
 * @subpackage Mesa *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMesaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'numero'        => new sfWidgetFormFilterInput(),
      'id_estado'     => new sfWidgetFormDoctrineChoice(array('model' => 'EstadoMesa', 'add_empty' => true)),
      'id_mozo'       => new sfWidgetFormDoctrineChoice(array('model' => 'Mozo', 'add_empty' => true)),
      'id_pedido'     => new sfWidgetFormDoctrineChoice(array('model' => 'Pedido', 'add_empty' => true)),
      'fecha_abierta' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'fecha_cerrada' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'id_restaurant' => new sfWidgetFormDoctrineChoice(array('model' => 'Restaurant', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'numero'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'id_estado'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'EstadoMesa', 'column' => 'id')),
      'id_mozo'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Mozo', 'column' => 'id')),
      'id_pedido'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Pedido', 'column' => 'id')),
      'fecha_abierta' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'fecha_cerrada' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'id_restaurant' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Restaurant', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('mesa_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Mesa';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'numero'        => 'Number',
      'id_estado'     => 'ForeignKey',
      'id_mozo'       => 'ForeignKey',
      'id_pedido'     => 'ForeignKey',
      'fecha_abierta' => 'Date',
      'fecha_cerrada' => 'Date',
      'id_restaurant' => 'ForeignKey',
    );
  }
}