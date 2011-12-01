<?php 
/**
 * afResponse Helper class
 *
 * @package appflower
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class afResponseHelper extends afResponse
{
    /**
     * Getting instance
     *
     * @return afResponse
     * @author Sergey Startsev
     */
    public static function create() { return new self; }
    
}
