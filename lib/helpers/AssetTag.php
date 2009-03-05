<?php
require_once dirname(__FILE__).'/../Helper.php';
require_once dirname(__FILE__).'/../Inflector.php';

class Tingle_Helper_AssetTag extends Tingle_Helper
{
	private $expansions = array();
	private $asset_paths = array(
		'stylesheet' => '/stylesheets',
		'javascript' => '/javascripts');
	
	private static $feed_types = array(
		'rss' =>  'application/rss+xml',
		'atom' => 'application/atom+xml');
		
	/**
	 * Return a stylesheet link tag for the given sources.
	 */
	public function stylesheet_link_tag($sources, $options = array())
	{	
		$options = array_merge(
			array('media' => 'screen'),
			$options);
			
		$urls = $this->expand_asset_sources('stylesheet', $sources);
		
		$tags = array();
		foreach ($urls as $url)
		{
			$tag = '<link href="'.$url.'"';
			if ($options['media']) $tag .= ' media="'.$options['media'].'"';
			$tag .= ' rel="stylesheet" type="text/css" />';
			$tags[] = $tag;
		}
		
		return implode("\n", $tags);
	}
	
	public function javascript_include_tag($sources)
	{
		$urls = $this->expand_asset_sources('javascript', $sources);
		
		$tags = array();
		foreach ($urls as $url)
		{
			$tags[] = '<script src="'.$url.'" type="text/javascript"></script>';
		}
		
		return implode("\n", $tags);
	}
	
	public function feed_link_tag($url, $type = 'rss', $options = array())
	{
		$options = array_merge(
			array('title' => strtoupper($type)),
			$options);
		
		return '<link href="'.$url.'" rel="alternate" title="'.$options['title'].'" type="'.self::$feed_types[$type].'" />';
	}
	
	public function set_stylesheet_path($path)
	{
		$this->set_default_asset_path('stylesheet', $path);
	}
	
	public function set_javascript_path($path)
	{
		$this->set_default_asset_path('javascript', $path);
	}
	
	public function register_stylesheet_expansion($name, $urls)
	{
		$this->register_expansion('stylesheet', $name, $urls);
	}
	
	public function register_javascript_expansion($name, $urls)
	{
		$this->register_expansion('javascript', $name, $urls);
	}
	
	private function set_default_asset_path($type, $path)
	{
		$this->asset_paths[$type] = $path;
	}
	
	private function register_expansion($type, $name, $urls)
	{
		$this->expansions[$type][$name] = (array)$urls;
	}
	
	private function get_asset_path($type, $url)
	{
		return (substr($url, 0, 1) != '/') ? $this->asset_paths[$type].'/'.$url : $url;
	}
	
	private function expand_asset_sources($type, $sources)
	{
		$paths = array();
		
		if (isset($this->expansions[$type][$sources]))
		{
			$sources = $this->expansions[$type][$sources];
		}

		if (is_array($sources))
		{
			foreach ($sources as $u)
			{
				$paths = array_merge($paths, $this->expand_asset_sources($type, $u));
			}
		}
		else
		{
			$paths[] = $this->get_asset_path($type, $sources);
		}
		
		return $paths;
	}
}
?>