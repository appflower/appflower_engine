<?php 

class bugReportActions extends CustomActions
{
  
	public function executeIndex()
	{
    	return XmlParser::layoutExt($this);
	}
  
	public function executeUpdate()
	{
  		if($this->getRequest()->getMethod()==sfRequest::POST) {
  			$parameters = array(
                    'report_subject'  => $this->getRequestParameter('edit[0][subject]'),
                    'report_comment'  => $this->getRequestParameter('edit[0][comment]'),
  					'report_email'    => $this->getRequestParameter('edit[0][email]'),
                    'email'    => sfConfig::get('app_bug_report_email'),
                    'subject'  => 'Bug Report',
                    'from'     => 'Seedcontrol'
                );

            afAutomailer::saveMail('bugReport', 'sendBugReport', $parameters);
            
            $result = array('success' => true, 'message' => 'Bug report submitted successfully.', 'redirect' => $this->getRequestParameter('af_referer','/'));
			$result = json_encode($result);
			return $this->renderText($result);
  		}
	}

}
