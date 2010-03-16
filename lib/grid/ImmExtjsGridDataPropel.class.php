<?php
/**
 * extJs grid data for Propel
 *
 */
class ImmExtjsGridDataPropel
{
	public $data=array();
	
	public $immExtjs=null;	
	public $request=null,$response=null;
	public $pager=null,$start=0,$limit=20,$page=1;
	
	public function __construct($propelClass='')
	{		
		$this->immExtjs=ImmExtjs::getInstance();	
		$this->request=sfContext::getInstance()->getRequest();
		$this->response=sfContext::getInstance()->getResponse();
		
		$this->start = $this->request->getParameterHolder()->has('start')?$this->request->getParameterHolder()->get('start'):$this->start;
		$this->limit = $this->request->getParameterHolder()->has('limit')?$this->request->getParameterHolder()->get('limit'):$this->limit;
  		$this->page=($this->start==0)?1:(ceil($this->start/$this->limit)+1);
  		
  		// pager
		$this->pager = new sfPropelPager($propelClass, $this->limit);
		$this->pager->setPage($this->page);
	}
	
	public function start($c)
	{
		$this->pager->setCriteria($c);
		$this->pager->init();
	}
	
	public function getResults()
	{
		return $this->pager->getResults();
	}
	
	public function addRowData($data=array())
	{
		$this->data[]=$data;
	}
	
	public function end()
	{		
		$o = array(
			 "success"=>true
			,"totalCount"=>$this->pager->getNbResults()
			,"rows"=>$this->data
		);
		
		$result=json_encode($o);
		
		$this->response->setContent($this->response->getContent().$result);

    	return sfView::NONE;
	}
}
?>