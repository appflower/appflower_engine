<?php

class afHtmlRenderer {	
   
    public static function renderHtml($params, $module, $action, $view) {
 		   	
	    $pdf = new afSimplePdf($view);
        $data = array("request_params" => $params, "params" => $view->wrapAll('params/param'));
	    $pdf->render($data);
	    exit();
    }
    
}

