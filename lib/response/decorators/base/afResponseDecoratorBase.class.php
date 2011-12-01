<?php 
/**
 * Response decorator base class 
 *
 * @package appflower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
abstract class afResponseDecoratorBase extends afResponse
{
    /**
     * Response instance
     */
    protected $_response;
    
    public function __construct(afResponse $response) {
        $this->_response = $response;
    }
    
    /**
     * Adding parameter to resutling response
     *
     * @param string $alias 
     * @param string $value 
     * @author Sergey Startsev
     */
    public function addParameter($name, $value)
    {
        $this->_response->addParameter($name, $value);
    }
    
    /**
     * Getting parameter from defined response
     *
     * @param string $alias 
     * @return string
     * @author Sergey Startsev
     */
    public function getParameter($name)
    {
        // init
        $this->getParameters();
        
        return $this->_response->getParameter($name);
    }
    
    /**
     * Checking exists parameter or not
     *
     * @param string $name 
     * @return boolean
     * @author Sergey Startsev
     */
    public function hasParameter($name)
    {
        // init
        $this->getParameters();
        
        return $this->_response->hasParameter($name);
    }
    
    /**
     * Getting defined parameters
     *
     * @return array
     * @author Sergey Startsev
     */
    public function getParameters()
    {
        return $this->_response->getParameters();
    }
    
}
