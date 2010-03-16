<?php
/**
 * Define a custom exception class
 */
class XmlParserException extends Exception
{
	
    public function __construct($message, $code = 0) {
		
		parent::__construct($message, $code);
    }

}

?>