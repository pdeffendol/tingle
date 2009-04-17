<?php
require_once dirname(__FILE__).'/Exception.php';

class Tingle_Template
{
	protected $_config = array(
		'template_path'  => array('.'),
		'template'       => null,
		'layout'         => null,
		'extract_vars'   => false,
		'helpers'        => array(),
		'active_helpers' => array()
		);


	public function __construct($config = null)
	{
		$this->_config = array_merge($this->_config, (array)$config);
		
		$this->register_bundled_helpers();
	}
	
	
	/**
	 * Register the helper classes that are bundled with Tingle.
	 */
	private function register_bundled_helpers()
	{
		$helpers = array('AssetTag', 'Capture', 'Form', 'FormTag', 'Tag', 'Text', 'Url');
		
		foreach ($helpers as $helper)
		{
			include_once dirname(__FILE__)."/helpers/{$helper}Helper.php";
			$this->register_helper("Tingle_{$helper}Helper");
		}
	}
	
	
	/**
	 * Register a helper class.
	 *
	 * A helper class contains static methods that will be
	 * registered as template helpers.
	 *
	 * Calling a registered helper method:
	 *
	 * $this->helper_method_name($arg1, $arg2);
	 *
	 * In the example above, $this is the Tingle_Template object.
	 */
	public function register_helper($name)
	{
		if (!class_exists($name))
		{
			throw new Tingle_InvalidHelperClass;
		}
		
		$helper_info = new ReflectionClass($name);
		
		foreach ($helper_info->getMethods() as $method)
		{
			if ($method->isPublic() && $method->isStatic())
			{
				$this->_config['helpers'][$method->getName()] = $name;
			}
		}
		
		return true;
	}
	
	
	/**
	 * Get a list of registered helper methods.
	 *
	 * @return array List of helper methods
	 */
	public function get_registered_helpers()
	{
		return array_keys($this->_config['helpers']);
	}
	
	
	/**
	 * Handle calls to helper methods by delegating to the appropriate
	 * helper class.
	 *
	 * @param string $helper Name of helper method
	 * @param array  $args   Arguments to helper method
	 * @return string Result of helper method
	 */
	public function __call($helper, $args)
	{
		$helper_class = $this->_config['helpers'][$helper];
		
		if (!$helper_class)
		{
			throw new Tingle_HelperMethodNotDefined($name);
		}
		
		if (!$this->_config['active_helpers'][$helper_class])
		{
			$this->_config['active_helpers'][$helper_class] = new $helper_class($this);
		}
		$helper_class =& $this->_config['active_helpers'][$helper_class];
		
		switch (count($args)) 
		{
			case 0:
				return $helper_class->$helper();
		
			case 1:
				return $helper_class->$helper($args[0]);
			case 2:
				return $helper_class->$helper($args[0], $args[1]);
			case 3:
				return $helper_class->$helper($args[0], $args[1], $args[2]);
			default:
				return call_user_func_array(array($helper_class, $helper), $args);
		}	
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
	 * Set whether to extract template variables to locals.
	 *
	 * If this is turned on, template files can access assigned
	 * variables without using $this.  Beware, this can cause
	 * confusion and/or conflicts if you are using local variables
	 * inside your template file.
	 *
	 * @param boolean $flag True to turn on extraction
	 */
	public function set_extract_vars($flag = true)
	{
		$this->_config['extract_vars'] = (bool)$flag;
	}

	/**
	 * Template rendering
	 */

	
	/**
	 * Indicate template file to process when calling ::display() and ::render() without
	 * parameters.
	 *
	 * @param string $template Path to template file
	 */
	public function set_template($template)
	{
		$this->_config['template'] = $template;
	}


  /**
	 * Indicate a layout file to process when calling ::display() and ::render().
	 *
	 * @param string $template Path to layout template file
	 */
	public function set_layout($template)
	{
		$this->_config['layout'] = $template;
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
			try {
				return $this->render();
			}
			catch (Tingle_Exception $e)
			{
				return '';
			}
		}
		
		return '';
	}

	
	/**
	 * Process a template file and output the results.  If a template filename
	 * is not provided, then use the filename already provided by ::set_template()
	 *
	 * @param string $template Path to template file
	 */
	public function display($template = null)
	{
		echo $this->render($template);
	}
	
	
	/**
	 * Process a template file and return the results as a string.  If a layout
	 * has been set, insert the template resulst into the rendered layout. If a template 
	 * filename is not provided, then use the filename already provided by 
	 * ::set_template()
	 *
	 * @param string $template Path to template file
	 * @return string Results of processing template
	 */
	public function render($template = null)
	{
		// Render template first
		$result = $this->render_without_layout($template);
			
		// Render layout if necessary
		if ($this->_config['layout'])
		{
			$this->content_for('layout')->set($result);
			return $this->render_without_layout($this->_config['layout']);
		}
		else
		{
			return $result;
		}
	}
	
	
	/**
	 * Render a partial template, optionally passing in some local
	 * variables.
	 *
	 * @param string $template Path to template file
	 * @param array  $locals   Associative array of local variables to assign
	 * return string Results of processing template
	 */
	protected function render_partial($template, $locals = array())
	{
		return $this->render_without_layout($template, $locals);
	}
	
	
	/**
	 * Process a template file and return the results as a string.  If a template 
	 * filename is not provided, then use the filename already provided by 
	 * ::set_template()
	 *
	 * @param string $template Path to template file
	 * @param array  $locals   Associative array of local variables to assign
	 * @return string Results of processing template
	 */
	private function render_without_layout($template = null, $locals = array())
	{
		if ($template === null)
		{
			$template = $this->_config['template'];
		}
		
		if (false === ($template_path = $this->template($template)))
		{
			throw new Tingle_TemplateNotFoundException('Template '.$template.' not found');
		}
		
		// "Hide" local variables in case we're using extraction
		$this->_config['saved_template_path'] = $template_path;
		unset($template);
		unset($template_path);
		
		if ($this->_config['extract_vars'])
		{
			extract($this->get_assignments(), EXTR_REFS);
		}
		extract((array)$locals, EXTR_OVERWRITE | EXTR_REFS);
		unset($locals);
		
		// Capture template output
		try 
		{
			ob_start();
			include($this->_config['saved_template_path']);
			$result = ob_get_contents();
			ob_end_clean();
		}
		catch (Exception $e)
		{
			// Clear the buffer so no output escapes (especially important
			// when dealing with nested templates)
			ob_end_clean();
			throw $e;
		}
		
		unset($this->_config['saved_template_path']);
		
		return $result;
	}
	
	/**
	 * Attempt to locate the specified template in the template
	 * path and return its full path.
	 *
	 * This can be used along with the PHP include statement to
	 * insert sub-templates.  (Use render_partial if you need
	 * to pass local variables to your sub-templates.)
	 *
	 * <?php include $this->template('header.tpl'); ?>
	 *
	 * @param  string Name of desired template file
	 * @return mixed Full path to template, or false if not found
	 */
	public function template($template)
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
