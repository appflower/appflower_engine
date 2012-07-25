<?php
/**
 * extJs form
 *
 */
class afExtjsForm 
{
	/**
	 * default attributes for the form
	 */
	public $attributes=array('width'      => '100%',
							'bodyStyle'=>'padding:0px',
							'idxml'=>false
							/*'fileUpload'=>true*/);
	
	public $afExtjs=null;	
	public $privateName=null;
	public $menuactions_items=array();
	private $validators = array();
	public $fieldTypes = array();
							
	public function __construct($attributes=array())
	{	
	    $this->afExtjs=afExtjs::getInstance();
		
		$this->privateName='form_'.Util::makeRandomKey();
		
		$this->attributes['name']=$this->privateName;
		$this->attributes['id']=$this->privateName;
		//$this->attributes['defaults']=array('labelStyle'=>'width:75px;font-size:11px;font-weight:bold;padding:0 3px 3px 0;');
		if(isset($attributes['labelWidth'])){
			//$this->attributes['defaults']=array('labelStyle'=>'width:'.$attributes['labelWidth'].'px;font-size:11px;font-weight:bold;padding:0 3px 3px 0;');
		}
		/*if(isset($attributes['idxml'])&&$attributes['idxml'])
		{
			$this->attributes['id']=$attributes['idxml'];
		}*/
		
		$this->afExtjs->setAddons(array ('css' => array('/appFlowerPlugin/css/my-extjs.css',$this->afExtjs->getPluginsDir().'multiselect/multiselect.css'), 'js' => array($this->afExtjs->getPluginsDir().'multiselect/DDView.js',$this->afExtjs->getPluginsDir().'multiselect/MultiSelect.js',$this->afExtjs->getPluginsDir().'multiselect/ItemSelector.js',$this->afExtjs->getPluginsDir().'multiselect/Ext.ux.TreeItemSelector.js',$this->afExtjs->getPluginsDir().'tree/Ext.tree.TreeSerializer.js',$this->afExtjs->getPluginsDir().'form/Ext.ux.ClassicFormPanel.js',$this->afExtjs->getPluginsDir().'form/Ext.ux.form.ComboWButton.js') ));

		if(isset($attributes['action']))
		{		
			$this->attributes['url']=$attributes['action'];
			unset($attributes['action']);
		}
		
		if(isset($attributes['portal'])&&$attributes['portal']==true)
		{
			$this->attributes=array_merge($this->attributes,array('anchor'=> '100%',
															'frame'=>true,
															'collapsible'=>true,
															'draggable'=>true,
															'cls'=>'x-portlet'));
															
			unset($attributes['portal']);
		}
				
		if(isset($attributes['tools']))
		{
			$this->attributes['tools']=$attributes['tools']->end();
			
			unset($attributes['tools']);
		}
				
		$this->attributes['getWidgetConfig']=$this->afExtjs->asMethod("var o={}; o.idxml=this.idxml || false; return o;");
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
	
	public function addMenuActionsItem($attributes)
	{			
		$this->menuactions_items[]=$attributes;			
	}
	
	public function addMenuActions()
	{
		
		if(count($this->menuactions_items)>0)
		{		
			/**
			 * Fill to move menuactions button to the right
			 */
			new afExtjsToolbarFill($this,array("moreaction" => true));
			
			$menuactions_button=new afExtjsToolbarButton($this,array('label'=>'More Actions'));
			$menuactions_menu=new afExtjsToolbarMenu($menuactions_button);		
			
			foreach ($this->menuactions_items as $attributes)
			{
				$item=new afExtjsToolbarMenuItem($menuactions_menu,$attributes);$item->end();
			}		
			
			$menuactions_menu->end();
			$menuactions_button->end();
		}
	}
	
	public function addScripts(Array $scripts) {
		
		foreach($scripts as $script) {
			$this->afExtjs->setAddons(array('js'=>array($script)));
		}
		
	}
	
	public function startFieldset($attributes=array())
	{
		return new afExtjsFieldset($attributes);		
	}
	
	public function endFieldset($fieldsetObj)
	{
		$this->attributes['items'][]=$fieldsetObj->end();
	}
	
	public function startColumns()
	{
		return new afExtjsFormColumns();		
	}
	
	public function endColumns($columnsObj)
	{
		$this->attributes['items'][]=$columnsObj->end();
	}
	
	public function startTabs($att=array())
	{
		return new afExtjsFormTabs($att);		
	}
	
	public function endTabs($tabsObj)
	{
		$this->attributes['items'][]=$tabsObj->end();
	}
	
	
	public function addButton($button)
	{
		if(is_array($button)) {
			if(!isset($this->attributes['tbar']))
			$this->attributes['tbar']=array();
			
			array_push($this->attributes['tbar'],$this->afExtjs->asAnonymousClass($button));
		} else {
			if(!isset($this->attributes['buttons']))
			$this->attributes['buttons']=array();
	
			array_push($this->attributes['buttons'],$this->afExtjs->asVar($button->end()));	
		}		

	}
	
	public function addMember($item)
	{
		$this->attributes['items'][]=$this->afExtjs->asAnonymousClass($item);		
	}
	
	public function startGroup($type,$attributes=array())
	{
		$class='afExtjsField'.ucfirst($type).'Group';
		return new $class($this,$attributes);
	}
	
	public function endGroup($groupObj)
	{
		$this->attributes['items'][]=$groupObj->end();
	}
	
	public function addHelp($html)
	{
		if(!isset($this->attributes['tbar']))
		{
			$this->attributes['tbar']=array();
		}
		
		$panel=new afExtjsPanel(array('html'=>'<div style="white-space:normal;">'.$html.'</div>','listeners'=>array('render'=>$this->afExtjs->asMethod(array("parameters"=>"panel","source"=>"if(panel.body){panel.body.dom.style.border='0px';panel.body.dom.style.background='transparent';}")))));
		$this->attributes['listeners']['render']=$this->afExtjs->asMethod("var tb;if(this.getTopToolbar()&&this.getTopToolbar().items.items.length==0){tb = this.getTopToolbar();tb.addItem(".$panel->privateName.");}else{ tb = new Ext.Toolbar({renderTo: this.tbar,items: [".$panel->privateName."]});}if(tb&&tb.container){tb.container.addClass('tbarBottomBorderFix');}");
		
	}

	public function addValidator($fieldName, $validatorCfg) {
		$this->validators[$fieldName] = $validatorCfg;
	}

	public function getValidators() {
		return $this->validators;
	}
	
	public function addFieldType($fieldName, $fieldType) {
		$this->fieldTypes[$fieldName] = $fieldType;
	}

	public function getFieldTypes() {
		return $this->fieldTypes;
	}	
	
	public function end()
	{			
		foreach ($this->attributes['items'] as $k=>$item)
		{
			if($item==null)
			unset($this->attributes['items'][$k]);
		}
		
		// Add moreactions
		
		$this->addMenuActions();
		
		if(isset($this->attributes['classic'])&&$this->attributes['classic'])
		{
			$this->afExtjs->private[$this->privateName]=$this->afExtjs->ClassicFormPanel($this->attributes);
		}
		else {
			$this->afExtjs->private[$this->privateName]=$this->afExtjs->FormPanel($this->attributes);
		}
	}
}
?>
