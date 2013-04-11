<?php
namespace Tingle;

use Tingle\Exception\RenderingError;

class CaptureContent
{
	private $contents;
	private $prefix = '';
	private $suffix = '';
	private $separator = '';
	private $capture_mode;
	
	private static $capture_lock = false;
	
	const SET = "set";
	const APPEND = "append";
	
	public function __construct($name)
	{
		$this->name = $name;
		$this->contents = array();
	}
	
	public function set_prefix($prefix)
	{
		$this->prefix = $prefix;
		return $this;
	}
	
	public function set_suffix($suffix)
	{
		$this->suffix = $suffix;
		return $this;
	}
	
	public function set_separator($separator)
	{
		$this->separator = $separator;
		return $this;
	}
	
	public function __toString()
	{
		$result = '';
		if (count($this->contents) > 0)
		{
			$result = $this->prefix.implode($this->separator, $this->contents).$this->suffix;
		}
		
		return $result;
	}
	
	public function set($content)
	{
		$this->contents = array($content);
	}
	
	public function append($content)
	{
		$this->contents[] = $content;
	}
	
	public function start($mode = self::SET)
	{
		if (self::$capture_lock)
		{
			throw new RenderingError('Nested content_for captures not allowed.  (Already capturing for "'.self::$capture_lock.'")');
		}
		$this->capture_mode = $mode;
		self::$capture_lock = $this->name;
		ob_start();
	}
	
	public function end()
	{
		if (self::$capture_lock != $this->name)
		{
			throw new RenderingError('content_for capturing for "'.$this->name.'" not started');
		}
		
		$content = ob_get_clean();
		if ($this->capture_mode == self::SET)
		{
			$this->set($content);
		}
		else
		{
			$this->append($content);
		}
		
		self::$capture_lock = null;
	}
}
?>