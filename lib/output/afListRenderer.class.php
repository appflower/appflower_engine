<?php

class afListRenderer {	
    /**
     * Gets and renders the rows for the given module/action.
     * The output is written directly to the response.
     *
     * It serves three types of requests:
     * 1) It serves JSON data for a ExtJs store.
     * 2) It serves CSV export of the data when af_format=csv.
     * 3) It serves CVS export of a selection when
     *      af_format=csv and selections=[row,...].
     */

	public static function renderList($request, $module, $action, afDomAccess $view) {

        $selections = $request->getParameter('selections');
        if($selections) {
            $source = new afSelectionSource(json_decode($selections, true));
        } else {
            $source = afDataFacade::getDataSource($view,
                $request->getParameterHolder()->getAll());
        }
        
        // For backward compatibility, the session is not closed
        // before calling a static datasource.
        if($source instanceof afPropelSource) {
            afOutput::closeSessionWriteLock();
        }

        $format = $request->getParameter('af_format');
        
        if($format === 'csv') {
            return self::renderCsv($action, $source);
        } else if($format === 'pdf') {
        	return self::renderPdf($view,$source);
        }
        
        return self::renderJson($view, $source);
    }

    private static function renderJson($view, $source) {
        $rows = $source->getRows(); 
        self::addRowActionSuffixes($view, $rows);
        
    	if($view->getBool('@dynamic')) {
        	self::changeEditLinks($rows);
    	}

        $gridData = new ImmExtjsGridData();
        $gridData->totalCount = $source->getTotalCount();
        $gridData->data = $rows;
        $additionalData = $source->getAdditionalData();
        if ($additionalData) {
            $gridData->additionalData = $additionalData;
        }
        return afOutput::renderText($gridData->end());
    }

    private static function changeEditLinks(&$rows) {

    	$module = sfContext::getInstance()->getActionStack()->getLastEntry()->getActionInstance()->getModuleName();
   	
    	foreach($rows as $key => &$row) {
    		foreach($row as $col => &$value) {
    			if(preg_match("/\/([a-zA-Z0-9]+\/edit[a-zA-Z]*)/",$value,$matches)) {
	    			$value = str_replace($matches[1],$module."/edit",$value);
	    		}	
    		}
    	}
    }
    
    
    /**
     * Adds row action urls to the rows.
     */
    private static function addRowActionSuffixes($view, &$rows) {
        $rowactions = $view->wrapAll('rowactions/action');
        $actionNumber = 0;
        foreach($rowactions as $action) {
            $actionNumber++;
            $url = UrlUtil::abs($action->get('@url'));
            $params = $action->get('@params', $action->get('@pk', 'id'));
            $params = explode(',', $params);
            $condition = $action->get('@condition');
            if($condition) {
                $condition = afCall::rewriteIfOldCondition($condition, $params);
            }

            foreach($rows as &$row) {
                if(!$condition || self::isRowActionEnabled($condition, $row)) {
                    self::addRowAction($row, $url, $params, $actionNumber);
                }
            }
        }
    }

    /**
     * Adds a row action if all its params are defined on the row.
     */
    private static function addRowAction(&$row, $url, $params, $actionNumber) {
        $urlParams = array();
        foreach($params as $param) {
            if(!isset($row[$param])) {
                return;
            }

            $urlParams[$param] = $row[$param];
        }

        $rowurl = UrlUtil::addParams($url, $urlParams);
        $row['action'.$actionNumber] = $rowurl;
    }

    private static function isRowActionEnabled($condition, $row) {
        return afCall::evaluate($condition, $row);
    }

    private static function renderCsv($actionName, $source) {
        HttpUtil::sendDownloadHeaders($actionName.'.csv', 'text/csv');

        $rows = $source->getRows();
        if(count($rows) > 0) {
            $keys = array_keys($rows[0]);
            self::pruneKeys($keys);

            echo afOutput::asCsv($keys);
            foreach($rows as $row) {
                echo afOutput::asCsv(self::extractValues($row, $keys));
            }
        }

        exit;
    }
    
    
	private static function renderPdf($view, $source) {
		
        $rows = $source->getRows();
        if(count($rows) > 0) {
            $columns = $view->wrapAll("fields/column");
        	
        	$orientation = (sizeof($columns) > 10) ? "L" : "P";
        	
            $pdf = new afSimplePdf($view,$orientation);
		 	$pdf->render(array($rows,$columns));
            exit();
        }

        exit;
    }
    

    /**
     * Removes _* keys from the list of keys.
     */
    private static function pruneKeys(&$keys) {
        foreach($keys as $i => $key) {
            if(StringUtil::startsWith($key, '_')) {
                unset($keys[$i]);
            }
        }
    }

    private static function extractValues($row, $keys) {
        $values = array();
        foreach($keys as $key) {
            $values[] = StringUtil::removeTagsAndEntities(ArrayUtil::get($row, $key, ''));
        }
        return $values;
    }
}

