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
		$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'form/colorfield/color-field.js'),'css'=>array($this->immExtjs->getExamplesDir().'form/colorfield/color-field.css')));									
					$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'form/adv-color-picker/code/Color.js'),'css'=>array($this->immExtjs->getExamplesDir().'form/adv-color-picker/code/color-picker.css')));
					$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'form/adv-color-picker/code/ColorPickerPanel.js')));
					$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'form/adv-color-picker/code/ColorPickerWin.js')));
		if(isset($attributes['plugin'])){			
			if($attributes['plugin'] == 'colorfield'){
					
					$this->attributes['xtype'] = "colorfield";
			}
			unset($attributes['plugin']);
		}
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>