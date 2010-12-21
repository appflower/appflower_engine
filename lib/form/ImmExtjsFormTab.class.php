<?php
/**
 * extJs Form Tab
 *
 */
class ImmExtjsFormTab
{
	/**
	 * default attributes for the column
	 */
	public $attributes=array('layout'=>'form','height'=>50,'autoScroll'=>true,'bodyStyle'=>'padding-right:20px');
	
	public $immExtjs=null;						
							
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
		//echo "<pre>";print_r($this->attributes);	
	}
	
	public function addMember($item)
	{				
		if(is_array($item))
		{
			$this->attributes['items'][]=$this->immExtjs->asAnonymousClass($item);
		}
		elseif(is_object($item)) {
			$this->attributes['items'][]=$this->immExtjs->asVar($item->privateName);
		}
	}
	
	public function startFieldset($attributes=array())
	{
		return new ImmExtjsFieldset($attributes);		
	}
	
	public function endFieldset($fieldsetObj)
	{
		$this->attributes['items'][]=$fieldsetObj->end();
	}
	
	public function startGroup($type,$attributes=array())
	{
		$class='ImmExtjsField'.ucfirst($type).'Group';
		return new $class($this,$attributes);
	}
	
	public function endGroup($groupObj)
	{
		$this->attributes['items'][]=$groupObj->end();
	}
	
	public function startColumns()
	{
		return new ImmExtjsFormColumns();		
	}
	
	public function endColumns($columnsObj)
	{
		$this->attributes['items'][]=$columnsObj->end();
	}
	public function prepareForSetting($attr){
		
		if(!$attr['isSetting']) return $attr;
		//echo "<pre>";print_r($attr);
		$backup = $attr;
		$s = array();
		unset($attr['items']);
		$s = $attr;
		
		$help = array();
		$form = array();
					
		$help['border'] = "false";
		//$help['bodyStyle'] = "padding:5px 5px 15px 5px";
		$help['title'] = $attr['title'];
		$help['html'] = "";
	
		
		$s['items'][] = $help;
		$s['bodyStyle']="padding-top:5px";		
		
		if(is_array($backup['items']))
		foreach($backup['items'] as $item){							
			$s['items'][] = $item;					
		}
		else $s['items'][] = $backup['items'];
		
		return $s;
		
	}
	public function end()
	{
		if(count($this->attributes['items'])>0)
		$this->attributes['items']=array_diff($this->attributes['items'],array(null));
		//change for settings		
		$this->attributes = $this->prepareForSetting($this->attributes);
		return $this->immExtjs->asAnonymousClass($this->attributes);
	}	
}
?>