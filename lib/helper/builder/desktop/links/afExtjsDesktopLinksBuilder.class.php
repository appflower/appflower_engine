<?php
/**
 * Desktop links builder class
 *
 * @package appFlower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afExtjsDesktopLinksBuilder extends afBaseExtjsBuilder
{
    /**
     * Menu identificator in definition
     */
    const LINKS_IDENTIFICATOR = 'links';
    
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
        
        if (!file_exists($path)) throw new afExtjsDesktopLinksBuilderException("Helper file '{$path}' doesn't exists");
        
        $instance->setDefinition(afExtjsBuilderParser::create($path)->parse()->get(self::LINKS_IDENTIFICATOR));
        $instance->setBuildedInstance(new afExtjsDesktopLinks());
        
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
        $this->processTools();
        
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
        foreach ($this->getDefinition() as $link) {
            $this->getBuildedInstance()->addLink($link);
        }
    }
    
}
