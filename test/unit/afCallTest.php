<?php
require_once(dirname(__FILE__).'/../../lib/afCall.class.php');

$limit = 50;

function __autoload($class_name) {
    eval("
echo \"$class_name loaded\\n\";
class $class_name {
  public static function execute_me(){
    return \"$class_name executed\\n\";
  }
}");
}

function callback($limit, $i = 1){
  //echo call_user_func_array(array("P$i", 'execute_me'), array());
  echo afCall::funcArray(array("P$i", 'execute_me'), array());
  if($i < $limit) callback($limit, $i+1);
}

callback($limit);
echo "Success\n";

