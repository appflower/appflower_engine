<?php
/**
 * extJs Desktop Links, Configuration setup
 */
class afExtjsDesktopLinks
{
	public $attributes=array();
	
	public $afExtjs=null;		
							
	public function __construct()
	{		
		$this->afExtjs=afExtjs::getInstance();
	}
	
	public function addLink($item)
	{
		$this->attributes['items'][]=$item;
	}
	
	public function end()
	{			
		foreach ($this->attributes['items'] as $link)
		{
		  if(!isset($link['iconCls'])||$link['iconCls']=='')
		  {
		    $link['iconCls'] = "desktop-win-shortcut";
		  }
		  
		  if(!isset($link['title'])&&$link['url']!='')
		  {
		    $link['title'] = $link['url'];
		  }
		  elseif (!isset($link['title'])&&($link['url']==''||isset($link['url']))) {
		    $link['title'] = 'default';
		  }
		  
		  $return[] = "<dt id=\"".$link['iconCls']."\"><a href=\"javascript:return false;\"".(($link['url']!='')?" onclick='afApp.widgetPopup(\"".$link['url']."\");'":"")."><img src=\"/appFlowerPlugin/extjs-3/plugins/desktop/images/s.gif\" /><div>".$link['title']."</div></a></dt>";
		}
		
		echo implode(null,$return);
	}
}
?>