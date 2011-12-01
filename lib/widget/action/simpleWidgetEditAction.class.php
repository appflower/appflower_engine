<?php
/**
 * This abstract class is used by AF Studio when new edit widget is created
 *
 * It guesses propel model object name and its coresponding form class from peer class
 * defined in widget xml datasource element
 *
 * It also dynamically reconfigures given model form object to use only fields
 * defined in widget xml config file
 * Validators are also replaced by sfValidatorPass
 * Basically I'm using form classes just to ease up filling propel objects with values from user
 *
 * @author Łukasz Wojciechowski <luwo@appflower.com>
 * @author Sergey Startsev <startsev.sergey@gmail.com>
 */
abstract class simpleWidgetEditAction extends sfAction
{
    /**
     * @var BaseObject
     */
    protected $object;
    
    /**
     * Widget uri
     *
     * @var string
     */
    protected $widgetUri;
    
    /**
     * @var BaseFormPropel
     */
    protected $form;
    
    /**
     * DOMDocument instance
     *
     * @var DOMDocument
     */
    protected $dom_xml;
    
    /**
     * DOMXPath instance 
     *
     * @var DOMXPath
     */
    protected $dom_xml_xpath;
    
    /**
     * Deprecated fields list, that not native and shouldn't be processed via generated form class
     *
     * @var array
     */
    protected $deprecated_field_types = array(
        'include',
        'file',
        'doublemulticombo',
    );
    
    /**
     * Pre-execute action - before every action
     *
     * @author Sergey Startsev
     */
    public function preExecute()
    {
        $module_name = $this->getModuleName();
        $action_name = $this->getActionName();
        
        // init widget uri
        $this->widgetUri = "{$module_name}/{$action_name}";
        
        // getting xml file path
        $xmlCU = new afConfigUtils($module_name);
        $xml_path = $xmlCU->getConfigFilePath($action_name.'.xml', true);
        
        // initialize dom document
        $this->dom_xml = new DOMDocument();
        $this->dom_xml->preserveWhiteSpace = false;
        $this->dom_xml->formatOutput = true;
        $this->dom_xml->load($xml_path);
        
        $this->dom_xml_xpath = new DOMXPath($this->dom_xml);
        
        // getting datasource class
        $peerClassName = $this->getDatasource();
        
        if (!empty($peerClassName) && class_exists($peerClassName)) {
            $modelClassName = constant("$peerClassName::OM_CLASS");
            $formClassName = "{$modelClassName}Form";
            
            $this->tryToLoadObjectFromRequest($peerClassName);
            
            if (!$this->object) {
                $this->createNewObject($modelClassName);
            }
            
            $this->createAndConfigureForm($formClassName);
        }
    }
    
    /**
     * Execute method reload
     *
     * @param string $request 
     * @return array
     * @author Łukasz Wojciechowski
     */
    public function execute($request)
    {
        if ($request->isMethod('post')) {
            if ($this->processPostData()) {
                $result = array(
                    'success' => true,
                    'message' => "Saved with success!",
                    'redirect' => $this->widgetUri . '?id=' . $this->object->getId()
                );
                
                return $result;
            }
        }
    }
    
    /**
     * Create and configure forn
     *
     * @param string $formClassName 
     * @author Łukasz Wojciechowski
     */
    private function createAndConfigureForm($formClassName)
    {
        $this->form = new $formClassName($this->object);
        $vs = $this->form->getValidatorSchema();
        foreach ($vs->getFields() as $fieldName => $validator) {
            $this->form->setValidator($fieldName, new sfValidatorPass());
        }
        
        if (isset($this->form['id'])) {
            unset($this->form['id']);
        }
        
        $formFieldNames = $this->getFieldNamesOfForm($this->form);
        $this->form->useFields($formFieldNames);
        
        // making form field default values available for widget XML config file placeholders
        foreach ($formFieldNames as $fieldName) {
            $this->$fieldName = $this->object->getByName($fieldName, BasePeer::TYPE_FIELDNAME);
        }
    }
    
    /**
     * Creating new object
     *
     * @param string $modelClassName 
     * @author Łukasz Wojciechowski
     */
    private function createNewObject($modelClassName)
    {
        $this->object = new $modelClassName;
        $this->id = '';
    }
    
    /**
     * Try to load object from request
     *
     * @param string $peerClassName 
     * @author Łukasz Wojciechowski
     */
    private function tryToLoadObjectFromRequest($peerClassName)
    {
        if ($this->getRequest()->hasParameter('id')) {
            $objectId = $this->getRequest()->getParameter('id');
            if ($objectId > 0) {
                $this->object = call_user_func("$peerClassName::retrieveByPK", $objectId);
                $this->id = $this->object->getPrimaryKey();
            }
        }
    }
    
    /**
     * Process post data
     *
     * @return boolean
     * @author Łukasz Wojciechowski
     */
    private function processPostData()
    {
        $formData = $this->getRequest()->getParameter('edit');
        $formData = $formData[0];

        $formData = $this->changeKeysForForeignFields($formData);
        $formData = $this->processMultipleRelations($formData);

        // filtered means that we are leaving only values for fields that exists in the form
        $formDataFiltered = array();
        foreach ($this->getFieldNamesOfForm($this->form) as $fieldName) {
            if (isset($formData[$fieldName])) {
                $formDataFiltered[$fieldName] = $formData[$fieldName];
            }
        }
        
        $this->form->bind($formDataFiltered);
        return $this->form->save();
    }
    
    /**
     * Quick and dirty solution for one problem
     * Combo widgets generated by AF are posting input field named like "{$i:fieldName}_value"
     * Since we are basing functionality of this action on autogenerated forms we got extra form fields and validation process breaks
     * This method assumes that every key that ends with "_value" is a value for foreign column coming from combo field
     * Each of those keys are changes by removing "_value" suffix
     * 
     * @return array
     * @author Łukasz Wojciechowski
     */
    private function changeKeysForForeignFields($formData)
    {
        $baseKeys = array();
        foreach ($formData as $key => $value) {
            if (substr($key, -6) != '_value') {
                continue;
            }
            
            $baseKey = str_replace('_value', '', $key);
            $baseKeys[] = $baseKey;
        }
        
        foreach ($baseKeys as $baseKey) {
            $valueForBaseKey = $formData["${baseKey}_value"];
            unset($formData["${baseKey}_value"]);
            $formData[$baseKey] = $valueForBaseKey;
        }
        
        return $formData;
    }
    
    /**
     * Getting Datasource classname
     *
     * @return string
     * @author Sergey Startsev
     */
    protected function getDatasource()
    {
        $class = $this->dom_xml_xpath->query('//i:datasource/i:class')->item(0);
        if ($class) {
            return $class->nodeValue;
        }
        
        return null;
    }
    
    /**
     * Getting defined fields names
     *
     * @return array
     * @author Sergey Startsev
     */
    protected function getFieldNames()
    {
        $fields = array();
        
        $fields_nodes = $this->dom_xml_xpath->query('//i:fields/i:field');
        foreach ($fields_nodes as $field) {
            if (in_array($field->getAttribute('type'), $this->deprecated_field_types)) continue;
            $fields[] = $field->getAttribute('name');
        }
        
        return $fields;
    }
    
    /**
     * Multiple relationships processing
     *
     * @param Array $formData 
     * @return array
     * @author Sergey Startsev
     */
    protected function processMultipleRelations(Array $formData)
    {
        $model_name = $this->object->getPeer()->getOMClass(false);
        
        $fields_nodes = $this->dom_xml_xpath->query('//i:fields/i:field[@type="doublemulticombo"]');
        foreach ($fields_nodes as $field) {
            $name = $field->getAttribute('name');
            $value = $formData[$name];
            
            $params = array();
            
            $class = $field->getElementsByTagName('class');
            $method = $field->getElementsByTagName('method');
            
            if (!($class) || !($method)) continue;
            
            $classNode = $class->item(0);
            $methodNode = $method->item(0);
            if ($classNode->nodeValue != 'ModelCriteriaFetcher' || $methodNode->getAttribute('name') != 'getDataForDoubleComboWidget') continue;
            
            foreach ($methodNode->getElementsByTagName('param') as $param) $params[$param->getAttribute('name')] = $param->nodeValue;
            
            $middle_model = $params['middle_model'];
            $middle_query = "{$middle_model}Query";
            $middle_model_field = $params['middle_model_field'];
            
            $query = $middle_query::create();
            call_user_func(array($query, "filterBy{$model_name}"), $this->object);
            $query->delete();
            
            $list = explode(",", $value);
            
            if ($list) {
                foreach ($list as $id) {
                    if ($id) {
                        $relation = new $middle_model;
                        call_user_func(array($relation, "set{$model_name}"), $this->object);
                        $relation->setByName($middle_model_field, $id, BasePeer::TYPE_FIELDNAME);
                        $relation->save();	
                    }
                }
            }
            
            if (array_key_exists($name, $formData)) unset($formData[$name]);
        }
        
        return $formData;
    }
    
    /**
     * File fields processing
     *
     * @example <i:field label="File" name="file" type="file">
     *              <i:value type="orm">
     *                  <i:class>ModelCriteriaFetcher</i:class>
     *                  <i:method name="configureFileField">
     *                      <i:param name="fields">[path:file_path_field]</i:param>
     *                  </i:method>
     *              </i:value>
     *          </i:field>
     *          
     *          if multiple files for table should be added <i:param name="glue_model">ForeignModelName</i:param>
     *          
     * @param BaseObject $model 
     * @return void
     * @author Sergey Startsev
     */
    protected function processFileFields(BaseObject $model)
    {
        $model_name = $this->object->getPeer()->getOMClass(false);
        
        $upload_dir = sfConfig::get('sf_upload_dir');
        $web_upload_dir = str_replace(sfConfig::get('sf_web_dir'), '', $upload_dir);
        
        if (!file_exists($upload_dir)) mkdir($upload_dir);
        
        foreach ($this->dom_xml_xpath->query('//i:fields/i:field[@type="file"]') as $field) {
            $name = $field->getAttribute('name');
            $params = array();
            
            $class = $field->getElementsByTagName('class');
            $method = $field->getElementsByTagName('method');
            
            if (!($class) || !($method)) continue;
            
            $classNode = $class->item(0);
            $methodNode = $method->item(0);
            if ($classNode->nodeValue != 'ModelCriteriaFetcher' || $methodNode->getAttribute('name') != 'configureFileField') continue;
            
            foreach ($methodNode->getElementsByTagName('param') as $param) $params[$param->getAttribute('name')] = $param->nodeValue;
            
            $is_foreign = false;
            
            $hashes = array();
            if (!array_key_exists('fields', $params)) continue;
            foreach (explode(',', str_replace(array('[', ']'), '', $params['fields'])) as $def) {
                list($key, $value) = explode(':', $def);
                $hashes[$key] = $value;
            }
            
            if (array_key_exists('glue_model', $params)) {
                $glue_model = $params['glue_model'];
                $is_foreign = true;
            }
            
            if (array_key_exists('upload_dir', $params)) {
                $web_upload_dir = '/' . trim($params['upload_dir'], '/');
                $upload_dir = sfConfig::get('sf_web_dir') . $web_upload_dir;
                if (!file_exists($upload_dir)) @mkdir($upload_dir, 0775, true);
            }
            
            if (!isset($_FILES['edit']['name']['0'][$name]) || !$_FILES['edit']['size']['0'][$name]) continue;
            
            $file_native_name = $_FILES['edit']['name']['0'][$name];
            $file_size = $_FILES['edit']['size']['0'][$name];
            $file_name = Util::makeRandomKey() . '.' . pathinfo($file_native_name, PATHINFO_EXTENSION);
            $file_path = "{$upload_dir}/{$file_name}";
            
            $tmp_name = $_FILES['edit']['tmp_name']['0'][$name];
            
            if (!move_uploaded_file($tmp_name, $file_path)) continue;
            
            if ($is_foreign) {
                $glue = new $glue_model;
                call_user_func(array($glue, "set" . get_class($model)), $model);
            } else {
                $glue = $model;
            }
            
            if (array_key_exists('path', $hashes)) $glue->setByName($hashes['path'], "{$web_upload_dir}/{$file_name}", BasePeer::TYPE_FIELDNAME);
            if (array_key_exists('original_name', $hashes)) $glue->setByName($hashes['original_name'], $file_native_name, BasePeer::TYPE_FIELDNAME);
            if (array_key_exists('name', $hashes)) $glue->setByName($hashes['name'], $file_name, BasePeer::TYPE_FIELDNAME);
            if (array_key_exists('size', $hashes)) $glue->setByName($hashes['size'], $file_size, BasePeer::TYPE_FIELDNAME);
            
            $glue->save();
        }
    }
    
    /**
     * This method reuturn field names that are also present in the form
     */
    private function getFieldNamesOfForm(sfForm $form)
    {
        $fieldNames = $this->getFieldNames();
        
        $formFieldNames = array();
        foreach ($form as $formFieldName => $formField) {
            if (in_array($formFieldName, $fieldNames)) {
                $formFieldNames[] = $formFieldName;
            }
        }
        return $formFieldNames;
    }
}
