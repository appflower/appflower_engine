<?php
/**
 * extJs Button
 *
 */
class ImmExtjsButton
{
	/**
	 * default attributes for the button
	 */
	public $attributes=array('disabled'=>false);
							
	public $privateName=null;	
	public $immExtjs=null;			
	public $containerObject=null;			
							
	public function __construct($containerObject,$attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		$this->containerObject=$containerObject;

		if(count($this->attributes)>0)
		$attributes=array_merge($this->attributes,$attributes);
		
		if(isset($attributes['icon']))
		{
			$this->attributes['cls']='x-btn-text-icon';
		}
		
		if(isset($attributes['icon'])&&isset($attributes['iconPosition']))
		{
			if($attributes['iconPosition']=='right')
			{
				$this->attributes['cls']='x-btn-text-icon-right';
			}
			elseif($attributes['iconPosition']=='left')
			{
				$this->attributes['cls']='x-btn-text-icon';
			}
		}
				
		if(isset($attributes['state']))
		{		
			switch ($attributes['state'])
			{
				case "disabled":
					$this->attributes['disabled']=true;	
				break;
			}
			
			unset($attributes['state']);
		}

		if(isset($attributes['label']))
		{
			$this->attributes['text']=$attributes['label'];
			
			unset($attributes['label']);
		}
		
		if(isset($attributes['handlers'])&&$attributes['handlers']!='')
		{
			if(isset($this->attributes['listeners']))
			{
				foreach ($this->attributes['listeners'] as $type=>$type_params)
				{
					/**
					 * if listener function source is not an array then the string is used directly
					 */
					if (isset($type_params['parameters'])&&$type_params['parameters']!=''&&isset($type_params['source'])&&!is_array($type_params['source']))
					{
						$this->attributes['listeners'][$type]=(array('parameters'=>$type_params['parameters'],'source'=>$type_params['source'].$attributes['handlers'][$type]['source']));
					}
				}
			}
			else {
				$this->attributes['listeners']=$attributes['handlers'];
			}
			
			unset($attributes['handlers']);			
		}
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
		
		//add the button to the container
		//to a object container
		if(is_object($containerObject))
		{
			if(method_exists($this->containerObject,'addButton'))
			{
				$this->containerObject->addButton($this);
			}
		}
		//to a panel from viewport (south,west,east,center)
		else {
			ImmExtjsLayout::getInstance()->attributes['viewport'][$containerObject.'_panel']['tbar'][]=$this->immExtjs->asVar($this->end());
		}
	}
		
	public function end()
	{
		/**
		 * don't use the hidden attribute
		 */
		unset($this->attributes['hidden']);
		
		$this->privateName='button_'.Util::makeRandomKey();
		
		if(isset($this->attributes['listeners'])&&count($this->attributes['listeners'])>0)
		{
			foreach ($this->attributes['listeners'] as $type=>$type_params)
			{
				/**
				 * if listener function source is not an array then the string is used directly
				 */
				if (isset($type_params['parameters'])&&isset($type_params['source'])&&!is_array($type_params['source']))
				{
					$this->attributes['listeners'][$type]=$this->immExtjs->asMethod(array('parameters'=>$type_params['parameters'],'source'=>$type_params['source']));
				}
			}
		}
		
		$this->immExtjs->private[$this->privateName]=$this->immExtjs->Button($this->attributes);
		
		return $this->privateName;
	}
}
?>