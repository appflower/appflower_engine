<?php
/**
 * AppFlower user object should implement this interface
 *
 * @author lukas
 */
class AppFlowerAnonymousUser implements AppFlowerUser{

    function getUsername()
    {
        return 'anonymous';
    }

    function getFullName()
    {
        return 'anonymous';
    }

    function isWidgetHelpEnabled()
    {
        return true;
    }

    function isAnonymous()
    {
        return true;
    }

    function getId()
    {
        return null;
    }

    function getPassword()
    {
        return null;
    }
    
    function getTimezoneOffset() {
        return 0;
    }
}
?>
