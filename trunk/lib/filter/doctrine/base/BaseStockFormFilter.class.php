<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Stock filter form base class.
 *
 * @package    filters
 * @subpackage Stock *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseStockFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'codigo'   => new sfWidgetFormFilterInput(),
      'cantidad' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'codigo'   => new sfValidatorPass(array('required' => false)),
      'cantidad' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('stock_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Stock';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'codigo'   => 'Text',
      'cantidad' => 'Number',
    );
  }
}