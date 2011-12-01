<?php 
/**
 * Data response decorator
 *
 * @package appflower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afResponseDataDecorator extends afResponseDecoratorBase
{
    /**
     * Identificator of parameter
     */
    const IDENTIFICATOR_DATA = 'data';
    
    /**
     * Identificator of meta parameter
     */
    const IDENTIFICATOR_META = 'meta';
    
    /**
     * Identificator of total parameter
     */
    const IDENTIFICATOR_TOTAL = 'total';
    
    /**
     * Success data
     */
    private $_data;
    
    /**
     * Meta parameter
     */
    private $_meta;
    
    /**
     * Total parameter
     */
    private $_total;
    
    public function __construct(afResponse $response, $meta, $data, $total)
    {
        $this->_meta = $meta;
        $this->_data = $data;
        $this->_total = $total;
        
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
        $this->addParameter(self::IDENTIFICATOR_META, $this->_meta);
        $this->addParameter(self::IDENTIFICATOR_DATA, $this->_data);
        $this->addParameter(self::IDENTIFICATOR_TOTAL, $this->_total);
        
        return parent::getParameters();
    }
    
}
