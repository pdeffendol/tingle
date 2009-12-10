<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/helpers/TextHelper.php';

class TextHelperTest extends PHPUnit_Framework_TestCase
{
	public function test_pluralize_should_retain_singular_for_one_item()
	{
		$this->assertEquals('1 item', Tingle_TextHelper::pluralize(1, 'item'));
	}
	
	public function test_pluralize_should_pluralize_for_multiple_items()
	{
		$this->assertEquals('2 items', Tingle_TextHelper::pluralize(2, 'item'));
	}
	
	public function test_pluralize_should_format_number()
	{
		$this->assertEquals('3001.00 items', Tingle_TextHelper::pluralize(3001, 'item', "%.2f"));
	}
	
	public function test_truncate()
	{
		$text = "This text is too long";
		
		$this->assertEquals($text, Tingle_TextHelper::truncate($text, strlen($text)), 'No truncation if text is short enough');
		$this->assertEquals('This text...', Tingle_TextHelper::truncate($text, 12), 'Default truncation options');
		$this->assertEquals('This text!', Tingle_TextHelper::truncate($text, 12, '!'), 'Different replacement text');
		$this->assertEquals('This te...', Tingle_TextHelper::truncate($text, 10, '...', true), 'Break words');
	}
	
	public function test_cycle_with_one_value()
	{
		$this->assertEquals('only', Tingle_TextHelper::cycle('only'), 'First call returns value');
		$this->assertEquals('only', Tingle_TextHelper::cycle('only'), 'Second call returns same value');
	}
	
	public function test_cycle_with_multiple_values()
	{
		$this->assertEquals('one', Tingle_TextHelper::cycle('one', 'two'), 'First call returns first value');
		$this->assertEquals('two', Tingle_TextHelper::cycle('one', 'two'), 'Second call returns second value');
		$this->assertEquals('one', Tingle_TextHelper::cycle('one', 'two'), 'Third call returns first value');
	}
	
	public function test_changing_values_creates_new_cycle()
	{
		$throwaway = Tingle_TextHelper::cycle('one', 'two');
		$this->assertEquals('first', Tingle_TextHelper::cycle('first', 'second'));
	}
	
	public function test_named_cycle()
	{
		$this->assertEquals('only', Tingle_TextHelper::cycle('only', array('name' => 'mine')), 'First call returns value');
		$this->assertEquals('only', Tingle_TextHelper::cycle('only', array('name' => 'mine')), 'Second call returns same value');
	}
	
	public function test_named_cycle_concurrency()
	{
		$throwaway = Tingle_TextHelper::cycle('only', array('name' => 'mine'));
		$this->assertEquals('one', Tingle_TextHelper::cycle('one', 'two', array('name' => 'numbers')));
		$this->assertEquals('only', Tingle_TextHelper::cycle('only', array('name' => 'mine')));
		$this->assertEquals('two', Tingle_TextHelper::cycle('one', 'two', array('name' => 'numbers')));
	}
	
	public function test_reset_cycle_default()
	{
		$throwaway = Tingle_TextHelper::cycle('one', 'two');
		Tingle_TextHelper::reset_cycle();
		$this->assertEquals('one', Tingle_TextHelper::cycle('one', 'two'));
	}
	
	public function test_reset_cycle_named()
	{
		$throwaway = Tingle_TextHelper::cycle('one', 'two', array('name' => 'numbers'));
		Tingle_TextHelper::reset_cycle('numbers');
		$this->assertEquals('one', Tingle_TextHelper::cycle('one', 'two', array('name' => 'numbers')));
	}
	
	public function test_current_cycle_default()
	{
		$throwaway = Tingle_TextHelper::cycle('one', 'two');
		$this->assertEquals('one', Tingle_TextHelper::current_cycle());
	}
	
	public function test_current_cycle_named()
	{
		$throwaway = Tingle_TextHelper::cycle('one', 'two', array('name' => 'numbers'));
		$this->assertEquals('one', Tingle_TextHelper::current_cycle('numbers'));
	}
	
	public function test_current_cycle_invalid()
	{
		$this->assertNull(Tingle_TextHelper::current_cycle('invalid'));
	}
}
?>
