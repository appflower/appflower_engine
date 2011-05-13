<?php
/**
 * AppFlower user object should implement this interface
 *
 * @author lukas
 */
interface AppFlowerUser {
    function getUsername();
    function getFullname();
    function isWidgetHelpEnabled();
    function isAnonymous();
    function getId();
    function getPassword();
}
?>
