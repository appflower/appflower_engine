<?php

class afEditShowRenderer {	
   
    public static function renderEditShow($request, $module, $action, $view) {
 		   	
	    $pdf = new afSimplePdf($view);
        $result = self::fetchDataInstance($view);
        $data = array("object" => $result,"fields" => $view->wrapAll("fields/field"), "grouping" => $view->wrapAll("grouping/set"));
	    $pdf->render($data);
	    exit();
    }

    public static function fetchDataInstance($view) {
        list($callback, $params) = afDataFacade::getDataSourceCallback($view);
        return afCall::funcArray($callback, $params);
    }
}

