<?php
/**
 * extJs grid data
 *
 */
class ImmExtjsGridData
{
	public $data=array();
	public $totalCount=null;
        public $additionalData=null;
	
	public $immExtjs=null;	
	public $response=null;
		
	public function __construct()
	{		
		$this->immExtjs=ImmExtjs::getInstance();	
		$this->response=sfContext::getInstance()->getResponse();  		
	}
		
	public function addRowData($data=array())
	{
		$this->data[]=$data;
	}
	
	public function end()
	{		
		$o = array(
			 "success"=>true
			,"totalCount"=>$this->totalCount
			,"rows"=>$this->data
                        ,"additionalData"=>$this->additionalData
		);
		
		$result=json_encode($o);
		
		return $result;
	}
}
?>