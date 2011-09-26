<?php

/**
 * afWidgetSelector form base class.
 *
 * @method afWidgetSelector getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
abstract class BaseafWidgetSelectorForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'url'         => new sfWidgetFormInputText(),
      'params'      => new sfWidgetFormInputText(),
      'category_id' => new sfWidgetFormPropelChoice(array('model' => 'afWidgetCategory', 'add_empty' => true)),
      'permission'  => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorPropelChoice(array('model' => 'afWidgetSelector', 'column' => 'id', 'required' => false)),
      'url'         => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'params'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'category_id' => new sfValidatorPropelChoice(array('model' => 'afWidgetCategory', 'column' => 'id', 'required' => false)),
      'permission'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('af_widget_selector[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'afWidgetSelector';
  }


}
