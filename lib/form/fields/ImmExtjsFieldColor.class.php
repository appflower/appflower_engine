<?php
/**
 * extJs Form Field Input
 */
class ImmExtjsFieldColor extends ImmExtjsField
{
	public $immExtjs;
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->immExtjs = ImmExtjs::getInstance();
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
			$attributes['value'] = trim(str_replace("#","",$attributes['value']));
			$newVal = $attributes['value'];
			if(strlen($attributes['value']) == 3){
				$tmp = $attributes['value'];
				$newVal = '';
				for($i=0;$i<3;$i++){
					$newVal .= $tmp{$i}.$tmp{$i}."";
				}				
			}
			$attributes['value'] = "#".$newVal;
		}else{
			$attributes['value'] = "#000000";
		}
		
		if(isset($attributes['plugin'])){			
			
			unset($attributes['plugin']);
		}
		if(isset($attributes['value'])&&$attributes['value']!=null)
		{
			$this->attributes['value']=$attributes['value'];
			
			unset($attributes['value']);
		}
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>