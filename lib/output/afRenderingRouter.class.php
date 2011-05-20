<?php

class afRenderingRouter {
    public function __construct(sfAction $actionInstance)
    {
        $this->request = $actionInstance->getRequest();
        $this->module  = $actionInstance->getModuleName();
        $this->action  = $actionInstance->getActionName();
        $this->actionVars = $actionInstance->getVarHolder()->getAll();
    }
    
    public function render() {
        $doc = $this->readWidgetConfig();
        $view = $this->wrapDoc($doc);
        return $this->renderContent($view);
    }
    
    protected function readWidgetConfig()
    {
        return afConfigUtils::getDoc($this->module, $this->action);
    }
    
    protected function wrapDoc($doc)
    {
        return afDomAccess::wrap($doc, 'view', new afVarScope($this->actionVars));
    }
    
    protected function renderContent($view)
    {
        $viewType = $view->get('@type');
        if ($viewType === 'list') {
            return afListRenderer::renderList($this->request, $this->module, $this->action,
                $view);
        } elseif ($viewType === 'edit' || $viewType === 'show') {
            $format = $this->request->getParameter('af_format');
            if ($format === 'pdf') {
                return afEditShowRenderer::renderEditShow(
                    $this->request, $this->module, $this->action, $view);
            } elseif ($viewType === 'edit') {
                return afEditJsonRenderer::renderEdit(
                    $this->request, $this->module, $this->action, $view);
            }
        } elseif ($viewType === 'html') {
        	$format = $this->request->getParameter('af_format');
            if ($format === 'pdf') {
            	return afHtmlRenderer::renderHtml($this->actionVars,$this->module,$this->action,$view);
            }
        }

        throw new XmlParserException(
            'Unsupported view type for af_format rendering: '.$viewType);
    }
}

