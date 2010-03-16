<?php

class Collection {
	
	private $collection = array();
	private $selected;
	
	function Collection($result,$getter,$selected = 0) {
		
		$this->collection = array();
		$this->selected = $selected;

		if(is_array($result) && empty($result)) {
			return true;
		}
		
		if(!is_array($result)) {
			throw new XmlParserException("First argument of Collection() should be an array");
		} else if(!method_exists($result[0],$getter)) {
			throw new XmlParserException("The method ".$getter." doesn't exist in result objects");
		}
			
		foreach($result as $object) {
			$this->collection[$object->getId()] = call_user_func(array($object,$getter));		
		}
	}
	
	public function getArray() {
		return $this->collection;
	}
	
	public function getSelected() {
		return $this->selected;
	}
	
	
}

?>
