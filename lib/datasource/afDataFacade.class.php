<?php

class afDataFacade {
    public static function getDataSource($view, $requestParams) {

        $source = self::createDataSource($view,
            ArrayUtil::get($requestParams, 'filter', null));
        self::setupDataSource($view, $source, $requestParams);
        if($view->getBool('fields@tree')) {
            $source->setLimit(null);
        }

        return $source;
    }

    private static function setupDataSource($view, $source, $params) {
        $source->setStart(ArrayUtil::get($params, 'start', 0));
        $source->setLimit(ArrayUtil::get($params, 'limit', 20));
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
