<?php

class nodeListIterator implements Iterator {
    
	private $position = 0;
    private $list;
	private $mode;
	private $attribute;

    public function __construct($list = null,$mode = 1) {
		
		if(is_object($list) && property_exists($list,"length") && $list->length == 0) {
			return null;
		}
		
		try {
			if($list !== null && !$this->check($list)) {
				throw new XmlValidatorException("Bad input parameter, list argument is missing or is not a valid DOMNodeList object!");
			} 
		}
		catch(Exception $e) {
			throw $e;
		}
		
		$this->position = 0;
		
		if($list !== null) {
			$this->list = $list;
		}
		
		if($mode !== NODES && $mode !== VALUES) {
			$this->mode = ATTRIBUTES;
			$this->attribute = $mode; 
		} else {
			$this->mode = $mode;
		}
		
    }

    public function rewind() {

        $this->position = 0;

    }


    public function current() {
	
		$this->check();
		
		$item = $this->list->item($this->position);
	
		if($this->mode === NODES) {
			return $item;
		} else if($this->mode == VALUES) {
			return $item->nodeValue;
		} else {
			return ($item->hasAttribute($this->attribute)) ? $item->getAttribute($this->attribute) : false;
		}
       
    }

 
   public function key() {
		
		$this->check();
        return $this->position;
    
   }


    public function next() {
	
		$this->check();
        ++$this->position;
    
	}


    public function valid() {
	
		$this->check();
        $ret = (is_object($this->list) && $this->list->length > $this->position);
		
		return $ret;
    
	}


	public function setList($list) {
		
		try {
			if(!$this->check($list)) {
				throw new XmlValidatorException("Bad input parameter, list argument is missing or is not a valid DOMNodeList object!");
			} 
		}
		catch(Exception $e) {
			throw $e;
		}
	
	
		$this->list = $list;
		$this->rewind();
	}
	
	
	public function getList() {
	
		return $this->list;
		
	}
	
	public function getListLength() {

		return ($this->list) ? $this->list->length : 0;
		
	}
	
	public function setMode($mode) {
		
		if($mode !== NODES && $mode !== VALUES) {
			$this->mode = ATTRIBUTES;
			$this->attribute = $mode; 
		} else {
			$this->mode = $mode;
		}
		
	}
	
	
	public function getMode() {
		
		return $this->mode;
		
	}
	
	private function check($list = null) {
		
		$exception = false;
		
		if($list === null) {
			$list = $this->list;
		}
		
		$ret = ($list && get_class($list) == "DOMNodeList");
		
		return $ret;
	}
	
}

?>