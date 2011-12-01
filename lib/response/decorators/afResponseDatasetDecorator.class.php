<?php 
/**
 * Dataset response decorator
 *
 * @package appflower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afResponseDatasetDecorator extends afResponseDecoratorBase
{
    /**
     * Identificator of parameter
     */
    const IDENTIFICATOR = 'dataset';
    
    /**
     * Success dataset
     */
    private $_dataset;
    
    public function __construct(afResponse $response, array $dataset = array())
    {
        $this->_dataset = $dataset;
        
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
        $this->addParameter(self::IDENTIFICATOR, $this->_dataset);
        
        return parent::getParameters();
    }
    
}
