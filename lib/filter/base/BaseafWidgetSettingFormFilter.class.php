<?php

/**
 * afWidgetSetting filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
abstract class BaseafWidgetSettingFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'    => new sfWidgetFormFilterInput(),
      'user'    => new sfWidgetFormFilterInput(),
      'setting' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'    => new sfValidatorPass(array('required' => false)),
      'user'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'setting' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('af_widget_setting_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'afWidgetSetting';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'name'    => 'Text',
      'user'    => 'Number',
      'setting' => 'Text',
    );
  }
}
