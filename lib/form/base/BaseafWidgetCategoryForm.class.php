<?php

/**
 * afWidgetCategory form base class.
 *
 * @method afWidgetCategory getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
abstract class BaseafWidgetCategoryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'     => new sfWidgetFormInputHidden(),
      'module' => new sfWidgetFormInputText(),
      'name'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'     => new sfValidatorPropelChoice(array('model' => 'afWidgetCategory', 'column' => 'id', 'required' => false)),
      'module' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'name'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('af_widget_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'afWidgetCategory';
  }


}
