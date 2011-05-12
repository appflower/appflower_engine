<?php

class CustomActions extends sfActions
{
    /**
     * Recognizes a need to convert the redirect to a JSON response.
     */
    public function redirect($url, $statusCode = 302)
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $url = $this->getController()->genUrl($url, true);
            $params = array('success'=>true, 'redirect'=>$url);

            $response = $this->getResponse();
            $response->clearHttpHeaders();
            $response->setStatusCode(200);
            $response->setContent(json_encode($params));
            $response->send();
            throw new sfStopException();
        } else {
            parent::redirect($url, $statusCode);
        }
    }
}
