<?php

class afApiActions extends sfActions
{
    public function executeListjson() {
        $config = $this->getRequestParameter('config');
        list($module, $action) = explode('/', $config);

        $doc = afConfigUtils::getDoc($module, $action);
        $view = afDomAccess::wrap($doc, 'view');
        $source = self::createDataSource($view);
        self::setupDataSource($source, $this->getRequest());
        if($view->getBool('fields@tree')) {
            $source->setLimit(null);
        }

        $gridData = new ImmExtjsGridData();
        $gridData->totalCount = $source->getTotalCount();
        $gridData->data = $source->getRows();
        return $this->renderText($gridData->end());
    }

    private function setupDataSource($source, $request) {
        $source->setStart($request->getParameter('start', 0));
        $source->setLimit($request->getParameter('limit', 20));
        $sortColumn = $request->getParameter('sort');
        if($sortColumn) {
            $source->setSort($sortColumn, $request->getParameter('dir', 'ASC'));
        }
    }

    private function oldCode() {
        // TODO: remove the old code
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

    private static function createDataSource($view) {
        $columns = $view->wrapAll('fields/column');
        $selectedColumns = array();
        foreach($columns as $column) {
            $selectedColumns[] = $column->get('@name');
        }

        //TODO: support also the other datasources: file and static
        $sourceType = $view->get('datasource@type');
        if($sourceType === 'orm') {
            list($callback, $params) = self::getDataSourceCallback($view);
            list($peer, $method) = $callback;
            $criteria = call_user_func_array($callback, $params);

            $class = self::getClassFromPeerClass($peer);
            $source = new afPropelSource($class, $selectedColumns);
            $source->setCriteria($criteria);
        } else if($sourceType === 'static') {
            list($callback, $params) = self::getDataSourceCallback($view);
            $source = new afStaticSource($callback, $params);
        }

        return $source;
    }

    private static function getDataSourceCallback($view) {
        //TODO: parse the params from the XML config
        $params = array();
        $class = $view->get('datasource/class');
        $method = $view->get('datasource/method@name');
        $callback = array($class, $method);
        return array($callback, $params);
    }

    private static function getClassFromPeerClass($peerClass) {
        $omClass = call_user_func(array($peerClass, 'getOMClass'));
        return substr('.'.$omClass, strrpos('.'.$omClass, '.') + 1);
    }
}
