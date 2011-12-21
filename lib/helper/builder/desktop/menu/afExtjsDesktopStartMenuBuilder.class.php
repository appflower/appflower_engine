<?php
/**
 * Desktop start menu builder
 *
 * @package appFlower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afExtjsDesktopStartMenuBuilder extends afBaseExtjsBuilder
{
    /**
     * Menu identificator in definition
     */
    const MENU_IDENTIFICATOR = 'menu';
    
    /**
     * Item type identificator in item attributes
     */
    const ITEM_TYPE = 'type';
    
    /**
     * Main identificator
     */
    const MAIN = 'main';
    
    /**
     * Tools identificator
     */
    const TOOLS = 'tools';
    
    /**
     * Private constructor
     */
    private function __construct() {}
    
    /**
     * Fabric method creator
     *
     * @param string $place 
     * @param string $place_type 
     * @return afExtjsDesktopStartMenuBuilder
     * @author Sergey Startsev
     */
    static public function create($place = 'frontend', $place_type = 'app')
    {
        $instance = new self;
        
        $path = afExtjsBuilderParser::getHelperPath($place, $place_type);
        
        if (!file_exists($path)) throw new afExtjsDesktopStartMenuBuilderException("Helper file '{$path}' doesn't exists");
        
        $instance->setDefinition(afExtjsBuilderParser::create($path)->parse()->get(self::MENU_IDENTIFICATOR));
        $instance->setBuildedInstance(new afExtjsStartMenu(afExtjsBuilderParser::getAttributes($instance->getDefinition(), array('title' => 'App Flower'))));
        
        return $instance;
    }
    
    /**
     * Getting main from definition
     *
     * @param Array $def 
     * @return array
     * @author Sergey Startsev
     */
    static public function getMain(Array $def)
    {
        return (array_key_exists(self::MAIN, $def)) ? $def[self::MAIN] : array();
    }
    
    /**
     * Getting tools from definition
     *
     * @param Array $def 
     * @return array
     * @author Sergey Startsev
     */
    static public function getTools(Array $def)
    {
        return (array_key_exists(self::TOOLS, $def)) ? $def[self::TOOLS] : array();
    }
    
    /**
     * Processing - building menu
     *
     * @return afExtjsDesktopStartMenuBuilder
     * @author Sergey Startsev
     */
    public function process()
    {
        $this->processTools();
        $this->processItems($this->getBuildedInstance(), self::getMain($this->getDefinition()));
        
        return $this;
    }
    
    /**
     * Process tool area
     *
     * @return void
     * @author Sergey Startsev
     */
    private function processTools()
    {
        foreach ((array) self::getTools($this->getDefinition()) as $tool) {
            $this->getBuildedInstance()->addTool($tool);
        }
    }
    
    /**
     * Process with items
     *
     * @param afExtjsStartMenu $glue_instance 
     * @param Array $definition 
     * @return void
     * @author Sergey Startsev
     */
    private function processItems(afExtjsStartMenu $glue_instance, Array $definition)
    {
        foreach ((array) $definition as $item_name => $item) {
            $this->getItemInstance($glue_instance, $item);
        }
    }
    
    /**
     * Process with single item
     *
     * @param afExtjsStartMenu $glue_instance 
     * @param Array $definition 
     * @return void
     * @author Sergey Startsev
     */
    private function getItemInstance(afExtjsStartMenu $glue_instance, Array $definition)
    {
        $attributes = afExtjsBuilderParser::getAttributes($definition);
        $children = afExtjsBuilderParser::getChildren($definition);
        
        $type = 'item';
        if (array_key_exists(self::ITEM_TYPE, $attributes)) $type = $attributes[self::ITEM_TYPE];
        $type = ucfirst($type);
        
        $reflection = new ReflectionClass("afExtjsStartMenu{$type}");
        
        $instance = $reflection->newInstance($glue_instance, $attributes);
        
        if (!empty($children)) {
            $menu_reflection = new ReflectionClass("afExtjsStartMenu");
            $menu_instance = $menu_reflection->newInstance($instance);
            $this->processItems($menu_instance, $children);
            $menu_instance->end();
        }
        
        $instance->end();
    }
    
}
