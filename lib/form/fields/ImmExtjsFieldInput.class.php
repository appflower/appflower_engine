<?php
/**
 * extJs Form Field Input
 */
class ImmExtjsFieldInput extends ImmExtjsField
{
	public $immExtjs;
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->immExtjs = ImmExtjs::getInstance();
		$this->attributes['xtype']='textfield';
		
		$this->attributes['width']='250';
		/*$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'form/colorfield/color-field.js'),'css'=>array($this->immExtjs->getExamplesDir().'form/colorfield/color-field.css')));									
					$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'form/adv-color-picker/code/Color.js'),'css'=>array($this->immExtjs->getExamplesDir().'form/adv-color-picker/code/color-picker.css')));
					$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'form/adv-color-picker/code/ColorPickerPanel.js')));
					$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'form/adv-color-picker/code/ColorPickerWin.js')));
		*/if(isset($attributes['plugin'])){			
			if($attributes['plugin'] == 'colorfield'){
				$this->immExtjs->setAddons(array(
					'js' => array(
						//$this->immExtjs->getExamplesDir().'form/colorpicker-ext-3.0.0/colorpicker.js',
						//$this->immExtjs->getExamplesDir().'form/colorpicker-ext-3.0.0/colorpickerfield.js'
						
						$this->immExtjs->getExamplesDir().'form/ColorPicker/sources/ColorMenu.js',
						$this->immExtjs->getExamplesDir().'form/ColorPicker/sources/ColorPicker.js',
						$this->immExtjs->getExamplesDir().'form/ColorPicker/sources/ColorPickerField.js'
						
					),
					'css'=>array(
						$this->immExtjs->getExamplesDir().'form/ColorPicker/resources/css/colorpicker.css'
					)
				));									
				$this->attributes['xtype'] = "colorpickerfield";
				$this->attributes['editMode'] = "all";
				if(isset($attributes['value'])&&$attributes['value']!=null){					
					$attributes['value'] = str_replace("#","",$attributes['value']);
				}
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