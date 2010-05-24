<?php
/**
 * 
 * @author Prakash Paudel
 * Reconfigure the form fields
 * 
 * The class helps to tweek the form fields globally according 
 * to certain conditions
 */
class ReConfigureFields{	
	static $REQUIRED_VALIDATOR = "immValidatorRequired";
	
	public function __construct($field=array()){
		$this->field = $field;
		$this->reConfigureColorField();
		$this->markLabelMandatory();		
	}
	private function markLabelMandatory(){
		if(!isset($this->field['attributes']))return;
		if(!isset($this->field['validators'])) return;
		$validators = $this->field['validators'];
		foreach($validators as $name=>$arr){
			if($name == self::$REQUIRED_VALIDATOR){
				$this->addAsterisk();
				break;
			}
			$this->addAsterisk();				
			if(isset($arr['params'])){
				$params = $arr['params'];				
				foreach($params as $k=>$v){
					if($k == "required" && $v === "false"){
						$this->removeAsterisk();
					}
				}
			}			
		}			
	}
	private function reConfigureColorField(){
		if(!isset($this->field['attributes']))return;
		$attr = $this->field['attributes'];
		if(isset($attr['plugin']) && $attr['plugin'] == "colorfield"){
			$this->field['attributes']['label'] = $attr['label']." (hex code)";
			$this->field['attributes']['help'] = (isset($attr['help'])?$attr['help']."<br>":'')."Pick the color or input the valid color code in hex";
		}
	}
	private function removeAsterisk(){		
		$attr = $this->field['attributes'];
		if(preg_match("/\*$/",trim($attr['label']))){
			$this->field['attributes']['label'] = preg_replace("/\*$/","",trim($attr['label']));			
		}		
	}
	private function addAsterisk(){
		$attr = $this->field['attributes'];
		if(!preg_match("/\*$/",trim($attr['label']))){
			$this->field['attributes']['label'] = $attr['label']."*";
		}		
	}	
	public function getField(){		
		return $this->field;
	}
}
?>