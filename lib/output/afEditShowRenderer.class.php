<?php

class afEditShowRenderer {	
   
    public static function renderEditShow($request, $module, $action, $actionVars, $view) {
 		   	
    	$source = afDataFacade::getDataSource($view,$request->getParameterHolder()->getAll());
	    $pdf = new afSimplePdf($view);
	 	$pdf->renderEdit($source);
	 	$pdf->push();
	    exit();
       
    }

}

