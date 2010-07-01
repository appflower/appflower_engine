<?php

class afSimplePdf {
	
	private
		$pdf,
		$width,
		$view,
		$orientation,
		$group_field,
		$filename,
		$root,
		$headers;
	
	public function __construct($view) {
		
		$path = sfConfig::get("sf_root_dir")."/plugins/appFlowerPlugin/lib/output/fpdf";
		
		require($path.'/fpdf.php');
		
		$this->view = $view;
		$this->root = sfConfig::get("sf_root_dir")."/web";
		
	}

	public function render(Array $data) {
		
		$orientation = "P";
		
		if($this->view->get("@type") == "list") {
			$this->removeJunkColumns($data[0],$data[1]);
			if(count($this->headers) > 8) {
				$orientation = "L";
			}
		}
		
		$this->width = ($orientation == "P") ? 190 : 277;
		
		$this->pdf=new afPDF($orientation);
		$this->pdf->orientation = $orientation;
		$this->pdf->AliasNbPages();
		
		$this->pdf->widget["title"] = $this->view->get("title");
		$this->pdf->widget["view"] = $this->view->get("@type");
		$this->filename = $this->getFileName();
		
		$this->pdf->af_version = sfConfig::get("app_appFlower_version");
		
		$this->pdf->setAuthor("AppFlower v".$this->pdf->af_version);
		$this->pdf->setCreator("AppFlower v".$this->pdf->af_version);
		$this->pdf->setDisplayMode("real");
		$this->pdf->setTitle($this->pdf->widget["title"]." Widget");
		$this->pdf->setSubject($this->pdf->widget["title"]." Widget printable");
		
		$this->pdf->AddPage();
		
		$method = "render".ucfirst($this->view->get("@type"));
		
		if($this->view->get("@type") != "html") {
			call_user_func_array(array($this,$method),$data);	
		} else {
			call_user_func(array($this,$method),$data);
		}
		
		$this->push();
		
		
	}
	
	
	private function getFileName() {
		return strtolower(preg_replace("/[[:space:]]+|[^a-zA-Z0-9]+/","_",$this->pdf->widget["title"]))."_".date("YmdHis").".pdf";
	}
	
	
	private function removeJunkColumns(Array &$rows,Array $columns) {
		
		// Set headers and find group by..
		
		$hiddens = array();
		
		foreach($columns as $col) {
			$this->headers[$col->get("@name")] = $col->get("@label");
			$colname = $col->get("@name");
			if($col->get("@groupField")) {
				$this->group_field = $colname;
			}
			if($col->get("@hidden")) {
				$hiddens[] = $colname;
			}
		}	
		
		// Remove HTML-only and hidden columns..
		
		foreach($rows as $k => $row) {
			$i = 0;
			foreach($row as $name => &$item) {
				$html = StringUtil::hasTags($item);
				$item =  StringUtil::removeTags($item);
				if(((!trim($item) && $html) || !array_key_exists($name,$this->headers) || 
				in_array($name,$hiddens)) && $name != $this->group_field) {
					unset($rows[$k][$name]);
					unset($this->headers[$name]);
				}
				$i++;
			}
		}
	}
	
	
	private function groupItems($rows) {
		
		$ret = array();
		
		foreach($rows as &$row) {
			$key = $row[$this->group_field];
			unset($row[$key]);
			$ret[$key][] = $row;
		}
		
		return $ret;
		
		
	}
	
	private function renderList(Array $rows,Array $columns) {
		
	
		// Set column width..
		
		$col_width = $this->width / count($this->headers);
		
		$this->pdf->SetFont('Arial','B',12);
		$this->pdf->setFillColor(220,220,220);
		$header_dim = $borders = array();
		
		foreach($this->headers as $header) {
			$header_dim[] = $col_width;
			$borders[] = 1;
			$this->pdf->Cell($col_width,8,$header,1,0,"L",1);
		}
		
		$this->pdf->Ln();
		
		$this->pdf->setWidths($header_dim);
		$this->pdf->setBorders($borders);
		$this->pdf->SetFont('Arial','',11);
		$this->pdf->setFillColor(255,255,255);
		
		if(!$this->group_field) {
			$items = array($rows);
		} else {
			$items = $this->groupItems($rows);
		}
		
		foreach($items as $group => $rows) {
			if($group) {
				$this->pdf->SetFont('Arial','B',12);
				$this->pdf->setFillColor(240,240,240);
				$this->pdf->Cell(0,8,$group,1,1,"L",1);
				$this->pdf->SetFont('Arial','',11);
			}
			foreach($rows as $row) {
				$tmp = array();
				foreach($row as $name => $cell) {
					$tmp[] = StringUtil::removeTags($cell);
				}
				$this->pdf->Row($tmp,false,array(),0.2,10);
			}
		}
		
	}
	
	
	private function getFieldValue($field,$object) {
		$value = afEditView::getFieldValue($field, $object);
		if(preg_match("/<img[^s]*src=\"([^\"]+)\"[^>]*>/i",$value,$match)) {
			$value = $this->root;
			if(substr($match[1],0,1) != "/") {
				$match[1] = "/".$match[1];
			}
			$value .= $match[1];
			
			if(!file_exists($value)) {
				$value = "";
			} else {
				$value = "afpdf_image:".$value;
			}
			
		} else {
			$value = StringUtil::removeTags($value);
		}

		return $value;
	}
	
	private function printFieldSetTitle($set = null) {
		
		$this->pdf->SetFont("Arial","B",11);
		
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
	
	
	
	private function renderEdit($object, Array $fields, Array $grouping) {
		
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
		$this->pdf->SetFontInfo(array(array("Arial","B",11),array("Arial","",11)));
		//$this->pdf->setFills(array(array(220,220,220)));
		
		if($grouping) {
			foreach($grouping as $set) {
				$printable = array();
				$refs = $set->wrapAll("ref");
				foreach($refs as $ref) {
					$field = afDomAccess::getByAttribute($fields,"name",$ref->get("@to"));
					if($field && !in_array($field->get("@type"),$exclude)) {
						$printable[] = $field;
					}
				}
				if(!empty($printable)) {
					$this->printFieldSetTitle($set);
					foreach($printable as $field) {
						$this->pdf->Row(array(str_replace("*","",$field->get("@label")).":","  ".$this->getFieldValue($field,$object)),true,array(), 0.5,10);	
					}
				}
			}
		} else {
			
			$this->printFieldSetTitle();
			
			foreach($fields as $k => $field) {
				
				if(!in_array($field->get("@type"),$exclude)) {
					$this->pdf->Row(array(str_replace("*","",$field->get("@label")).":","  ".$this->getFieldValue($field,$object)),true,array(), 0.5,10);
					$this->pdf->setLineWidth(0.2);
				}	
			
			}
			
		}
		
		$this->pdf->setLineWidth(0.2);
				
	}
	
	
	
	private function renderShow(Array $data) {
		$this->renderEdit($data);
	}
	
	private function renderHtml(Array $data) {
		
		$html = $data["request_params"][$data["params"][0]->get("@name")];
		$html = StringUtil::removeTags(preg_replace("/<br[\s\/]*>/","\n",$html));
	
		$this->pdf->SetFont('Arial','',11);
		
		$this->pdf->MultiCell(0,5,$html);
		
	}
	
	
	private function push($download = false) {
		
		$this->pdf->Output($this->filename,($download) ? "D" : "I");
	
	}
	
	
	
}

?>
