<?php
/**
 * extJs Form Field Input
 */
class afExtjsFieldInput extends afExtjsField
{
	public $afExtjs;
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->afExtjs = afExtjs::getInstance();
		$this->attributes['xtype']='textfield';
		
		$this->attributes['width']='250';
		$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getExamplesDir().'form/colorfield/color-field.js'),'css'=>array($this->afExtjs->getExamplesDir().'form/colorfield/color-field.css')));									
					$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getExamplesDir().'form/adv-color-picker/code/Color.js'),'css'=>array($this->afExtjs->getExamplesDir().'form/adv-color-picker/code/color-picker.css')));
					$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getExamplesDir().'form/adv-color-picker/code/ColorPickerPanel.js')));
					$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getExamplesDir().'form/adv-color-picker/code/ColorPickerWin.js')));
		if(isset($attributes['plugin'])){			
			if($attributes['plugin'] == 'colorfield'){
					
					$this->attributes['xtype'] = "colorfield";
			}
			unset($attributes['plugin']);
		}
		if(isset($attributes['value'])&&$attributes['value']!=null)
		{
			$this->attributes['value']=is_numeric($attributes['value'])?($attributes['value']." "):$attributes['value'];
			
			unset($attributes['value']);
		}
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>