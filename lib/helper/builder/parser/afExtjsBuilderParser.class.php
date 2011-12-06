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
    static public function getAttributes(Array $def)
    {
        return (array_key_exists(self::ATTRIBUTES, $def)) ? $def[self::ATTRIBUTES] : array();
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
     * Parse definition process
     *
     * @return afExtjsBuilderParser
     * @author Sergey Startsev
     */
    public function parse()
    {
        $this->definition = sfYaml::load($this->getPath());
        
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
        
        return (array_key_exists($key, $this->definition)) ? $this->definition[$key] : array();
    }
    
}
