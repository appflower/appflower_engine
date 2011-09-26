<?php

/**
 * afWidgetHelpSettings filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 */
abstract class BaseafWidgetHelpSettingsFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'                => new sfWidgetFormFilterInput(),
      'widget_help_is_enabled' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'popup_help_is_enabled'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'help_type'              => new sfWidgetFormFilterInput(),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'user_id'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'widget_help_is_enabled' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'popup_help_is_enabled'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'help_type'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('af_widget_help_settings_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'afWidgetHelpSettings';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'user_id'                => 'Number',
      'widget_help_is_enabled' => 'Boolean',
      'popup_help_is_enabled'  => 'Boolean',
      'help_type'              => 'Number',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
    );
  }
}
