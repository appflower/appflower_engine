<?php
/**
 * extJs Desktop Links, Configuration setup
 */
class afExtjsDesktopLinks
{
    /**
     * Contains path to the transparent 1px image.
     */
    const EMPTY_ICON = "/appFlowerPlugin/extjs-3/plugins/desktop/images/s.gif"; 
    
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
	    $return = array();
	    $items = (array_key_exists('items', $this->attributes)) ? $this->attributes['items'] : array();
	    
		foreach ($items as $link) {
            if (empty($link['iconCls'])) {
                $link['iconCls'] = empty($link['icon']) ? "desktop-win-shortcut" : '';
            }

            if (empty($link['icon'])) {
                $link['icon'] = self::EMPTY_ICON;
            }
            
            if (!isset($link['title']) && $link['url'] != '') {
                $link['title'] = $link['url'];
            } elseif (!isset($link['title']) && ($link['url'] == '' || isset($link['url']))) {
                $link['title'] = 'default';
            }

            $return[] = '<dt class="shortcut ' . $link['iconCls'] . '"><a href="javascript:return false;"' . (($link['url']!='') ? ' onclick="afApp.widgetPopup("' . $link['url']. '"); return false;' : "") . '><img src="' . $link['icon'] . '" /><div>' . $link['title'] . '</div></a></dt>';
		}
		
		echo implode(null, $return);
	}
}
?>