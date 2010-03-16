<?php
/**
 * Define a custom exception class
 */
class XmlValidatorException extends Exception
{
	
    public function __construct($message, $code = 0,$cli = false) {
    	
		if(is_array($message)) {
			$message = $this->constructXmlError($message,$cli);
		}
		
		parent::__construct($message, $code);
    }

	private function constructXmlError($errors, $cli = false) {
	
		$sep = ($cli) ? "\n" : "<br>";
		$msg = ($cli) ? "" : ("One or more errors have been found in configuration file: ".$errors[0]->file).$sep.$sep;
		
		foreach($errors as $error) {
			$msg .= "At line: ".$error->line.$sep."Error: ".$error->message.$sep.$sep;
		}
		
		return $msg;
		
	}

}

?>