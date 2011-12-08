<?php
/**
 * Base helper builder class
 *
 * @package appFlower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
abstract class afBaseExtjsBuilder
{
    /**
     * Helper definition
     *
     * @var array
     */
    private $definition;
    
    /**
     * Builded instance
     *
     * @var object
     */
    private $builded_instance;
    
    /**
     * Getting builded instance
     *
     * @return object
     * @author Sergey Startsev
     */
    public function getBuildedInstance()
    {
        return $this->builded_instance;
    }
    
    /**
     * Setting builded instance
     *
     * @param object $instance 
     * @return void
     * @author Sergey Startsev
     */
    public function setBuildedInstance($instance)
    {
        $this->builded_instance = $instance;
    }
    
    /**
     * Getting definition
     *
     * @return array
     * @author Sergey Startsev
     */
    protected function getDefinition()
    {
        return $this->definition;
    }
    
    /**
     * Setting definition
     *
     * @param Array $definition 
     * @return void
     * @author Sergey Startsev
     */
    protected function setDefinition(Array $definition)
    {
        $this->definition = $definition;
    }
    
    /**
     * Main process functionality
     *
     * @return void
     * @author Sergey Startsev
     */
    abstract public function process();
    
}
