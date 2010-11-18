<?php

/**
 * This class works in context of symfony module.
 * When you create instance of afConfigUtils for some module you can then easy
 * fetch paths for any config file or action file within that module.
 *
 * All possible places for module file are searched.
 * Additionally when you will look for conifig file config/pages from application and AF plugin will be searched too
 *
 * There are some static methods here - we should move them somewhere probably in the future.
 */
class afConfigUtils {

    private $moduleName;

    function __construct($moduleName)
    {
        $this->moduleName = $moduleName;

        $this->findModulePaths();
    }

    private function getActionsPath() {
        $file = "actions.class.php";
        $path = $this->getActionFilePath($file);

        if(!$path) {
            throw new XmlParserException(
                sprintf('No such module actions: %s', $this->moduleName));
        }

        return $path;
    }
    
    /**
     * Fetches and remembers in a property all possible module directories
     */
    private function findModulePaths()
    {
        $context = sfContext::getInstance();
        $dirsAsKeys = $context->getConfiguration()->getControllerDirs($this->moduleName);
        $dirs = array();
        foreach ($dirsAsKeys as $dir => $junk) {
            $dirs[] = dirname($dir);
        }
        $this->modulePaths = $dirs;
    }

    function getConfigFilePath($fileName)
    {
        $path = $this->getFilePath('config/'.$fileName);
        if ($path) {
            return $path;
        }


        $additionalPaths = array();
        $context = sfContext::getInstance();
        $root = sfConfig::get('sf_root_dir');
        $application = $context->getConfiguration()->getApplication();

        $additionalPaths[] = "$root/apps/$application/config/pages";
        $additionalPaths[] = "$root/plugins/appFlowerPlugin/config/pages";

        return $this->getFilePath($fileName, $additionalPaths);
    }

    function getActionFilePath($fileName)
    {
        return $this->getFilePath('actions/'.$fileName);
    }

    private function getFilePath($filePath, $customPaths = array())
    {
        if (count($customPaths) > 0) {
            $paths = $customPaths;
        } else {
            $paths = $this->modulePaths;
        }

        foreach ($paths as $path) {
            $fullFilePath = $path.'/'.$filePath;
            if (is_readable($fullFilePath)) {
                return $fullFilePath;
            }
        }
    }

    /**
     * Returns the XML config DOM document.
     */
    public static function getDoc($module, $action) {
        $afCU = new afConfigUtils($module);
        $path = $afCU->getConfigFilePath("{$action}.xml");
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
        $afCU = new afConfigUtils($module);
        $path = $afCU->getConfigFilePath("{$action}.xml");
        if($path) {
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
            $afCU = new afConfigUtils($module);
            require_once($afCU->getActionsPath());
        }

        $instance = new $moduleClass($context, $module, $action);
        $instance->isPageComponent = true;
        $instance->preExecute();
        $instance->execute($request);
        $instance->postExecute();

        return $instance->getVarHolder()->getAll();
    }

}