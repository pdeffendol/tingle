<?php

class Tingle_Exception extends Exception
{
}

class Tingle_TemplateNotFoundException extends Tingle_Exception
{
	private $template;
	private $template_path;
	
	public function __construct($template, $template_path)
	{
		$this->template = $template;
		$this->template_path = $template_path;
	}
	
	public function __toString()
	{
		return __CLASS__.": Template '{$this->template}' not found (template path: '".implode(':', $this->template_path)."')";
	}
}

class Tingle_RenderingError extends Tingle_Exception
{
}

class Tingle_InvalidHelperClass extends Tingle_Exception
{
	
}

class Tingle_HelperMethodNotDefined extends Tingle_Exception
{
	private $helper_name;
	
	public function __construct($helper_name)
	{
		$this->helper_name = $helper_name;
	}
	
	public function __toString()
	{
		return __CLASS__.": '{$this->helper_name}'";
	}	
}
?>
