<?php

class afSimplePdf {
	
	private
		$pdf;
	
	function __construct($view) {
		
		$path = sfConfig::get("sf_root_dir")."/plugins/appFlowerPlugin/lib/output/fpdf";
		
		require($path.'/fpdf.php');
	
		$this->pdf=new afPDF();
		$this->pdf->AliasNbPages();
		
		$this->pdf->widget["title"] = $view->get("title");
		$this->pdf->widget["view"] = $view->get("@type");
		
		$this->pdf->af_version = sfConfig::get("app_appFlower_version");
		
		$this->pdf->setAuthor("AppFlower v".$this->pdf->af_version);
		$this->pdf->setCreator("AppFlower v".$this->pdf->af_version);
		$this->pdf->setDisplayMode("real");
		$this->pdf->setTitle($this->pdf->widget["title"]." Widget");
		$this->pdf->setSubject($this->pdf->widget["title"]." Widget printable");
		
		$this->pdf->AddPage();
		
	}
	
	
	public function renderList(Array $rows,Array $columns) {
		

		// Set headers and find group by..
		
		$headers = array();
		$group_field = null;
		
		foreach($columns as $col) {
			$headers[$col->get("@name")] = $col->get("@label");
			if($col->get("@groupField")) {
				$group_field = $col->get("@name");
			}
		}
		
		// Remove HTML-only columns..
		
		foreach($rows as $k => $row) {
			$i = 0;
			foreach($row as $name => &$item) {
				$html = StringUtil::hasTags($item);
				$item =  StringUtil::removeTags($item);
				if(!trim($item) && $html) {
					unset($rows[$k][$name]);
					unset($headers[$name]);
				}
				if(!array_key_exists($name,$headers)) {
					unset($rows[$k][$name]);
				}
				$i++;
			}
		}		
	
		// Set column width..
		
		$col_width = 190 / count($headers);
		
		$this->pdf->SetFont('Arial','B',12);
		$this->pdf->setFillColor(220,220,220);
		$header_dim = $borders = array();
		
		foreach($headers as $header) {
			$header_dim[] = $col_width;
			$borders[] = 1;
			$this->pdf->Cell($col_width,8,$header,1,0,"L",1);
		}
		
		$this->pdf->Ln();
		
		$this->pdf->setWidths($header_dim);
		$this->pdf->setBorders($borders);
		$this->pdf->SetFont('Arial','',11);
		$this->pdf->setFillColor(255,255,255);
		
		foreach($rows as $row) {
			$tmp = array();
			foreach($row as $name => $cell) {
				$tmp[] = StringUtil::removeTags($cell);
			}
			$this->pdf->Row($tmp,false,array(),0.2,10);
		}
	}
	
	
	private function getFieldValue($field,$object) {
		
		
		$value = $field->get("@value");
		$selected = $field->get("@selected");
		
		if(!$value) {
			if($field->get("@type") == "checkbox" || $field->get("@type") == "radio") {
				$value = ($field->get("@checked")) ? "yes" : "no";
			} else {
				$source = $field->wrapAll("value");
				if(!empty($source)) {
					$orm = $source[0]->get("source@name");
					if($orm) {
						$method = "get".sfInflector::camelize($field->get("@name"));
						if(method_exists($object,$method)) {
							$value = $object->$method();	
						}
					} else {
						$class = $source[0]->get("class");
						$method = $source[0]->get("method@name");
						$tmp = $source[0]->wrapAll("method/param");
						$params = array();
						
						foreach($tmp as $t) {
							$params[] = $t->get("");
						}
						
						$result = call_user_func_array(array($class,$method),$params);
						
						if($field->get("@type") == "combo" || $field->get("@type") == "extendedCombo" || $field->get("@type") == "multicombo") {
							if(isset($result[$selected])) {
								$value = $result[$selected];	
							}
						} else if($field->get("@type") == "doublemulticombo" || $field->get("@type") == "doubletree") {
							foreach($result[1] as $r) {
								$value .= $r.",";
							}
							$value = trim($value,",");
						}
					}
				}
			}
		}
		
		if($field->get("@type") == "static" && substr($value,0,4) == "<img") {
			// Image..
		}

		return $value;
	}
	
	
	private function printFieldSetTitle($set = null) {
		
		if($set) {
			$title = ($set->get("@tabtitle")) ? $set->get("@tabtitle") : $set->get("@title"); 
		} else {
			$title = "Form fields";
		}
		
		$this->pdf->setFillColor(255,255,255);
		$this->pdf->setLineWidth(0.2);
		//$this->pdf->setFillColor(190,190,190);
		$this->pdf->Ln(1);
		$this->pdf->Cell(0,8,$title,1,1,"L",1);
		$this->pdf->Ln(1);
		
		
	}
	
	
	public function renderEdit(Array $data) {
		
		$exclude = array
		(
		"include",
		"file",
		"hidden",
		"password"
		);
		
		$this->pdf->setWidths(array(90,90));
		$this->pdf->setBorders(array(0,"L"));
		$this->pdf->setAligns(array("R","L"));
		//$this->pdf->setFills(array(array(220,220,220)));
		
		if($data["grouping"]) {
			foreach($data["grouping"] as $set) {
				$printable = array();
				$refs = $set->wrapAll("ref");
				foreach($refs as $ref) {
					$field = afDomAccess::getByAttribute($data["fields"],"name",$ref->get("@to"));
					if($field && !in_array($field->get("@type"),$exclude)) {
						$printable[] = $field;
					}
				}
				if(!empty($printable)) {
					$this->printFieldSetTitle($set);
					foreach($printable as $field) {
						$this->pdf->Row(array(str_replace("*","",$field->get("@label")).":","  ".StringUtil::removeTags($this->getFieldValue($field,$data["object"]))),true,array(), 0.5,10);	
					}
				}
			}
		} else {
			
			$this->printFieldSetTitle();
			
			foreach($data["fields"] as $k => $field) {
				
				if(!in_array($field->get("@type"),$exclude)) {
					$this->pdf->Row(array(str_replace("*","",$field->get("@label")).":","  ".StringUtil::removeTags($this->getFieldValue($field,$data["object"]))),true,array(), 0.5,10);
					$this->pdf->setLineWidth(0.2);
				}	
			
			}
			
		}
		
		$this->pdf->setLineWidth(0.2);
				
	}
	
	
	
	public function renderShow(Array $data) {
		$this->renderEdit($data);
	}
	
	
	public function push($download = false) {
		
		$this->pdf->Output("",($download) ? "D" : "");
	
	}
	
	
	
}

?>