<?php

class afApiActions extends sfActions
{
    /**
     * This function serves three types of requests:
     * 1) It serves JSON data for a ExtJs store.
     * 2) It serves CSV export of the data when af_format=csv.
     * 3) It serves CVS export of a selection when
     *      af_format=csv and selections=[row,...].
     */
    public function executeListjson($request) {
        $config = $this->getRequestParameter('config');
        list($module, $action) = explode('/', $config);

        $vars = afConfigUtils::getConfigVars($module, $action, $request);
        // For backward compatibility with listEventMatrixServer.
        $this->getVarHolder()->add($vars);

        return afListRenderer::renderList($request, $module, $action, $vars);
    }
}
