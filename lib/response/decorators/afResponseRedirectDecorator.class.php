<?php 
/**
 * Redirect response decorator
 *
 * @package appflower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afResponseRedirectDecorator extends afResponseDecoratorBase
{
    /**
     * Identificator of parameter
     */
    const IDENTIFICATOR = 'redirect';
    
    /**
     * Path for redirect
     */
    private $_path;
    
    public function __construct(afResponse $response, $path)
    {
        $this->_path = $path;
        
        parent::__construct($response);
    }
    
    /**
     * Reload getting parameters
     *
     * @return array
     * @author Sergey Startsev
     */
    public function getParameters()
    {
        $this->addParameter(self::IDENTIFICATOR, $this->_path);
        
        return parent::getParameters();
    }
    
}
