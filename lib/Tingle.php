<?php
namespace Tingle;

require_once dirname(__FILE__).'/'.__NAMESPACE__.'/Exception.php';

function register_autoloader()
{
  if (!$callbacks = spl_autoload_functions())
  {
    $callbacks = array();
  }
  foreach ($callbacks as $callback)
  {
    spl_autoload_unregister($callback);
  }
  spl_autoload_register(__NAMESPACE__.'\autoload');
  foreach ($callbacks as $callback)
  {
    spl_autoload_register($callback);
  }
}

function autoload($class)
{
	if (!preg_match('#^\\\\?'.__NAMESPACE__.'\\\\(.*)#', $class, $matches))
	{
    return;
  }
	$namespaced_class = $matches[1];
	
	foreach (array('', '/helpers') as $path)
	{
	  $full_path = dirname(__FILE__) . '/' . __NAMESPACE__ . $path . '/' . $namespaced_class . '.php';
	  if (file_exists($full_path))
	  {
	    require_once $full_path;
			return;
	  }
	}
}

register_autoloader();
?>
