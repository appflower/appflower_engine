<?php

class afDataFacade {
    const 
        DEFAULT_PROXY_LIMIT = 20;

    public static function getDataSource($view, $requestParams) {

        $source = self::createDataSource($view,ArrayUtil::get($requestParams, 'filter', null));
        
        if($view->get("@type") == "list") {
        	self::setupDataSource($view, $source, $requestParams);	
	        if($view->getBool('fields@tree')) {
                $source->setLimit(null);	
	        }
        }

        return $source;
    }

    private static function setupDataSource($view, $source, $params) {
        $source->setStart(ArrayUtil::get($params, 'start', 0));
        $source->setLimit(ArrayUtil::get($params, 'limit', 
           self::DEFAULT_PROXY_LIMIT));
        $sortColumn = ArrayUtil::get($params, 'sort', null);
        if($sortColumn) {
            $sortColumn = self::getSortColumn($view, $sortColumn);
            $source->setSort($sortColumn, ArrayUtil::get(
                $params, 'dir', 'ASC'));
        }
    }

    /**
     * Returns the given sort column or its sortIndex.
     */
    private static function getSortColumn($view, $sortColumn) {
        $columns = $view->wrapAll('fields/column');
        foreach($columns as $column) {
            if($column->get('@name') == $sortColumn) {
                $sortIndex = $column->get('@sortIndex');
                if($sortIndex) {
                    return constant($sortIndex);
                } else {
                    return $sortColumn;
                }
            }
        }
        return $sortColumn;
    }


    private static function createDataSource($view, $filters, $format='html') {
        $listView = new afListView($view);
        $selectedColumns = $listView->getSelectedColumns();

        //TODO: support also the file datasource
        
        $sourceType = $view->get('datasource@type');
        $viewType = $view->get('@type');
        $className = $view->get('datasource@className');

        if($sourceType === 'orm') {
            list($callback, $params) = self::getDataSourceCallback($view);
            list($peer, $method) = $callback;
            $result = afCall::funcArray($callback, $params);

        	if($viewType == "list") {
            
	            afFilterUtil::setFilters($peer, $result, $filters);	
	
	            $class = self::getClassFromPeerClass($peer);
	            $extractor = new afColumnExtractor($class, $selectedColumns,
	                $format);
                    if ($className == '') {
                        $className = 'afPropelSource';
                    }
                    $source = new $className($extractor);
	            $source->setCriteria($result);
	            
        	} else if($viewType == "edit" || $viewType == "show") {
        		$source = array("object" => $result,"fields" => $view->wrapAll("fields/field"), "grouping" => $view->wrapAll("grouping/set"));
        	}
        } else if($sourceType === 'static') {
            list($callback, $params) = self::getDataSourceCallback($view);
            if ($className == '') {
                $className = 'afStaticSource';
            }
            $source = new $className($callback, $params);
        } else {
            throw new XmlParserException(
                'Unsupported datasource type: '.$sourceType);
        }

        return $source;
    }

    private static function getDataSourceCallback($view) {
        $args = array();
        $params = $view->wrapAll('datasource/method/param');
        foreach($params as $param) {
            $args[] = $param->get('');
        }

        $class = $view->get('datasource/class');
        $method = $view->get('datasource/method@name');
        $callback = array($class, $method);
        return array($callback, $args);
    }

    private static function getClassFromPeerClass($peerClass) {
        $omClass = call_user_func(array($peerClass, 'getOMClass'));
        return substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
    }
}
