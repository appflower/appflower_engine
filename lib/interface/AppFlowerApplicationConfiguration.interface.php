<?php
/**
 * Application (frontend, backedn, whatever) or Project's configuration class should implement this interface
 *
 * @author lukas
 */
interface AppFlowerApplicationConfiguration {
    /**
     * Should return propel auto generated Query class for table where user is stored
     *
     * @return AppFlowerUserQuery
     */
    function getAppFlowerUserQuery();
}
?>
