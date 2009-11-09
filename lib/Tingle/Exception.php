<?php
namespace Tingle;

class Exception extends \Exception
{
}

class TemplateNotFoundException extends Exception
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

class RenderingError extends Exception
{
}

class InvalidHelperClass extends Exception
{
	private $attempted_class;
	
	public function __construct($attempted_class)
	{
		$this->attempted_class = $attempted_class;
	}
	
	public function __toString()
	{
		return __CLASS__.": '{$this->attempted_class}' is not a helper class";
	}
}

class HelperMethodNotDefined extends Exception
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
