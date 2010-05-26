<?php

class afFilterUtil {
	/**
	 * setting filters to parser criteria
	 * 
	 * @author radu
	 */
	public static function setFilters($peer, Criteria $criteria,$filters)
	{		
		if($filters)
		{		
			for($i=0;$i<count($filters);$i++)
			{
				if(empty($filters[$i]['field'])) {
					continue;
				}
				

				if(self::useCustomFilter($peer, $criteria, $filters[$i])) {
					continue;
				} else {
					switch($filters[$i]['data']['type'])
					{					
						case 'string' :					
							$critAnd[] = $criteria->getNewCriterion($filters[$i]['field'],"%".$filters[$i]['data']['value']."%",Criteria::LIKE); 
							break;
						case 'list' : 
						case 'combo':
							if (strstr($filters[$i]['data']['value'],',')){
								$fi = explode(',',$filters[$i]['data']['value']);							
								$filters[$i]['data']['value'] = $fi;
								$critAnd[] = $criteria->getNewCriterion($filters[$i]['field'],$filters[$i]['data']['value'],Criteria::IN); 
							}else{
								$critAnd[] = $criteria->getNewCriterion($filters[$i]['field'],$filters[$i]['data']['value'],Criteria::EQUAL);							 
							}
							Break;
						case 'boolean' : 
							$critAnd[] = $criteria->getNewCriterion($filters[$i]['field'],$filters[$i]['data']['value'],Criteria::EQUAL); 
							Break;
						case 'numeric' :					
							switch ($filters[$i]['data']['comparison']) {
								case 'eq' : 
									$critAnd[] = $criteria->getNewCriterion($filters[$i]['field'],$filters[$i]['data']['value'],Criteria::EQUAL); 
									break;
								case 'lt' : 
									$critNumeric[] = $criteria->getNewCriterion($filters[$i]['field'],$filters[$i]['data']['value'],Criteria::LESS_THAN); 
									break;
								case 'gt' : 
									$critNumeric[] = $criteria->getNewCriterion($filters[$i]['field'],$filters[$i]['data']['value'],Criteria::GREATER_THAN); 
									break;
							}
							break;
						case 'date' : 
							switch ($filters[$i]['data']['comparison']) {
								case 'eq' : 
									//$critAnd[] = $criteria->getNewCriterion($filters[$i]['field'],date('Y-m-d',strtotime($filters[$i]['data']['value'])),Criteria::EQUAL);
									$critDate[] = $criteria->getNewCriterion($filters[$i]['field'],date('Y-m-d',strtotime($filters[$i]['data']['value'])),Criteria::GREATER_THAN);
									$critDate[] = $criteria->getNewCriterion($filters[$i]['field'],date('Y-m-d',strtotime($filters[$i]['data']['value'])+(24*60*60)),Criteria::LESS_THAN);
									break;
								case 'lt' : 
									$critDate[] = $criteria->getNewCriterion($filters[$i]['field'],date('Y-m-d',strtotime($filters[$i]['data']['value'])),Criteria::LESS_THAN);
									break;
								case 'gt' : 
									$critDate[] = $criteria->getNewCriterion($filters[$i]['field'],date('Y-m-d',strtotime($filters[$i]['data']['value'])),Criteria::GREATER_THAN);
									break;
							}
						break;
					}
				}
			}
			
			
			//print_r($critNumeric);
			
			if(isset($critNumeric))
			{
				$critNumericCount=count($critNumeric);
							
				if($critNumericCount>0)
				{				
					for ($i=1;$i<$critNumericCount;$i++)
					{
						$critNumeric[0]->addAnd($critNumeric[$i]);
					}
															
					$criteria->add($critNumeric[0]);
				}
			}
			
			//print_r($criteria);
			
			if(isset($critDate))
			{
				$critDateCount=count($critDate);
				
				if($critDateCount>0)
				{				
					for ($i=1;$i<$critDateCount;$i++)
					{
						$critDate[0]->addAnd($critDate[$i]);
					}
					
					$criteria->add($critDate[0]);
				}
			}
									
			if(isset($critAnd))
			{
				$critAndCount=count($critAnd);
				
				if($critAndCount>0)
				{
					for ($i=1;$i<$critAndCount;$i++)
					{
						$critAnd[0]->addAnd($critAnd[$i]);
					}
					
					$criteria->add($critAnd[0]);
				}
			}
		}
	}

	private static function useCustomFilter($peer, $criteria, $filter) {
		$method = 'filter'.sfInflector::camelize($filter['field']);
		$customFilter = array($peer, $method);
		if(!is_callable($customFilter)) {
			return false;
		}

		call_user_func($customFilter, $criteria, $filter['data']);
		return true;
	}
}

