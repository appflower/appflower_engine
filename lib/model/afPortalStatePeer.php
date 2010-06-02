<?php

class afPortalStatePeer extends BaseafPortalStatePeer
{
	const TYPE_NORMAL = 'NORMAL';
	const TYPE_TABBED = 'TABBED';
	public $TYPES = array(self::TYPE_NORMAL, self::TYPE_TABBED);
	
	public static function createOrUpdateState($config)
	{
		$afPortalStateObj=self::retrieveByIdXml($config->idXml);
				
		if(!$afPortalStateObj)
		{
			$afPortalStateObj=new afPortalState();
			$afPortalStateObj->setIdXml($config->idXml);
			$afPortalStateObj->setLayoutType($config->layoutType);
			$afPortalStateObj->setContent($config->content);
			$afPortalStateObj->save();			
		}
		else
		{
			$content=$afPortalStateObj->getContent();
			unset($content[$config->layoutItem]);
			$content[$config->layoutItem]=$config->content[$config->layoutItem];
			
			$afPortalStateObj->setLayoutType($config->layoutType);
			$afPortalStateObj->setContent($content);
			$afPortalStateObj->save();
			
			unset($content);
		}
		
		if(isset($config->layoutItem)&&isset($config->content[$config->layoutItem]['portalLayoutNewType'])&&$config->content[$config->layoutItem]['portalLayoutNewType']!=null&&$config->content[$config->layoutItem]['portalLayoutNewType']!=false)
		{
			$oldLayout=json_decode($config->content[$config->layoutItem]['portalLayoutType']);
			$newLayout=json_decode($config->content[$config->layoutItem]['portalLayoutNewType']);
			$countOldLayout=count($oldLayout);
			$countNewLayout=count($newLayout);
			$columns=$config->content[$config->layoutItem]['portalColumns'];
			
			$afPortalStateObj->setPortalLayoutType($config->layoutItem,$config->content[$config->layoutItem]['portalLayoutNewType']);
			
			if ($countOldLayout<$countNewLayout)
			{
				//add diff columns from old layout to new layout
				for($i=$countOldLayout;$i<$countNewLayout;$i++)
				{
					$columns[$i]=array();
				}
								
				$afPortalStateObj->setColumns($config->layoutItem,$columns);
			}
			elseif($countOldLayout>$countNewLayout)
			{
				//remove diff columns from old layout to new layout & copy the widgets from removed columns to the last existing column
				$widgets=array();
				for($i=$countNewLayout;$i<$countOldLayout;$i++)
				{
					if(isset($columns[$i]))
					{
						$widgets=array_merge($widgets,$columns[$i]);
						unset($columns[$i]);
					}
				}
				$columns[$countNewLayout-1]=array_merge($columns[$countNewLayout-1],$widgets);
								
				$afPortalStateObj->setColumns($config->layoutItem,$columns);
			}
			$afPortalStateObj->save();
		}
		
		return $afPortalStateObj;
	}
	
	public static function removeState($config)
	{
		$afPortalStateObj=self::retrieveByIdXml($config->idXml);
				
		if($afPortalStateObj)
		{
			$content=$afPortalStateObj->getContent();
			$currentCount=count($content);
			//unset current tab
			unset($content[$config->layoutItem]);
			//if there is any next tab
			if(isset($content[($config->layoutItem+1)]))
			{
				//move next tabs down in order
				for ($i=($config->layoutItem+1);$i<$currentCount;$i++)
				{
					$content[$i-1]=$content[$i];
				}
				//unset last tab, cause it will contain the same information as the previous one
				unset($content[(count($content)-1)]);
			}
			$afPortalStateObj->setContent($content);
			$afPortalStateObj->save();
			
			unset($content);
			
			return $afPortalStateObj;
		}
		else {
			return false;
		}
	}
	
	public static function updateWidgetsToState($config,$selectedWidgets,$unselectedWidgets)
	{
		$afPortalStateObj=self::retrieveByIdXml($config->idXml);
		
		if($afPortalStateObj)
		{			
			/**
			 * the entire code below reffers to the columns of a tab
			 */
			
			$columns=$afPortalStateObj->getColumns($config->layoutItem);
			
			/**
			 * if there are columns for the $config->layoutItem
			 */
			if($columns)
			{
				/**
				 * first removing unselected widgets, if they exist in portal state tab
				 */
				
				foreach ($unselectedWidgets as $unselectedWidget)
				{
					foreach ($columns as $ci=>$column)
					{						
						foreach ($column as $pwi=>$portalWidget)
						{
							$currentCount=count($column);
							
							if('/'.$portalWidget->idxml==$unselectedWidget)
							{
								unset($columns[$ci][$pwi]);
								
								//if there is any next widget in the column
								if(isset($columns[$ci][$pwi+1]))
								{
									//move next widgets down in order
									for ($i=($pwi+1);$i<$currentCount;$i++)
									{
										$columns[$ci][$i-1]=$columns[$ci][$i];
									}
									//unset last widget, cause it will contain the same information as the previous one
									unset($columns[$ci][(count($columns[$ci])-1)]);
								}
							}					
						}
					}			
				}
				
				$afPortalStateObj->setColumns($config->layoutItem,$columns);
												
				/**
				 * search if there are any selected widgets already added to any of the columns, and if there are, unset those from $selectedWidgets array
				 */
				$columns=$afPortalStateObj->getColumns($config->layoutItem);
				
				foreach ($selectedWidgets as $ksw=>$selectedWidget)
				{
					foreach ($columns as $ci=>$column)
					{
						foreach ($column as $pwi=>$portalWidget)
						{
							if('/'.$portalWidget->idxml==$selectedWidget)
							{
								unset($selectedWidgets[$ksw]);
							}					
						}
					}			
				}
				
				/**
				 * then adding selected widgets to the first column of the portal
				 */
				
				$firstColumnWidgets=$afPortalStateObj->getColumn($config->layoutItem);
				
				$lastKey=count($firstColumnWidgets)-1;
				
				/**
				 * add new widgets to the column
				 */
				foreach ($selectedWidgets as $widget)
				{
					$lastKey++;
					
					$firstColumnWidgets[$lastKey]=new stdClass();
					$firstColumnWidgets[$lastKey]->idxml=substr($widget,1); //unset the first slash from string "/"
				}
				
				$afPortalStateObj->setColumn($config->layoutItem,0,$firstColumnWidgets);	
			}
			/**
			 * if there are not any columns for the $config->layoutItem, this happens only when Add New Tab is pushed
			 */
			else {
				/**
				 * add new widgets to the first column
				 */
				$key=0;
				foreach ($selectedWidgets as $widget)
				{					
					$firstColumnWidgets[$key]=new stdClass();
					$firstColumnWidgets[$key]->idxml=substr($widget,1); //unset the first slash from string "/"
					
					$key++;
				}
				
				/**
				 * set first column
				 */
				$afPortalStateObj->setColumn($config->layoutItem,0,$firstColumnWidgets);
				$afPortalStateObj->setPortalLayoutType($config->layoutItem,$config->content[$config->layoutItem]['portalLayoutType']);
				$afPortalStateObj->setPortalTitle($config->layoutItem,$config->content[$config->layoutItem]['portalTitle']);
			}
			
			$afPortalStateObj->save();
			
			return $afPortalStateObj;
		}
		else {
			return false;
		}
		
	}
	
	public static function searchForWidgetInState($config,$widget)
	{
		$afPortalStateObj=self::retrieveByIdXml($config->idXml);
		
		$exist=false;
		
		if($afPortalStateObj)
		{
			$columns=$afPortalStateObj->getColumns($config->layoutItem);
						
			if($columns)
			{			
				foreach ($columns as $column)
				{
					foreach ($column as $portalWidget)
					{
						if('/'.$portalWidget->idxml==$widget)
						{
							$exist=true;
						}					
					}
				}
			}
		}
		
		return $exist;
	}
	
	public static function retrieveByIdXml($idXml,$userId=0)
	{
		$c=new Criteria();
		$c->add(self::ID_XML,$idXml);
		$c->add(self::USER_ID,(sfContext::getInstance()->getUser()->isAuthenticated()?sfContext::getInstance()->getUser()->getGuardUser()->getId():$userId));
		$afPortalStateObj=afPortalStatePeer::doSelectOne($c);
		
		if($afPortalStateObj!=null)
		{
			return $afPortalStateObj;
		}
		else return false;
	}
	
	public static function deleteByIdXml($idXml,$userId=0)
	{
		$c=new Criteria();
		$c->add(self::ID_XML,$idXml);
		$c->add(self::USER_ID,((sfContext::getInstance()->getUser()->isAuthenticated()&&sfContext::getInstance()->getUser()->getGuardUser()!=null)?sfContext::getInstance()->getUser()->getGuardUser()->getId():$userId));
		$c->setLimit(1);
		return afPortalStatePeer::doDelete($c);
	}
	
}
