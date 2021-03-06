<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * EstadoMesa filter form base class.
 *
 * @package    filters
 * @subpackage EstadoMesa *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseEstadoMesaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'codigo'        => new sfWidgetFormFilterInput(),
      'nombre'        => new sfWidgetFormFilterInput(),
      'id_restaurant' => new sfWidgetFormDoctrineChoice(array('model' => 'Restaurant', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'codigo'        => new sfValidatorPass(array('required' => false)),
      'nombre'        => new sfValidatorPass(array('required' => false)),
      'id_restaurant' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Restaurant', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('estado_mesa_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'EstadoMesa';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'codigo'        => 'Text',
      'nombre'        => 'Text',
      'id_restaurant' => 'ForeignKey',
    );
  }
}