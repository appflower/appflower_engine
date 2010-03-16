<?php

class ImmExtJsLayoutFilter extends sfFilter
{
	public function execute ($filterChain)
	{
		
		/*if($this->context->getRequest()->getParameter('layout') == 'old')
		{
			if ($this->isFirstCall())
			{
				sfProjectConfiguration::getActive()->loadHelpers("Helper");
			}
		
			if($this->context->getRequest()->getParameter('glued') == '1')
			{
				$glued = true;
			}
			else
			{
				$glued = false;
			}
			
			$actionInstance = $this->context->getActionStack()->getLastEntry()->getActionInstance();
			
			$parser = new XmlParser($glued);
			
			$actionInstance->getVarHolder()->set('layout', $parser->getLayout());
			$actionInstance->getVarHolder()->set('parser', $parser);
			$actionInstance->setTemplate("ext");
			$actionInstance->setLayout("layoutExtjs");
		}
		
		*/
		$filterChain->execute();
		
		//if($this->context->getRequest()->getParameter('layout') == 'new')
		//{
		//	$parser->runParser();
		//}
	}
}