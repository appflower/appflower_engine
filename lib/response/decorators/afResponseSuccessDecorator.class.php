<?php 
/**
 * Success response decorator
 *
 * @package appflower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afResponseSuccessDecorator extends afResponseDecoratorBase
{
    /**
     * Identificator of parameter
     */
    const IDENTIFICATOR = 'success';
    
    /**
     * Success type
     */
    private $_type;
    
    public function __construct(afResponse $response, $type = true)
    {
        $this->_type = (bool)$type;
        
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
        $this->addParameter(self::IDENTIFICATOR, $this->_type);
        
        return parent::getParameters();
    }
    
}
