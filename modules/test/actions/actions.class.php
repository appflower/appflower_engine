<?php

/**
 * test actions.
 *
 * @package    manager
 * @subpackage test
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class testActions extends CustomActions
{
	/**
	 * Executes index action
	 *
	 */
	public function executeIndex()
	{
		
		$this->forward('default', 'module');
	}

	public function executeEdit()
	{
		 
		if($this->getRequest()->getMethod()==sfRequest::POST)
		{
			$result = array('success' => true);
			$result = json_encode($result);

			return $this->renderText($result);
		}
		 
		$this->bar = "oo";
		$this->foobar = "lalala";
		 
		

	}
	
	
	public function executeEditnew()
	{
		 
		if($this->getRequest()->getMethod()==sfRequest::POST)
		{
			$result = array('success' => true);
			$result = json_encode($result);

			return $this->renderText($result);
		}
		 
		$this->bar = "oo";
		$this->foobar = "lalala";
		 
		

	}

	public function executePopup()
	{

		sfProjectConfiguration::getActive()->loadHelpers("Helper");

		$parser = new XmlParser();

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");

		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}


	public function executeDummy()
	{


		$result = array('success' => true, 'message' => 'This is success message!');

		if($this->errors) {
			$result = array('success' => false, 'message' => 'A validation error occured!');
			foreach($this->errors as $error) {
				$result["errors"][$error[0]] = $error[1];
			}
		}

		$result = json_encode($result);
		return $this->renderText($result);

	}


	public function executeNew()
	{

		$result = array('success' => true, 'message' => 'This is new');
		$result = json_encode($result);
		return $this->renderText($result);

	}


	public function executeDelete()
	{

		$result = array('success' => true, 'message' => 'This is delete');
		$result = json_encode($result);
		return $this->renderText($result);

	}

	public function executeList()
	{
		 
		$this->foobar = 2;
		$this->bar = "lalla";
	}


	public function executeButtons()
	{
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");

		$parser = new XmlParser();

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");
		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}


	public function executeListtree()
	{
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");

		$parser = new XmlParser();

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");
		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}


	public function executeInfo()
	{
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");

		$parser = new XmlParser();

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");
		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}


	public function executeGroups()
	{
		 
		$this->args = 0;
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");

		$parser = new XmlParser();

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");
		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}



	public function executeListstatic()
	{
		 
		$this->getVarHolder()->add(array("bar" => "foo11"));
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");
		 
		$parser = new XmlParser();

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");
		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}


	public function executeHtml()
	{
		 
		$this->getVarHolder()->add(array("html" => "<b>this is bold<br />foo<br>bar<br /><br/>foobar</b>"));
	}


	public function executeWizard1()
	{
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");
		 
		$parser = new XmlParser(XmlParser::WIZARD);

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");

		$parser->getNext();
		$parser->getPrevious();

		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}

	public function executeWizard2()
	{
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");
		 
		$parser = new XmlParser(XmlParser::WIZARD);

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");

		$parser->getNext();
		$parser->getPrevious();

		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}


	public function executeWizard3()
	{
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");
		 
		$parser = new XmlParser(XmlParser::WIZARD);

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");

		$parser->getNext();
		$parser->getPrevious();

		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}


	public function executeLayout2()
	{
		 
		$this->getVarHolder()->add(array("bar" => "foo11"));
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");
		 
		$parser = new XmlParser(XmlParser::PAGE);

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");
		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}

	public function executeLayout3()
	{
		 
		$this->getVarHolder()->add(array("bar" => "foo11"));
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");

		$parser = new XmlParser(true);

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");
		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}

	public function executeShow()
	{

		$this->getVarHolder()->add(array("id" => $this->getRequestParameter("id",1)));
		 
		sfProjectConfiguration::getActive()->loadHelpers(array("Helper","sfExtjs2"));
		 
		$parser = new XmlParser();

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");
		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}


	public function executeZonepage()
	{
		 

	}

	public function executeNewZone()
	{
		 
		if($this->getRequest()->getMethod()==sfRequest::POST)
		{
				
			$x = new TimeZones();
            $formData = $this->getRequestParameter("edit");
            $formData = $formData[2];
			$x->setName($formData['name']);
			$x->setOffset($formData['offset']);
			$x->save();

			$result = array('success' => true);
			$result = json_encode($result);

			return $this->renderText($result);
		}
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");

		$parser = new XmlParser();

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");
		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}


	public function executeShowZone()
	{
		 
		$this->getVarHolder()->add(array("id" => $this->getRequestParameter("id")));
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");

		$parser = new XmlParser();
		$this->layout = $parser->getLayout();
		$this->setLayout("layoutExtjs");
		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}

	public function executeListZones()
	{
		 
		sfProjectConfiguration::getActive()->loadHelpers("Helper");
		 
		$parser = new XmlParser();

		$this->layout = $parser->getLayout();

		$this->setLayout("layoutExtjs");
		$this->setTemplate("edit");

		return sfView::SUCCESS;

	}


	function executeListjson()
	{

		$start = $this->request->getParameterHolder()->has('start')?$this->request->getParameterHolder()->get('start'):0;
		$limit = $this->request->getParameterHolder()->has('limit')?$this->request->getParameterHolder()->get('limit'):20;
		$page=($start==0)?1:(ceil($start/$limit)+1);

		$parser = $this->getUser()->getAttributeHolder()->getAll('parser/grid');
		$host = "https://".$this->getRequest()->getHost();

		foreach($parser as $data) {
			if($data["proxy"] == $this->getModuleName()."/".$this->getActionName()) {
				$parser = $data;
				break;
			}
		}

		$pager = new sfPropelPager($parser["class"], $parser["limit"]);
		$pager->setPage($page);
		$pager->setPeerMethod($parser["select_method"]);
		$pager->setCriteria($parser["criteria"]);
		$pager->init();
		 
		$grid_data = new afExtjsGridData();
		$grid_data->totalCount = $pager->getNbResults();
		 
		$items = array();

		$i = $j = 0;

		foreach($pager->getResults() as $object) {
				
			foreach($parser["columns"] as $column) {
				$j = 0;
				$id = call_user_func(array($object,"getId"));
				$tmp = call_user_func(array($object,"get".$column["phpname"]));
				if(in_array($column["phpname"],$parser["foreign_keys"])) {
					$items[$i][$column["column"]] = call_user_func(array($tmp,"__toString"));
				} else {
					$items[$i][$column["column"]] = $tmp;
				}
					
				foreach($parser["rowactions"] as $k => $action) {
					if(!strstr($host.$action["attributes"]["url"],"?")) {
						$host.$action["attributes"]["url"] .= "?";
					}
					$items[$i]["action".($j+1)] = $host."/".$action["attributes"]["url"]."id=".$id."&";
					$j++;
				}
			}
			$i++;
		}

		foreach ($items as $item) {
			$grid_data->addRowData($item);
		}

		return $this->renderText($grid_data->end());
	}


	function executeListgridjson()
	{

		$start = $this->request->getParameterHolder()->has('start')?$this->request->getParameterHolder()->get('start'):0;
		$limit = $this->request->getParameterHolder()->has('limit')?$this->request->getParameterHolder()->get('limit'):20;
		$anode = $this->request->getParameterHolder()->has('anode')?$this->request->getParameterHolder()->get('anode'):null;

		$uid = $this->request->getParameterHolder()->get('uid');

		$page=($start==0)?1:(ceil($start/$limit)+1);

		$parser = $this->getUser()->getAttributeHolder()->getAll('parser/grid');

		$host = "https://".$this->getRequest()->getHost();
		foreach($parser as $data) {
			if($data["uid"] == $uid) {
				$parser = $data;
				break;
			}
		}

		$pager = new sfPropelPager($parser["class"], $parser["limit"]);

		if($anode === null) {
			$c = $parser["criteria"];
		} else {
			$parser["datasource"]["method"]["params"]["criteria"] = $parser["criteria"];
			$parser["datasource"]["method"]["params"]["gid"] = $anode;
			$c = afCall::funcArray(array($parser["datasource"]["class"],$parser["datasource"]["method"]["name"]),
			$parser["datasource"]["method"]["params"]);
		}

		$pager->setPeerMethod($parser["select_method"]);
		$pager->setPage($page);
		$pager->setCriteria($c);
		$pager->init();
			

		$grid_data = new afExtjsGridData();
		$grid_data->totalCount = $pager->getNbResults();

		$items = array();
			
		$i = $j = 0;

		foreach($pager->getResults() as $object) {
				
			foreach($parser["columns"] as $column) {
				$j = 0;
				$id = $object->getId();
					
				if(method_exists($object,"get".$column["phpname"])) {
					$tmp = call_user_func(array($object,"get".$column["phpname"]));
				} else {
					$tmp = "";
				}
					
				if($tmp && in_array($column["phpname"],$parser["foreign_keys"])) {
					$items[$i][$column["column"]] = call_user_func(array($tmp,"__toString"));
				} else {
					$items[$i][$column["column"]] = $tmp;
				}
					
				$items[$i]["_id"] = ($anode == null) ? $id : rand();
				$items[$i]["_parent"] = $anode;
				$items[$i]["_is_leaf"] = ($anode == null) ? false : true;
					
				foreach($parser["rowactions"] as $k => $action) {
					if(!strstr($host.$action["attributes"]["url"],"?")) {
						$host.$action["attributes"]["url"] .= "?";
					}
					if($anode !== null) {
						$items[$i]["action".($j+1)] = $host.$action["attributes"]["url"]."id=".$id."&";
					}

					$j++;
				}
			}
			$i++;
		}

		foreach ($items as $item) {
			$grid_data->addRowData($item);
		}

		return $this->renderText($grid_data->end());
	}
}
