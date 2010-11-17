<?php

class afConfigUtils {
    /**
     * Returns the path to the $module/config/$action.xml
     */
    public static function getPath($module, $action) {
        $context = sfContext::getInstance();

        $root = sfConfig::get('sf_root_dir');
        $application = $context->getConfiguration()->getApplication();
        $modulePath = self::getModulePath($module);
        $path = "$modulePath/config/$action.xml";
        
        if(!file_exists($path)) {
            $path = "$root/apps/$application/config/pages/$action.xml";
        }
        
     	if(!file_exists($path)) {
            $path =  "$root/plugins/appFlowerPlugin/config/pages/$action.xml";
        }
        
        
        return $path;
    }

    private static function getActionsPath($moduleName) {
        $path = self::getModulePath($moduleName);
        $path .= "/actions/actions.class.php";

        if(!file_exists($path)) {
            throw new XmlParserException(
                sprintf('No such module actions: %s', $moduleName));
        }

        return $path;
    }

    /**
     * Returns directory path for given module
     * Also looks in plugins directory
     *
     * @param string $module
     * @return string Path to plugin "base" directory
     */
    private static function getModulePath($moduleName)
    {
        $context = sfContext::getInstance();
        $dirs = $context->getConfiguration()->getControllerDirs($moduleName);
        foreach ($dirs as $dir => $checkEnabled)
        {
          $module_file = $dir.'/actions.class.php';
          if (is_readable($module_file)) {
              return dirname($dir);
          }
        }

        return null;
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
