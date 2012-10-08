<?php
/**
 * Builder helper parser 
 *
 * @package appFlower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afExtjsBuilderParser
{
    /**
     * Helper file name
     */
    const HELPER_FILE = 'helper.yml';
    
    /**
     * Attributes identificator
     */
    const ATTRIBUTES = 'attributes';
    
    /**
     * Childs identificator
     */
    const CHILDREN = 'children';
    
    /**
     * Helper file path
     *
     * @var string
     */
    protected $path;
    
    /**
     * Parsed definition
     *
     * @var array
     */
    protected $definition;
    
    /**
     * Private constructor
     */
    private function __construct() {}
    
    /**
     * Fabric method creator
     *
     * @param string $path 
     * @return afExtjsBuilderParser
     * @author Sergey Startsev
     */
    static public function create($path = '')
    {
        $instance = new self;
        $instance->setPath($path);
        
        return $instance;
    }
    
    /**
     * Getting attributes from definition
     *
     * @param Array $def 
     * @return array
     * @author Sergey Startsev
     */
    static public function getAttributes(Array $def, Array $default = array())
    {
        return (array_key_exists(self::ATTRIBUTES, $def)) ? $def[self::ATTRIBUTES] : $default;
    }
    
    /**
     * Getting childs from definition
     *
     * @param Array $def 
     * @return array
     * @author Sergey Startsev
     */
    static public function getChildren(Array $def)
    {
        return (array_key_exists(self::CHILDREN, $def)) ? $def[self::CHILDREN] : array();
    }
    
    /**
     * Getting helper file path
     *
     * @param string $place 
     * @param string $place_type 
     * @return string
     * @author Sergey Startsev
     */
    static public function getHelperPath($place = 'frontend', $place_type = 'app')
    {
        return sfConfig::get("sf_{$place_type}s_dir") . "/{$place}/config/" . self::HELPER_FILE;
    }
    
    /**
     * Parse definition process
     *
     * @return afExtjsBuilderParser
     * @author Sergey Startsev
     */
    public function parse()
    {
        $content = preg_replace(array('/\<\?php/','/\<\?/','/\?\>/'), array('','',''), file_get_contents($this->getPath()));
        
        $this->definition = (array) sfYaml::load($content);
        
        return $this;
    }
    
    /**
     * Set path that will be parsed
     *
     * @param string $path 
     * @return afExtjsBuilderParser
     * @author Sergey Startsev
     */
    public function setPath($path)
    {
        $this->path = $path;
        
        return $this;
    }
    
    /**
     * Getting path
     *
     * @return string
     * @author Sergey Startsev
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Getting area from parsed definition
     *
     * @param string $key 
     * @return array
     * @author Sergey Startsev
     */
    public function get($key = '')
    {
        if (empty($key)) return $this->definition;
        
        return (array_key_exists($key, $this->definition)) ? (array) $this->definition[$key] : array();
    }
    
}
