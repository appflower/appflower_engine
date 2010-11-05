<?php

class afPortalState extends BaseafPortalState
{
	public $content_unserialized=false;
	
	public function getContent()
	{
		if(!$this->content_unserialized)
		{
			$this->content_unserialized=unserialize($this->content);
		}
		
		/**
		 * sort by key
		 */
		ksort($this->content_unserialized);
		
		return $this->content_unserialized;
	}
	
	public function setContent($v)
	{
		if ($this->content !== $v) {
			$this->content_unserialized=false;
			$this->content = serialize($v);
			$this->modifiedColumns[] = afPortalStatePeer::CONTENT;
		}

		return $this;
	} 
		
	public function getColumns($item=0)
	{
		$content=$this->getContent();
		
		if(isset($content[$item])&&isset($content[$item]['portalColumns']))
		{
			return $content[$item]['portalColumns'];
		}
		else {
			return false;
		}
	}
	
	public function getColumn($item=0,$column=0)
	{
		$content=$this->getContent();
		
		if(isset($content[$item])&&isset($content[$item]['portalColumns'])&&isset($content[$item]['portalColumns'][$column]))
		{
			return $content[$item]['portalColumns'][$column];
		}
		else {
			return false;
		}
	}
	
	public function setColumns($item,$value)
	{
		$content=$this->getContent();
		
		if(!isset($content[$item]))
		{
			$content[$item]=array();
			$content[$item]['portalColumns']=array();
		}
		
		$content[$item]['portalColumns']=$value;
		
		$this->setContent($content);
	}
	
	public function setColumn($item,$column,$value)
	{
		$content=$this->getContent();
		
		if(!isset($content[$item]))
		{
			$content[$item]=array();
			$content[$item]['portalColumns']=array();
		}
		
		$content[$item]['portalColumns'][$column]=$value;
		
		$this->setContent($content);
	}
	
	public function getColumnsSize($item=0)
	{
		return json_decode($this->getPortalLayoutType($item));
	}
	
	public function getPortalLayoutType($item=0)
	{
		$content=$this->getContent();
		
		if(isset($content[$item])&&isset($content[$item]['portalLayoutType']))
		{
			return $content[$item]['portalLayoutType'];		
		}
		else {
			return false;
		}
	}
	
	public function setPortalLayoutType($item,$value)
	{
		$content=$this->getContent();
		
		if(!isset($content[$item]))
		{
			$content[$item]=array();
			$content[$item]['portalLayoutType']='';
		}
		
		$content[$item]['portalLayoutType']=$value;

		$this->setContent($content);	
	}
	
	public function getPortalTitle($item=0)
	{
		$content=$this->getContent();
		
		if(isset($content[$item])&&isset($content[$item]['portalTitle']))
		{
			return $content[$item]['portalTitle'];		
		}
		else {
			return 'Tab '.$item;
		}
	}
	
	public function setPortalTitle($item,$value)
	{
		$content=$this->getContent();
		
		if(!isset($content[$item]))
		{
			$content[$item]=array();
			$content[$item]['portalTitle']='';
		}
		
		$content[$item]['portalTitle']=$value;

		$this->setContent($content);	
	}
	
	public function save(PropelPDO $con = null)
	{
		if($this->user_id==null) {
            $afUser = sfContext::getInstance()->getUser()->getAppFlowerUser();
            if (!$afUser->isAnonymous()) {
                $this->setUserId($afUser->getId());
                parent::save($con);
            }
        }
	}
}
