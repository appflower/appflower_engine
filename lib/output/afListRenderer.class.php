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
            Newsroom::closeSessionWriteLock();
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
        // To support existing static datasources,
        // html escaping is disabled for them.
        if($source instanceof afPropelSource) {
            self::escapeHtml($rows);
        }
        self::addRowActionSuffixes($view, $rows);

        $gridData = new ImmExtjsGridData();
        $gridData->totalCount = $source->getTotalCount();
        $gridData->data = $rows;
        return afOutput::renderText($gridData->end());
    }

    private static function escapeHtml(&$rows) {
        foreach($rows as &$row) {
            foreach($row as $column => &$value) {
                if($value instanceof sfOutputEscaperSafe) {
                    $value = (string)$value;
                }
                else if(is_string($value) &&
                    preg_match('/^html|^link/i', $column) === 0) {
                    $value = htmlspecialchars($value);
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
                    $urlParams = array();
                    foreach($params as $param) {
                        if(isset($row[$param])) {
                            $urlParams[$param] = $row[$param];
                        }
                    }

                    $rowurl = UrlUtil::addParams($url, $urlParams);
                    $row['action'.$actionNumber] = $rowurl;
                }
            }
        }
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
		
        $rows = $source["result"]->getRows();
        if(count($rows) > 0) {
        	
            $pdf = new afSimplePdf($view);
		 	$pdf->renderList($rows,$source["columns"]);
		 	$pdf->push();
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
            $values[] = ArrayUtil::get($row, $key, '');
        }
        return $values;
    }
}

