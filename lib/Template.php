<?php
require_once dirname(__FILE__).'/Exception.php';

class Tingle_Template
{
	protected $_config = array(
		'template_path' => array('.'),
		'template'      => null
		);

	
	public function __construct($config = null)
	{
		# code...
	}

	
	/**
	 * Assign variables to the template.
	 *
	 * Variables may be assigned one at a time, or you can assign
	 * all the properties of an object or associated array.
	 *
	 * Assigning a variable named _config is not allowed, as it would
	 * interfere with the Template settings.
	 *
	 * Examples:
	 * 
	 * <code>
	 * $tpl = new Tingle_Template;
	 *
	 * // Assign single variable
   * $tpl->assign('var', 'value');
	 *
	 * // Assign associative array
	 * $data = array('var1' => 'value1', 'var2' => 'value2);
	 * $tpl->assign($data);
	 *
	 * // Assign object properties
	 * $obj = new StdClass;
	 * $obj->var1 = 'value1';
	 * $obj->var2 = 'value2';
	 * $tpl->assign($obj);
	 *
	 * // Assign directory to template object
	 * $tpl->var = 'value'
	 *
	 * @return boolean True on success, false on failure
	 */
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

	
	/**
	 * Return all assigned variables, as an associative array.
	 *
	 * @return array Assigned template variables
	 */
	public function get_assignments()
	{
		$all = (array)get_object_vars($this);
		
		// Because we called get_object_vars inside the class, it returns
		// protected and private attributes.
		unset($all['_config']);
		
		return $all;
	}
	
	/**
	 * Configuration
	 */
	
	/**
	 * Add another directory to the template search path.
	 *
	 * @param string $path Path to location of templates in filesystem
	 */
	public function add_template_path($path)
	{
		$this->_config['template_path'] = array_merge($this->_config['template_path'] , (array)$path);
	}
	
	/**
	 * Set the template search path, overwriting any previous settings.
	 *
	 * @param string $path New template search path
	 */
	public function set_template_path($path)
	{
		$this->_config['template_path'] = array();
		$this->add_template_path('.');
		
		$this->add_template_path($path);
	}
	
	
	/**
	 * Retrieve template search paths.
	 * 
	 * @return array Search paths
	 */
	public function get_template_path()
	{
		return $this->_config['template_path'];
	}


	/**
	 * Template rendering
	 */

	
	/**
	 * Indicate template file to process when calling ::fetch() and ::render() without
	 * parameters.
	 *
	 * @param string $template Path to template file
	 */
	public function set_template($template)
	{
		$this->_config['template'] = $template;
	}

	
	/**
	 * Convert a Template object to a string by obtaining the rendered template.
	 * If a template has not been set with ::set_template() then return an
	 * empty string.
	 *
	 * @return string Rendered template
	 **/
	public function __toString()
	{
		if ($this->_config['template'])
		{
			return $this->fetch();
		}
		
		return '';
	}

	
	/**
	 * Process a template file and output the results.  If a template filename
	 * is not provided, then use the filename already provided by ::set_template()
	 *
	 * @param string $template Path to template file
	 */
	public function render($template = null)
	{
		echo $this->fetch($template);
	}
	
	
	/**
	 * Process a template file and return the results as a string.  If a template 
	 * filename is not provided, then use the filename already provided by 
	 * ::set_template()
	 *
	 * @param string $template Path to template file
	 * @return string Results of processing template
	 */
	public function fetch($template = null)
	{
		if ($template === null)
		{
			$template = $this->_config['template'];
		}
		
		if (false === ($template_path = $this->find_template($template)))
		{
			throw new Tingle_TemplateNotFoundException('Template '.$template.' not found');
		}
		
		// Capture template output
		ob_start();
		include($template_path);
		return ob_get_clean();
	}

	
	/**
	 * 	Attempt to locate the specified template in the template
	 * path and return its full path.
	 *
	 * @param  string Name of desired template file
	 * @return mixed Full path to template, or false if not found
	 */
	private function find_template($template)
	{
		$paths = (array)$this->_config['template_path'];
		
		foreach ($paths as $path)
		{
			$full_path = realpath($path.DIRECTORY_SEPARATOR.$template);
			$real_path = realpath($path);
			
			// Only templates inside the template_path directories are
			// allowed to be executed
			if (file_exists($full_path) 
				&& is_readable($full_path) 
				&& substr($full_path, 0, strlen($real_path)) == $real_path)
			{
				return $full_path;
			}
		}
		
		// File not found in any path
		return false;
	}
}
?>
