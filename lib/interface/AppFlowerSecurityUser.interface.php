<?php
/**
 * Application "myUser" class should implement this interface
 *
 * @author lukas
 */
interface AppFlowerSecurityUser {

    /**
     * Should return currently logged user representation storage object
     *
     * @return AppFlowerUser
     */
    function getAppFlowerUser();
}
?>
