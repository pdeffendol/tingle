<?php
require_once dirname(__FILE__).'/Exception.php';

class Tingle_Cycle
{
	/**
	 * Array of values through which to cycle
	 * @var array
	 */
	private $values;
	
	/** 
	 * Array index from which to pull next value
	 * @var integer
	 */
	private $index;
	
	/**
	 * Constructor.
	 *
	 * @param string $first_value First in a list of values to cycle
	 */
	function __construct($first_value) 
	{
		$values = func_get_args();
		if (count($values) == 1 && is_array($values[0]))
		{
			$values = $values[0];
		}
		$this->values = $values;
		$this->reset();
	}
	
	public function __get($name)
	{
		switch ($name)
		{
			case 'values':
				return $this->values;
				break;
		}
	}
	
	public function __toString()
	{
		return $this->next_value();
	}
	
	public function reset()
	{
		$this->index = 0;
	}
	
	public function current_value()
	{
		return $this->values[$this->previous_index()];
	}
	
	public function next_value()
	{
		$value = $this->values[$this->index];
		$this->index = $this->next_index();
		return $value;
	}
	
	private function next_index()
	{
		return $this->incremented_index(1);
	}
	
	private function previous_index()
	{
		return $this->incremented_index(-1);
	}
	
	private function incremented_index($delta)
	{
		return ($this->index + $delta) % count($this->values);
	}
}
?>