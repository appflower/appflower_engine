<?php
/**
 * Desktop start menu builder
 *
 * @package appFlower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afExtjsDesktopStartMenuBuilder
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
     * Menu definition
     *
     * @var array
     */
    protected $definition;
    
    /**
     * Menu instance
     *
     * @var afExtjsStartMenu
     */
    protected $menu_instance;
    
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
        
        $path = sfConfig::get("sf_{$place_type}s_dir") . "/{$place}/config/" . afExtjsBuilderParser::HELPER_FILE;
        
        if (!file_exists($path)) throw new afExtjsDesktopStartMenuBuilderException("Helper file '{$path}' doesn't exists");
        
        $instance->definition = afExtjsBuilderParser::create($path)->parse()->get(self::MENU_IDENTIFICATOR);
        $instance->menu_instance = new afExtjsStartMenu(afExtjsBuilderParser::getAttributes($instance->definition));
        
        return $instance;
    }
    
    /**
     * Processing - building menu
     *
     * @return afExtjsDesktopStartMenuBuilder
     * @author Sergey Startsev
     */
    public function process()
    {
        $this->getItems($this->menu_instance, $this->definition);
        
        return $this;
    }
    
    /**
     * Settign menu instance
     *
     * @param afExtjsStartMenu $menu 
     * @return void
     * @author Sergey Startsev
     */
    public function setMenuInstance(afExtjsStartMenu $menu)
    {
        $this->menu_instance = $menu;
    }
    
    /**
     * Getting menu instance
     *
     * @return afExtjsStartMenu
     * @author Sergey Startsev
     */
    public function getMenuInstance()
    {
        return $this->menu_instance;
    }
    
    /**
     * Process with items
     *
     * @param afExtjsStartMenu $glue_instance 
     * @param Array $definition 
     * @return void
     * @author Sergey Startsev
     */
    private function getItems(afExtjsStartMenu $glue_instance, Array $definition)
    {
        foreach (afExtjsBuilderParser::getCleanDefinition($definition) as $item_name => $item) {
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
        $childs = afExtjsBuilderParser::getChilds($definition);
        
        $type = 'item';
        if (array_key_exists(self::ITEM_TYPE, $attributes)) $type = $attributes[self::ITEM_TYPE];
        $type = ucfirst($type);
        
        $clean_definition = afExtjsBuilderParser::getCleanDefinition($definition);
        
        $reflection = new ReflectionClass("afExtjsStartMenu{$type}");
        $instance = $reflection->newInstance($glue_instance, $clean_definition);
        
        if (!empty($childs)) {
            $menu_reflection = new ReflectionClass("afExtjsStartMenu");
            $menu_instance = $menu_reflection->newInstance($instance);
            $this->getItems($menu_instance, $childs);
            $menu_instance->end();
        }
        
        $instance->end();
    }
    
}
