<?php

/**
 * This class works in context of symfony module.
 * When you create instance of afConfigUtils for some module you can then easy
 * fetch paths for any config file or action file within that module.
 *
 * All possible places for module file are searched.
 * Additionally when you will look for conifig file config/pages from application and AF plugin will be searched too
 *
 * TODO:
 * There are some static methods here - we should move them somewhere probably in the future.
 */
class afConfigUtils {

    private $moduleName;
    /**
     * @var sfApplicationConfiguration
     */
    private $appConf;

    /**
     * If you pass $appConf then afConfigUtils will work in context of passed project/application
     */
    function __construct($moduleName, sfApplicationConfiguration $appConf = null)
    {
        $this->moduleName = $moduleName;
        if ($appConf) {
            $this->appConf = $appConf;
        } else {
            $this->appConf = sfContext::getInstance()->getConfiguration();
        }

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
        $dirsAsKeys = $this->appConf->getControllerDirs($this->moduleName);
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
        $application = $this->appConf->getApplication();

        $rootDir = $this->appConf->getRootDir();
        $additionalPaths[] = "{$rootDir}/apps/$application/config/pages";
        $additionalPaths[] = "{$rootDir}/plugins/appFlowerPlugin/config/pages";

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
     * You can use this method to generate file path for non existent widget
     * We are assuming that we'll want to create given file so we'll try to create needed directories
     */
    function generateConfigFilePath($fileName)
    {
        $filePath = 'config/'.$fileName;
        $paths = $this->modulePaths;

        foreach ($paths as $path) {
            $fullFilePath = $path.'/'.$filePath;
            $fullDirPath = dirname($fullFilePath);
            if (!file_exists($fullDirPath)) {
                mkdir($fullDirPath, 0777, true);
            }
            return $fullFilePath;
        }
    }

    function generateActionFilePath($actionName)
    {
        $filePath = "actions/{$actionName}Action.class.php";
        $paths = $this->modulePaths;

        foreach ($paths as $path) {
            $fullFilePath = $path.'/'.$filePath;
            return $fullFilePath;
        }
    }

    /**
     * checks if passed action is defined either in actions.class.php file or in
     * dedicated action file
     *
     * @param <type> $actionName
     */
    function isActionDefined($actionName) {
        $actionsFilePath = $this->getActionsPath();
        $actionsFile = file_get_contents($actionsFilePath);
        $found = is_numeric(strpos($actionsFile, "{$actionName}action"));
        if ($found) {
            return true;
        }

        $actionFilePath = $this->generateActionFilePath($actionName);

        if (file_exists($actionFilePath)) {
            return true;
        }

        return false;
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
        $instance = self::getActionInstance($module, $action);
        
        $instance->isPageComponent = true;
        $instance->preExecute();
        $instance->execute($request);
        $instance->postExecute();

        return $instance->getVarHolder()->getAll();
    }
    
    /**
     * Returns given module/action instance
     * 
     * @param type $module
     * @param type $action
     * @return sfAction
     */
    public static function getActionInstance($module, $action) {
    	$context = sfContext::getInstance();
        $controller = $context->getController();
        return $controller->getAction($module, $action);
    }

}