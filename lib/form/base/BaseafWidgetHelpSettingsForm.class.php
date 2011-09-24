<?php

/**
 * afWidgetHelpSettings form base class.
 *
 * @method afWidgetHelpSettings getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
abstract class BaseafWidgetHelpSettingsForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'user_id'                => new sfWidgetFormInputText(),
      'widget_help_is_enabled' => new sfWidgetFormInputCheckbox(),
      'popup_help_is_enabled'  => new sfWidgetFormInputCheckbox(),
      'help_type'              => new sfWidgetFormInputText(),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorPropelChoice(array('model' => 'afWidgetHelpSettings', 'column' => 'id', 'required' => false)),
      'user_id'                => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'widget_help_is_enabled' => new sfValidatorBoolean(array('required' => false)),
      'popup_help_is_enabled'  => new sfValidatorBoolean(array('required' => false)),
      'help_type'              => new sfValidatorInteger(array('min' => -128, 'max' => 127, 'required' => false)),
      'created_at'             => new sfValidatorDateTime(array('required' => false)),
      'updated_at'             => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('af_widget_help_settings[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'afWidgetHelpSettings';
  }


}
