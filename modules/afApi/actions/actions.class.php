<?php

class afApiActions extends sfActions
{
    public function executeListjson() {
        $config = $this->getRequestParameter('config');
        list($module, $action) = explode('/', $config);

        $doc = afConfigUtils::getDoc($module, $action);
        $view = afDomAccess::wrap($doc, 'view');

        $data = array(
            'uid' => $unique_id,
            'title' => $view->get('title'),
            'tree' => $view->getBool('fields@tree'),
            'type' => $view->get('datasource@type'),
            'columns' => explode(',', $view->get('display/visible')),
            //TODO: implement ...

            "group_field" => $group_field,
            "datafile" => (isset($this->attribute_holder["datafile"])) ? $this->attribute_holder["datafile"] : null,
            "realtime" => (isset($this->attribute_holder["realtime"])) ? $this->attribute_holder["realtime"] : false, 
            "wizard" => $this->step,

            "levels" => (isset($parse["levels"])) ? $parse["levels"] : false,
            "datasource" => (isset($parse["datasource"])) ? $parse["datasource"] : null,
            "result" => $result,
            "total_count"=>(isset($this->attribute_holder["total_count"])) ? $this->attribute_holder["total_count"] : null,
            "sql" => isset($parse["datasource"]["statement"]) ? true : false,
            "foreign_keys" => $fks,
            "select_method" => $select_method,
            "criteria" => $criteria, 
            "class" => str_replace("Peer","",$data_class),
            "columns" => $parse["display"]["visible"],
            "selectors" => (isset($parse["display"]["selectors"])) ? $parse["display"]["selectors"] : null,
            "proxy" => $parse["proxy"],
            "limit" => $limit,
            "rowactions" => isset($parse["rowactions"]) ? $parse["rowactions"] : null,
            "actions" => isset($parse["actions"]) ? $parse["actions"] : null,
            "remoteLoad" => ArrayUtil::isTrue($parse, 'remoteLoad'),
            "remoteSort" => (isset($parse["remoteSort"]) && $parse["remoteSort"] == "true") ? true : false,
            "remoteFilter" => (isset($parse["remoteFilter"]) && $parse["remoteFilter"] == "true") ? true : false,
            "exportFrom" => isset($parse["exportFrom"]) ? $parse["exportFrom"] : false,
            "conditions" => isset($parse["conditions"]) ? $parse["conditions"] : false,
            "static_real_pagination"=>isset($parse["params"]["static_real_pagination"]) ? $parse["params"]["static_real_pagination"] : false,
            "reload_topic" => ArrayUtil::get($parse, 'params', 'reload_topic', null),
        );

        //TODO: parse the datasource from the XML config
        return $this->renderText('Not implemented yet');
    }

    private static function fetchData() {
        //TODO: implement ...
        // Getting DB results..
        if($parse["datasource"]["type"] != "file") {
            if(ArrayUtil::isTrue($parse, 'remoteLoad')) {
                $criteria = array();
            } else {
                $criteria = call_user_func_array(array($parse["datasource"]["class"],$parse["datasource"]["method"]["name"]),
                    (isset($parse["datasource"]["method"]["params"])?$parse["datasource"]["method"]["params"]:array()));
            }
        }
        
        if($parse["datasource"]["type"] == "orm") {
            if(get_class($criteria) != "Criteria") {
                throw new XmlParserException("Bad return value, in case of orm source, peer methods must return Criteria object!");
            }	
        } else if($parse["datasource"]["type"] == "static") {
            if(!is_array($criteria)) {
                throw new XmlParserException("Bad return value, in case of static source, peer methods must return Array!");
            }
            $result = $criteria;
            $criteria = null;
        } else if($parse["datasource"]["type"] == "file") {
            $criteria = $result = null;
        }

        $data_class = $parse["datasource"]["class"];	
        if($data_class) {
            if($parse["datasource"]["type"] == "orm") {
                $cols = call_user_func(array($data_class,"getTableMap"));	
                $current_table = $cols->getPhpName();	
            }
        }
        
        $foreign_keys = $selected_foreign_keys = $fks = $fk_count = $data_types = array();
        
        if($parse["datasource"]["type"] == "orm") {
            
            foreach($cols->getColumns() as $colobject) {
                $method = "";
                $dbconn = constant($colobject->getTable()->getPhpName()."Peer::DATABASE_NAME");
                $related_column = strtolower($colobject->getRelatedColumnName());
                $column = strtolower($colobject->getColumnName());
                $data_types[$column] = $colobject->getType();
        
                if($related_column) {
                    $class = self::getPhpName($dbconn, $colobject->getRelatedTableName());
                    
                    if(!class_exists($class)) {
                        throw new XmlParserException("PhpName of table: ".$colobject->getRelatedTableName()." cannot be determined!");
                    } else if(!method_exists($current_table,"get".$class)) {
                        $method = $class."RelatedBy".sfInflector::camelize($column);
                        if(!method_exists($current_table,"get".$method)) {
                            throw new XmlParserException("The getter method cannot be determined for column: ".$column);
                        }
                    }
                    
                    $foreign_keys[] = array("pointer" => $column, "class" => $class, "method" => $method);
                    if(in_array($column,$parse["display"]["visible"])) {
                        $selected_foreign_keys[$column] = array("pointer" => $column, "class" => $class, "method" => $method);
                        $fks[] = ($method) ? $method : $class;
                        if(!isset($fk_count[$class])) {
                            $fk_count[$class] = 0;
                        }
                        $fk_count[$class]++;
                    }
                }
            }	
        
            
            $group_field = "";
            
            foreach($parse["display"]["visible"] as $ik => $item) {
            
                if(isset($parse["fields"][$item]["attributes"]["groupField"]) && 
                 $parse["fields"][$item]["attributes"]["groupField"] === "true") {
                    $group_field = $item;
                }
                
                unset($parse["display"]["visible"][$ik]);
                $parse["display"]["visible"][$ik]["column"] = $item;
                if(isset($selected_foreign_keys[$item])) {
                    $parse["display"]["visible"][$ik]["phpname"] = ($selected_foreign_keys[$item]["method"]) ? $selected_foreign_keys[$item]["method"] : $selected_foreign_keys[$item]["class"];
                } else {
                    $parse["display"]["visible"][$ik]["phpname"] = sfInflector::camelize($item);
                }
                
                if(!empty($data_types) && isset($data_types[$item])) {
                    $parse["display"]["visible"][$ik]["type"] = $data_types[$item]; 	
                }
            }
            
            if(!empty($fk_count)) {
                $duplicates = (max($fk_count) > 1);		
            } else {
                $duplicates = false;
            }
            
            if(!$duplicates && sizeof($selected_foreign_keys) == 0) {
                $select_method = "doSelect";
            } else if(!$duplicates && sizeof($selected_foreign_keys) == 1) {
                $select_method = "doSelectJoin".$selected_foreign_keys[key($selected_foreign_keys)]["class"];
            } else if(!$duplicates && sizeof($foreign_keys)-1 == sizeof($selected_foreign_keys)) {
                foreach($foreign_keys as $value) {
                    if(!array_key_exists($value["pointer"],$selected_foreign_keys)) {
                        $diff = $value;
                        break;
                    }
                }
                $select_method = "doSelectJoinAllExcept".$diff["class"];
            } else {
                $select_method = "doSelect";
                foreach($selected_foreign_keys as $fk) {
                    if($fk_count[$fk["class"]] == 1) {
                        $criteria->addJoin(strtolower(constant($current_table."Peer::".
                        strtoupper($fk["pointer"]))),constant($fk["class"]."Peer::ID"),Criteria::LEFT_JOIN);	
                    }
                }
            }
            $result = array();
        } else {
            $select_method = null;
        }
        
        if(!isset($group_field)) {
            $group_field = "";
        }
    }
}
