  public function executeList(sfWebRequest $request)
  { 
    return XmlParser::layoutExt($this);
  }
