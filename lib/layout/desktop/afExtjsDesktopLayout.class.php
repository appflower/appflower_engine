<?php
/**
 * extJs desktop layout
 */
class afExtjsDesktopLayout extends afExtjsLayout
{
	public function __construct($attributes=array())
	{
		parent::__construct($attributes);
		$this->afExtjs->setAddons(array ('css' => array(), 'js' => array()));
	}
}
?>
