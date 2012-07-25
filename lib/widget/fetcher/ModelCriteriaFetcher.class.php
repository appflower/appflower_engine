<?php
/**
 * Class with some static methods that will be able to give values
 * for choices/combo widgets on editing foreign key fields
 *
 * @author Łukasz Wojciechowski <luwo@appflower.com>
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
class ModelCriteriaFetcher
{
    /**
     * Method builds array with ID's as keys and __toString() representation as value
     * Used in combo widgets as possible choices
     * 
     * @author Łukasz Wojciechowski
     * @author Sergey Startsev
     */
    static public function getDataForComboWidget($modelName, $params = '', $like = '', $lookupIn = '')
    {
        $queryClass = "{$modelName}Query";
        $query = new $queryClass('propel', $modelName);
        
        if (is_array($params)) {            
            foreach ($params as $def) {
                list($method, $value) = explode(':', $def);
                call_user_func(array($query, $method), $value);
            }
        }
        
        if (!empty($like) && !empty($lookupIn)) {
            $query->where("{$modelName}.{$lookupIn} like ?", "{$like}%");
        }
        
        /* @var $query ModelCriteria */
        $collection = $query->find();
        
        if (method_exists($modelName, '__toString')) {
            $response = $collection->toKeyValue('Id');
        } else {
            $response = $collection->toKeyValue('Id', 'Id');
        }
        
        if (!empty($like)) {
            $likeResponse = array();
            foreach ($response as $key => $value) {
                $likeResponse[] = array('key' => $key, 'value' => $value);
            }
            $response = $likeResponse;
        }
        
        return $response;
    }
    
    /**
     * Method just returns empty Criteria object so it can be used in list widget to fetch data
     * if defined more than one  parameter, for example getDataForList($modelName, $fieldName, $fieldValue)
     * then this criteria will be added to query class 
     * 
     * @example getDataForList($modelName, $fieldName, $fieldValue),
     *          getDataForList($modelName, $fieldName1, $fieldValue1, $fieldName2, $fieldValue2)
     *              where - fieldName in table structure style, like field_name
     *
     * @author Łukasz Wojciechowski
     * @author Sergey Startsev
     */
    static public function getDataForList($modelName)
    {
        if (func_num_args() % 2 === 0) throw new sfException("not all params defined");
        
        $queryClass = "{$modelName}Query";
        $query = new $queryClass('propel', $modelName);
        
        // remove modelName from array of args
        $arguments = array_slice(func_get_args(), 1);
        if (!empty($arguments)) {
            $params = array();
            foreach ($arguments as $key => $value) {
                if ($key % 2 === 1) {
                    $query->where("{$modelName}." . sfInflector::camelize($field_name) . " = ?", $value);
                    continue;
                }
                $field_name = $value;
            }
        }
        
        return $query;
    }
    
    /**
     * Getting data for double combo box widget
     *
     * @param string $source_model 
     * @param string $glue_field_name 
     * @param string $glue_field_value 
     * @param string $middle_model 
     * @param string $middle_model_field 
     * @return array
     * 
     * @example 
     * 
     * <i:field label="Genres" name="genre_id" state="editable" style="" type="doublemulticombo">
            <i:value type="orm">
                <i:class>ModelCriteriaFetcher</i:class>
                <i:method name="getDataForDoubleComboWidget">
                    <i:param name="source_model">Genre</i:param>
                    <i:param name="glue_field_name">band_id</i:param>
                    <i:param name="glue_field_value">{id}</i:param>
                    <i:param name="middle_model">BandHasGenre</i:param>
                    <i:param name="middle_model_field">genre_id</i:param>
                </i:method>
            </i:value>
            <i:tooltip>Select the genres to be added to this new band.</i:tooltip>
            <i:help>The selected genres will be associated with this new band.</i:help>
            <i:validator name="immValidatorRequired"/>
        </i:field>
     * 
     * @author Sergey Startsev
     * @author Radu Topala <radu@appflower.com>
     */
    static public function getDataForDoubleComboWidget($source_model, $glue_field_name, $glue_field_value, $middle_model, $middle_model_field)
    {
        $options = $selected = array();
        
        $middleQuery = "{$middle_model}Query";
        $objects = $middleQuery::create()->where("{$middle_model}.". sfInflector::camelize($glue_field_name) .'=?', $glue_field_value)->leftJoin($source_model)->find();
        if ($objects != null) {
            foreach ($objects as $object) {
                $selected[$object->getByName($middle_model_field, BasePeer::TYPE_FIELDNAME)] = call_user_func(array($object, "get{$source_model}"))->__toString();
            }
        }
        
        $sourceQuery = "{$source_model}Query";
        foreach ($sourceQuery::create()->find() as $object) $options[$object->getId()] = $object->__toString();
        foreach ($options as $id => $label) if (isset($selected[$id])) unset($options[$id]);
        
        return array($options, $selected);
    }
    
    /**
     * Data needed for filters with foreign table
     *
     * @param string $model_name 
     * @return array
     * @author Sergey Startsev
     */
    static public function filterData($model_name)
    {
        $query = "{$model_name}Query";
        $objects = $query::create()->find();
        
        if ($objects != null) {

            $array = array();
            foreach ($objects as $object) {
                $array[$object->getId()] = $object->__toString();
            }

            return $array;
        } else {
            return array();
        }
    }
    
    /**
     * Configure file field method
     *
     * @see ../action/simpleWidgetEditAction.class.php
     * @return void
     * @author Sergey Startsev
     */
    static public function configureFileField() {}
    
}
