<?php 
/**
 * Message response decorator
 *
 * @package appflower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afResponseMessageDecorator extends afResponseDecoratorBase
{
    /**
     * Identificator of parameter
     */
    const IDENTIFICATOR = 'message';
    
    /**
     * Implemented message
     */
    private $_message;
    
    public function __construct(afResponse $response, $message)
    {
        $this->_message = $message;
        
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
        $this->addParameter(self::IDENTIFICATOR, $this->_message);
        
        return parent::getParameters();
    }
    
}
