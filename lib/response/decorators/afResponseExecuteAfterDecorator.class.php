<?php 
/**
 * Execute after response decorator
 *
 * @package appflower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afResponseExecuteAfterDecorator extends afResponseDecoratorBase
{
    /**
     * Identificator of parameter
     */
    const IDENTIFICATOR = 'executeAfter';
    
    /**
     * Code to execute
     */
    private $_code;
    
    public function __construct(afResponse $response, $code)
    {
        $this->_code = $code;
        
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
        $this->addParameter(self::IDENTIFICATOR, $this->_code);
        
        return parent::getParameters();
    }
    
}
