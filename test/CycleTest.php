<?php
require_once dirname(__FILE__).'/../lib/Tingle.php';

use Tingle\Cycle;

class CycleTest extends PHPUnit_Framework_TestCase
{
	public function test_with_one_value()
	{
		$cycle = new Cycle('first');
		
		$this->assertEquals('first', $cycle->next_value());
		$this->assertEquals('first', $cycle->next_value());
	}
	
	public function test_values()
	{
		$cycle = new Cycle('first');
		$this->assertEquals(array('first'), $cycle->values);
	}
	
	public function test_passing_array_to_constructor()
	{
		$values = array('one', 'two');
		$cycle = new Cycle($values);
		$this->assertEquals($values, $cycle->values);
	}
	
	public function test_string_cast()
	{
		$cycle = new Cycle('first');
		
		$this->assertEquals('first', strval($cycle));
	}
	
	public function test_with_multiple_values()
	{
		$cycle = new Cycle('one', 'two');
		
		$cycle->next_value();
		$this->assertEquals('two', strval($cycle));
		$this->assertEquals('one', strval($cycle));
	}
	
	public function test_current_value()
	{
		$cycle = new Cycle('one', 'two');
		
		$cycle->next_value();
		$this->assertEquals('one', $cycle->current_value());
		$this->assertEquals('two', strval($cycle));
	}
	
	public function test_reset()
	{
		$cycle = new Cycle('one', 'two');
		
		$cycle->next_value();
		$cycle->reset();
		$this->assertEquals('one', strval($cycle));
	}
}
?>
