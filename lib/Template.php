<?php
class Tingle_Template
{
	protected $_config;
	
	public function __construct($config = null)
	{

	}
	
	public function assign($name_or_container, $value = null)
	{
		if (is_string($name_or_container))
		{
			// Don't allow overwriting of configuration settings
			if ($name_or_container != '_config')
			{
				$this->$name_or_container = $value;
				return true;
			}
		}
		
		if (is_array($name_or_container))
		{
			// Assign key/value pairs
			foreach ($name_or_container as $key => $value)
			{
				$this->assign($key, $value);
			}
			return true;
		}

		if (is_object($name_or_container))
		{
			return $this->assign(get_object_vars($name_or_container));
		}
		
		// Name isn't a string or container
		return false;
	}
	
	public function get_assignments()
	{
		$all = (array)get_object_vars($this);
		
		// Because we called get_object_vars inside the class, it returns
		// protected and private attributes.
		unset($all['_config']);
		
		return $all;
	}
}
?>
