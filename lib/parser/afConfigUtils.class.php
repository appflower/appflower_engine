<?php

class afConfigUtils {
    /**
     * Returns the path to the $module/config/$action.xml
     */
    public static function getPath($module, $action) {
        $context = sfContext::getInstance();
        $root = sfConfig::get('sf_root_dir');
        $application = $context->getConfiguration()->getApplication();
        $path = "$root/apps/$application/modules/$module/config/$action.xml";
        if(!file_exists($path)) {
            $path = "$root/plugins/appFlowerPlugin/modules/$module/config/$action.xml";
        }
        
        if(!file_exists($path)) {
            $path = "$root/apps/$application/config/pages/$action.xml";
        }
        
     	if(!file_exists($path)) {
            $path =  "$root/plugins/appFlowerPlugin/config/pages/$action.xml";
        }
        
        
        return $path;
    }

    private static function getActionsPath($module) {
        $context = sfContext::getInstance();
        $root = sfConfig::get('sf_root_dir');
        $application = $context->getConfiguration()->getApplication();
        $path = "$root/apps/$application/modules/$module/actions/actions.class.php";
        if(!file_exists($path)) {
            $path = "$root/plugins/appFlowerPlugin/modules/$module/actions/actions.class.php";
        }

        if(!file_exists($path)) {
            throw new XmlParserException(
                sprintf('No such module actions: %s', $module));
        }

        return $path;
    }

    /**
     * Returns the XML config DOM document.
     */
    public static function getDoc($module, $action) {
        $path = self::getPath($module, $action);
       	if(file_exists($path)) {
        	$doc = new DOMDocument();
	        $doc->load($path);
	        return $doc;	
        } else {
            throw new XmlParserException(
                sprintf('No such XML config: %s/%s', $module, $action));
        }
       
    }

    /**
     * Returns the XML config DOM document or null.
     */
    public static function getOptionalDoc($module, $action) {
        $path = self::getPath($module, $action);
        if(file_exists($path)) {
            $doc = new DOMDocument();
            $doc->load($path);
            return $doc;
        }
        return null;
    }

    /**
     * Executes the given action to get its variables
     * for placeholders.
     */
    public static function getConfigVars($module, $action, $request) {
    	
    	$context = sfContext::getInstance();
        $moduleClass = $module.'Actions';
        if(!class_exists($moduleClass)) {
            require_once(self::getActionsPath($module));
        }

        $instance = new $moduleClass($context, $module, $action);
        $instance->isPageComponent = true;
        $instance->preExecute();
        $instance->execute($request);
        $instance->postExecute();

        return $instance->getVarHolder()->getAll();
    }
}
