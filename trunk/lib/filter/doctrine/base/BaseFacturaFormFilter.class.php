<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Factura filter form base class.
 *
 * @package    filters
 * @subpackage Factura *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseFacturaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'numero'  => new sfWidgetFormFilterInput(),
      'propina' => new sfWidgetFormFilterInput(),
      'fecha'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'id_mesa' => new sfWidgetFormDoctrineChoice(array('model' => 'Mesa', 'add_empty' => true)),
      'id_caja' => new sfWidgetFormDoctrineChoice(array('model' => 'Caja', 'add_empty' => true)),
      'total'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'numero'  => new sfValidatorPass(array('required' => false)),
      'propina' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'fecha'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'id_mesa' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Mesa', 'column' => 'id')),
      'id_caja' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Caja', 'column' => 'id')),
      'total'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('factura_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Factura';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'numero'  => 'Text',
      'propina' => 'Number',
      'fecha'   => 'Date',
      'id_mesa' => 'ForeignKey',
      'id_caja' => 'ForeignKey',
      'total'   => 'Number',
    );
  }
}