<?php
/**
 * Gird filter helper class
 * 
 * The class will add filters in the grid as defined in the xml config.
 * The grid data is clickable for drill down filter.
 *  
 * @author Prakash Paudel
 *	
 */
class ImmExtjsGridFilter{
	
	// Defines the default filter types supported
	public static $ftypes = array("boolean","numeric","list","string","combo","date");
	
	public static function add($grid,$column,$temp_column,$temp_field){		
		if(isset($column['filter'])){
			$tf = array();
			$f = $column['filter'];					
			$tf['dataIndex'] = $temp_column['dataIndex'];
			if(preg_match('/^html_/',$tf['dataIndex'])){				
				$tf['dataColumn'] = preg_replace('/^html_/','',$tf['dataIndex']);				 
			}					
			if(isset($f['dataIndex'])){
				$tf['dataColumn'] = $f['dataIndex'];
			}
			if(isset($f['phpMode'])){
				$tf['phpMode'] = $f['phpMode'];
			}					
			$tf['type'] = isset($f['type'])?$f['type']:(isset($temp_field['type'])?$temp_field['type']:'string');					
			if(!isset($grid->attributes['remoteFilter'])) $tf['type'] = "string";
			if(isset($f['class']) && isset($f['method'])){
				$datas = call_user_func(array($f['class'],$f['method']));
				foreach($datas as $key=>$value){
					$tf['options'][] = array($key,$value);	
				}
				if(!isset($f['type'])) $tf['type'] = 'combo';
			}
			if(isset($f['options'])){
				$tf['options'][] = $f['options'];
			}
			if(isset($f['selectable'])){
				$tf['selectable'] = $f['selectable'];
			}
			if(isset($f['sortby'])){
				$tf['sortby'] = $f['sortby'];
			}
				
			if(isset($f['single'])){
				$tf['single'] = $f['single'];
			}	
			if(!in_array($tf['type'],self::$ftypes)){
				if($datas = self::getList($tf['type'])){
					$tf['type'] = 'list';
					foreach($datas as $key=>$value){
						$tf['options'][] = array($key,$value);	
					} 
				}
			}
			
			// Currently if type is list then only single is supported
			// Will be extended soon.....
			if($tf['type'] == "list"){
				//$tf['single'] = true;
				$tf['phpMode'] = true;
			}
			if($tf['type'] == "combo"){
				//$tf['single'] = true;
				$tf['lovcombo'] = true;
			}
			
			$grid->immExtjs->setAddons(array('js' => array($grid->immExtjs->getExamplesDir().'form/lovcombo-1.0/js/Ext.ux.form.LovCombo.js') ));					
			$grid->immExtjs->setAddons(array('css' => array($grid->immExtjs->getExamplesDir().'form/lovcombo-1.0/css/Ext.ux.form.LovCombo.css') ));
								
					
			$grid->addFilter($tf);
		}else {					
			if(!isset($grid->attributes['remoteFilter'])){
				$temp_filter=array('type'=>isset($temp_field['type'])?$temp_field['type']:"string");
				$temp_filter['dataIndex']=$temp_column['dataIndex'];				
				$grid->addFilter($temp_filter);
			}else{						
				if(isset($column['type'])){
					if(in_array($column['type'],self::$ftypes)){
						$temp_filter=array('type'=>$column['type']);
						$temp_filter['dataIndex']=$temp_column['dataIndex'];				
						$grid->addFilter($temp_filter);						
					}
				}
			}
		}
	}
	public static function filter($text,$value=NULL){
		if($value === NULL){
			$value = $text;
		}
		$template = '<a class="ux-grid-filter" href="#" onclick="">'.$text.'</a><div class="ux-grid-filter-hidden-value">'.$value.'</div>';
		return $template;
	}
	public static function getList($flag){
		switch($flag){
			case 'YES_NO':
				return array(
					'yes'=>"Yes",
					'no'=>"No"
				);
			case 'YES_NO_NUMERIC':
				return array(
					'1'=>"Yes",
					'0'=>"No"
				);
			case 'TRUE_FALSE':
				return array(
					'true'=>"True",
					'false'=>"False"
				);
			case 'TRUE_FALSE_NUMERIC':
				return array(
					'1'=>"True",
					'0'=>"False"
				);
			case 'ACTIVE_INACTIVE':
				return array(
					'1'=>"Active",
					'0'=>"Inactive"
				);
            case 'APPROVED_DISAPPROVED':
                return array(
                    '1'=>"Approved",
                    '0'=>"Disapproved"
            );
            case 'ENABLED_DISABLED':
                return array(
                    '1'=>"Enabled",
                    '0'=>"Disabled"
                );
			case 'SEVERITY_LEVEL_07':
				return array(
					'0'=>"Emergency",
					'1'=>"Alert",
					'2'=>"Critical",
					'3'=>"Error",
					'4'=>"Warning",
					'5'=>"Notice",
					'6'=>"Info",
					'7'=>"Debug"				
				);
			case 'SEVERITY_LEVEL_18':
				return array(
					'1'=>"Emergency",
					'2'=>"Alert",
					'3'=>"Critical",
					'4'=>"Error",
					'5'=>"Warning",
					'6'=>"Notice",
					'7'=>"Info",
					'8'=>"Debug"				
				);
			case 'FACILITY_LEVEL':
					return array(
					'0'=>'Kernel Messages',
					'1'=>'User-Level Messages',
					'2'=>'Mail System',
					'3'=>'System Daemons',
					'4'=>'Security/Authorization Messages',
					'5'=>'Messages Generated Internally by Syslogd',
					'6'=>'Line Printer Subsystem',
					'7'=>'Network News Subsystem',
					'8'=>'UUCP Subsystem',
					'9'=>'Clock Daemon',
					'10'=>'Security/Authorization Messages',
					'11'=>'FTP Daemon',
					'12'=>'NTP Subsystem',					 
					'13'=>'Log Audit',
					'14'=>'Log Alert',
					'15'=>'Clock Daemon',
					'16'=>'local use 0 (local0)',
					'17'=>'local use 1 (local1)',
					'18'=>'local use 2 (local2)',
					'19'=>'local use 3 (local3)',
					'20'=>'local use 4 (local4)',
					'21'=>'local use 5 (local5)',
					'22'=>'local use 6 (local6)',
					'23'=>'local use 7 (local7)'			
				);
			default:
				return false;
		}		
	}
	
}