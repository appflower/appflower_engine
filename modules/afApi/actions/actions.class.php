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

        $source = afDataFacade::getDataSource($view,
            $request->getParameterHolder()->getAll());

        $format = $request->getParameter('af_format');
        if($format === 'csv') {
            return $this->renderCsv($source);
        }
        return $this->renderJson($source);
    }

    private function renderJson($source) {
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
            $condition = $action->get('@condition');
            if($condition) {
                $parts = explode(',', $condition);
                $class = $parts[0];
                $method = $parts[1];
                $extraArgs = array_slice($parts, 2);
            }

            foreach($rows as &$row) {
                $urlParams = array();
                foreach($params as $param) {
                    if(isset($row[$param])) {
                        $urlParams[$param] = $row[$param];
                    }
                }

                if(!$condition || self::isRowActionEnabled(
                        $class, $method, $urlParams, $extraArgs)) {
                    $rowurl = UrlUtil::addParams($url, $urlParams);
                    $row['action'.$actionNumber] = $rowurl;
                }
            }
        }
    }

    private static function isRowActionEnabled($class, $method, $urlParams,
        $extraArgs) {
        $args = array_merge(array_values($urlParams), $extraArgs);
        return XmlParser::isActionEnabled($class, $method, $args);
    }

    private function renderCsv($source) {
        $rows = $source->getRows();
        if(count($rows) > 0) {
            $keys = array_keys($rows[0]);
            echo afOutput::asCsv($keys);
            foreach($rows as $row) {
                echo afOutput::asCsv($row);
            }
        }

        exit;
    }
}
