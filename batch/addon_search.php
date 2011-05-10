<?php
/**
 * batch for creating cactus.xml containing all js/css files used inside AppFlower Engine
 * 
 * @author Radu Topala <radu@appflower.com>
 */

require_once(dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php');

if(file_exists(dirname(__FILE__).'/../../../plugins/appFlowerStudioPlugin/lib/config/afFilterConfigHandler.class.php'))
{
	require_once(dirname(__FILE__).'/../../../plugins/appFlowerStudioPlugin/lib/config/afFilterConfigHandler.class.php');
}

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration);

$phpFiles = sfFinder::type('file')->name('*.php')->in(sfConfig::get('sf_root_dir').'/plugins/appFlowerPlugin');
$arrays = array('js'=>array(),'css'=>array());
$excluded = array('Ext.ux.NeedHelp.js','TriggerField.js','Ext.ux.grid.Search.js','Ext.ux.IconMenu.js','Ext.ux.plugins.GridRowOrder.js','row-up-down.css');

foreach ($phpFiles as $phpFile)
{
	$content = null;
	$content = file_get_contents($phpFile);

	preg_match_all('/setAddons\((.*)\);/',$content,$matches);

	if(count($matches)>0)
	{
		foreach ($matches[1] as $array)
		{
			if(substr_count($array,'".$plugin."')>0||substr_count($array,"\$script")>0||substr_count($array,"\$addons")>0)
			{
				continue;
			}			

			$array = str_replace('$this->afExtjs->getPluginsDir().\'','\'/extjs-3/plugins/',$array);
			$array = str_replace('$grid->afExtjs->getPluginsDir().\'','\'/extjs-3/plugins/',$array);
			$array = str_replace('afExtjs::getInstance()->getPluginsDir().\'','\'/extjs-3/plugins/',$array);						
			//echo $phpFile.' '.$array."\n";
			$array = eval("return (".$array.");");
			foreach ($array as $type=>$typeArray)
			{
				$arrays[$type]=array_merge($arrays[$type],$typeArray);
			}
		}
	}
}

foreach ($arrays as $arrayType=>$array)
{
	$arrays[$arrayType]=array_unique($array);
}

foreach ($arrays as $arrayType=>$array)
{
	foreach ($array as $k=>$file)
	{
		if(substr_count($file,'appFlowerPlugin')==1)
		{
			$arrays[$arrayType][$k]=str_replace('/appFlowerPlugin','',$file);
		}
		
		foreach ($excluded as $e)
		{
			if(substr_count($file,$e)>0)
			{
				unset($arrays[$arrayType][$k]);
			}
		}
	}
}

foreach ($arrays as $arrayType=>$array)
{
	$arrays[$arrayType]=array_unique($array);
}

$cactusXml[]="<cactus>";
foreach ($arrays as $arrayType=>$array)
{
	$cactusXml[]="\r\n\t<".$arrayType.">\r\n\t\t<needles>\r\n\r\n\t\t\t<!-- AppFlower ".$arrayType." files (".count($arrays[$arrayType]).") -->\r\n\t\t\t<needle>\r\n\t\t\t\t<output>appFlower.".$arrayType."</output>\r\n\t\t\t\t<files>";
	foreach ($array as $file)
	{
		$cactusXml[]="\r\n\t\t\t\t\t<file>".$file."</file>";
	}
	$cactusXml[]="\r\n\t\t\t\t</files>\r\n\t\t\t</needle>\r\n\t\t</needles>\r\n\t</".$arrayType.">";
}
$cactusXml[]="\r\n</cactus>";

file_put_contents(sfConfig::get('sf_root_dir').'/plugins/appFlowerPlugin/config/cactus.xml',implode(null,$cactusXml));