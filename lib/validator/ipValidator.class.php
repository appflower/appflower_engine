<?php
class ipValidator extends sfValidator
{
    /**
     * Accepts a valid IP address or hostname.
     */
    public function execute(&$value, &$error)
    {
        if (preg_match('/^[0-9]+(\.[0-9]+){3}$/', $value) === 1)
        {
            return true;
        }

        // The value is assumed to be a hostname.
        $ip = gethostbyname($value);
        if ($ip === $value)
        {
            $error = $this->getParameter('error', 'Invalid IP address!');
            return false;
        }

        return true;
    }
}
