<?php 
/**
 * Query response decorator
 *
 * @package appflower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afResponseQueryDecorator extends afResponseDecoratorBase
{
    /**
     * Identificator of parameter
     */
    const IDENTIFICATOR = 'query';
    
    /**
     * Success query
     */
    private $_query;
    
    public function __construct(afResponse $response, $query)
    {
        $this->_query = $query;
        
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
        $this->addParameter(self::IDENTIFICATOR, $this->_query);
        
        return parent::getParameters();
    }
    
}
