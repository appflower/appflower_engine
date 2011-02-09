<?php
/**
 * This class is needed only to provide static method that return Criteria object
 *
 * This is used as default callback for i:datasource of orm type.
 * So it is possible to define it in minimal form like:
 
   <i:datasource type="orm">
     <i:class>ProjectPeer</i:class>
   </i:datasource

 * @author lukas
 */
class afCriteriaCreator {
    static function createCriteria()
    {
        return new Criteria;
    }
}
?>
