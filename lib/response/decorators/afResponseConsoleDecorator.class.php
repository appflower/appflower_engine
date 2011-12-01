<?php 
/**
 * Console response decorator
 *
 * @package appflower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afResponseConsoleDecorator extends afResponseDecoratorBase
{
    /**
     * Identificator of parameter
     */
    const IDENTIFICATOR = 'console';
    
    /**
     * Success content
     */
    private $_content;
    
    public function __construct(afResponse $response, $content)
    {
        $this->_content = $content;
        
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
        $this->addParameter(self::IDENTIFICATOR, $this->_content);
        
        return parent::getParameters();
    }
    
}
