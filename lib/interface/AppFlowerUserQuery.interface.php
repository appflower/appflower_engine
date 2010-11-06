<?php
/**
 * Implementers of this interface should provide gateway to application specific user storage
 *
 * @author lukas
 */
interface AppFlowerUserQuery {
    /**
     * @return AppFlowerUser
     */
    function findOneByUsername($username);
}
?>
