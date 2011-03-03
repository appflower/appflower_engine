<?php

class afOutput {
    public static function asCsv($row) {
        $cells = array();
        foreach($row as $value) {
            $cells[] = '"'.str_replace('"', '""', $value).'"';
        }
        return implode(',', $cells)."\n";
    }
    

    /**
     * Adds the text to the current response.
     */
    public static function renderText($text) {
        $response = sfContext::getInstance()->getResponse();
        $response->setContent($response->getContent().$text);
        return sfView::NONE;
    }


    /**
     * Closes a write lock opened by a session_start().
     * The lock should not be hold while waiting.
     */
    public static function closeSessionWriteLock()
    {
        $user = sfContext::getInstance()->getUser();
        if($user) {
            $user->shutdown();
        }
        session_write_close();
        session_write_close();
    }
}
