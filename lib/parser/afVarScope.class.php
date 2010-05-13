<?php

/**
 * A scope to replace placeholders with values.
 */
class afVarScope {
    private
        $vars;

    public function __construct($vars) {
        $this->vars = $vars;
    }

    /**
     * Replaces all {placholder}s in the given text.
     */
    public function interpret($text) {
        $callback = array($this, '_replace_callback');
        return preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', 
            $callback, $text);
    }

    public function _replace_callback($matches) {
        $name = $matches[1];
        if(isset($this->vars[$name])) {
            return $this->vars[$name];
        }
        throw new XmlParserException(sprintf(
            'Variable %s cannot be found in the attribute holder!',
            $matches[0]));
    }
}

