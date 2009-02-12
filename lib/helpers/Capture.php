<?php
require_once dirname(__FILE__).'/../Helper.php';
require_once dirname(__FILE__).'/Capture_Content.php';

class Tingle_Helper_Capture extends Tingle_Helper
{
	private $contents;
	
	public function __construct($template)
	{
		parent::__construct($template);
		$this->contents = array();
	}
	
	public function content_for($name)
	{
		if (!isset($this->contents[$name]))
		{
			$this->contents[$name] = new Tingle_Helper_Capture_Content($name);
		}
		
		return $this->contents[$name];
	}
}
?>
