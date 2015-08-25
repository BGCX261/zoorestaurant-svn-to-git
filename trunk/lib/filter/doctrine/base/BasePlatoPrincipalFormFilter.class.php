<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * PlatoPrincipal filter form base class.
 *
 * @package    filters
 * @subpackage PlatoPrincipal *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BasePlatoPrincipalFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'codigo'        => new sfWidgetFormFilterInput(),
      'nombre'        => new sfWidgetFormFilterInput(),
      'descripcion'   => new sfWidgetFormFilterInput(),
      'precio'        => new sfWidgetFormFilterInput(),
      'id_restaurant' => new sfWidgetFormDoctrineChoice(array('model' => 'Restaurant', 'add_empty' => true)),
      'type'          => new sfWidgetFormFilterInput(),
      'capacidad'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'codigo'        => new sfValidatorPass(array('required' => false)),
      'nombre'        => new sfValidatorPass(array('required' => false)),
      'descripcion'   => new sfValidatorPass(array('required' => false)),
      'precio'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'id_restaurant' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Restaurant', 'column' => 'id')),
      'type'          => new sfValidatorPass(array('required' => false)),
      'capacidad'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('plato_principal_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PlatoPrincipal';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'codigo'        => 'Text',
      'nombre'        => 'Text',
      'descripcion'   => 'Text',
      'precio'        => 'Number',
      'id_restaurant' => 'ForeignKey',
      'type'          => 'Text',
      'capacidad'     => 'Text',
    );
  }
}