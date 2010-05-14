<?php

class afApiActions extends sfActions
{
    public function executeListjson($request) {
        $config = $this->getRequestParameter('config');
        list($module, $action) = explode('/', $config);

        $doc = afConfigUtils::getDoc($module, $action);
        $vars = afConfigUtils::getConfigVars($module, $action, $request);
        $view = afDomAccess::wrap($doc, 'view', new afVarScope($vars));
        // For backward compatibility with listEventMatrixServer.
        $this->getVarHolder()->add($vars);

        $source = self::createDataSource($view,
            $this->getRequestParameter('filter'));
        self::setupDataSource($view, $source, $request);
        if($view->getBool('fields@tree')) {
            $source->setLimit(null);
        }

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
        return $this->renderText($gridData->end());
    }

    private static function setupDataSource($view, $source, $request) {
        $source->setStart($request->getParameter('start', 0));
        $source->setLimit($request->getParameter('limit', 20));
        $sortColumn = $request->getParameter('sort');
        if($sortColumn) {
            $sortColumn = self::getSortColumn($view, $sortColumn);
            $source->setSort($sortColumn, $request->getParameter('dir', 'ASC'));
        }
    }

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
            $url = $action->get('@url');
            $params = $action->get('@params', 'id');
            $params = explode(',', $params);
            foreach($rows as &$row) {
                $rowurl = $url;
                foreach($params as $param) {
                    if(isset($row[$param])) {
                        $rowurl = UrlUtil::addParam($rowurl,
                            $param, $row[$param]);
                    }
                }

                $row['action'.$actionNumber] = $rowurl;
            }
        }
    }

    private static function createDataSource($view, $filters, $format='html') {
        $listView = new afListView($view);
        $selectedColumns = $listView->getSelectedColumns();

        //TODO: support also the file datasource
        $sourceType = $view->get('datasource@type');
        if($sourceType === 'orm') {
            list($callback, $params) = self::getDataSourceCallback($view);
            list($peer, $method) = $callback;
            $criteria = call_user_func_array($callback, $params);
            afFilterUtil::setFilters($criteria, $filters);

            $class = self::getClassFromPeerClass($peer);
            $extractor = new afColumnExtractor($class, $selectedColumns,
                $format);
            $source = new afPropelSource($extractor);
            $source->setCriteria($criteria);
        } else if($sourceType === 'static') {
            list($callback, $params) = self::getDataSourceCallback($view);
            $source = new afStaticSource($callback, $params);
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
