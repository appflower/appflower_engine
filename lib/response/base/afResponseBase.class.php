<?php 
/**
 * Response base class
 *
 * @package appflower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
abstract class afResponseBase
{
    /**
     * Parameters
     */
    protected $_parameters = array();
    
    /**
     * Getting parameters
     *
     * @return mixied
     * @author Sergey Startsev
     */
    public function getParameters()
    {
        return $this->_parameters;
    }
    
    /**
     * Adding to response new 
     *
     * @param string $alias 
     * @param string $value 
     * @author Sergey Startsev
     */
    public function addParameter($name, $value)
    {
        $this->_parameters[$name] = $value;
    }
    
    /**
     * Getting assigned from response
     *
     * @param string $alias 
     * @return string
     * @author Sergey Startsev
     */
    public function getParameter($name)
    {
        return ($this->hasParameter($name)) ? $this->_parameters[$name] : null;
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
        return array_key_exists($name, $this->_parameters);
    }
    
}
