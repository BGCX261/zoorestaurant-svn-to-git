<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Caja filter form base class.
 *
 * @package    filters
 * @subpackage Caja *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCajaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id_factura'    => new sfWidgetFormFilterInput(),
      'fecha_abierta' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'fecha_cerrada' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'id_estado'     => new sfWidgetFormDoctrineChoice(array('model' => 'EstadoCaja', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id_factura'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fecha_abierta' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'fecha_cerrada' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'id_estado'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'EstadoCaja', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('caja_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Caja';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'id_factura'    => 'Number',
      'fecha_abierta' => 'Date',
      'fecha_cerrada' => 'Date',
      'id_estado'     => 'ForeignKey',
    );
  }
}