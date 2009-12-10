<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/Tingle.php';

use Tingle\TextHelper;

class TextHelperTest extends PHPUnit_Framework_TestCase
{
	public function test_pluralize_should_retain_singular_for_one_item()
	{
		$this->assertEquals('1 item', TextHelper::pluralize(1, 'item'));
	}
	
	public function test_pluralize_should_pluralize_for_multiple_items()
	{
		$this->assertEquals('2 items', TextHelper::pluralize(2, 'item'));
	}
	
	public function test_pluralize_should_format_number()
	{
		$this->assertEquals('3001.00 items', TextHelper::pluralize(3001, 'item', "%.2f"));
	}
	
	public function test_truncate()
	{
		$text = "This text is too long";
		
		$this->assertEquals($text, TextHelper::truncate($text, strlen($text)), 'No truncation if text is short enough');
		$this->assertEquals('This text...', TextHelper::truncate($text, 12), 'Default truncation options');
		$this->assertEquals('This text!', TextHelper::truncate($text, 12, '!'), 'Different replacement text');
		$this->assertEquals('This te...', TextHelper::truncate($text, 10, '...', true), 'Break words');
	}
	
	public function test_cycle_with_one_value()
	{
		$this->assertEquals('only', TextHelper::cycle('only'), 'First call returns value');
		$this->assertEquals('only', TextHelper::cycle('only'), 'Second call returns same value');
	}
	
	public function test_cycle_with_multiple_values()
	{
		$this->assertEquals('one', TextHelper::cycle('one', 'two'), 'First call returns first value');
		$this->assertEquals('two', TextHelper::cycle('one', 'two'), 'Second call returns second value');
		$this->assertEquals('one', TextHelper::cycle('one', 'two'), 'Third call returns first value');
	}
	
	public function test_changing_values_creates_new_cycle()
	{
		$throwaway = TextHelper::cycle('one', 'two');
		$this->assertEquals('first', TextHelper::cycle('first', 'second'));
	}
	
	public function test_named_cycle()
	{
		$this->assertEquals('only', TextHelper::cycle('only', array('name' => 'mine')), 'First call returns value');
		$this->assertEquals('only', TextHelper::cycle('only', array('name' => 'mine')), 'Second call returns same value');
	}
	
	public function test_named_cycle_concurrency()
	{
		$throwaway = TextHelper::cycle('only', array('name' => 'mine'));
		$this->assertEquals('one', TextHelper::cycle('one', 'two', array('name' => 'numbers')));
		$this->assertEquals('only', TextHelper::cycle('only', array('name' => 'mine')));
		$this->assertEquals('two', TextHelper::cycle('one', 'two', array('name' => 'numbers')));
	}
	
	public function test_reset_cycle_default()
	{
		$throwaway = TextHelper::cycle('one', 'two');
		TextHelper::reset_cycle();
		$this->assertEquals('one', TextHelper::cycle('one', 'two'));
	}
	
	public function test_reset_cycle_named()
	{
		$throwaway = TextHelper::cycle('one', 'two', array('name' => 'numbers'));
		TextHelper::reset_cycle('numbers');
		$this->assertEquals('one', TextHelper::cycle('one', 'two', array('name' => 'numbers')));
	}
	
	public function test_current_cycle_default()
	{
		$throwaway = TextHelper::cycle('one', 'two');
		$this->assertEquals('one', TextHelper::current_cycle());
	}
	
	public function test_current_cycle_named()
	{
		$throwaway = TextHelper::cycle('one', 'two', array('name' => 'numbers'));
		$this->assertEquals('one', TextHelper::current_cycle('numbers'));
	}
	
	public function test_current_cycle_invalid()
	{
		$this->assertNull(TextHelper::current_cycle('invalid'));
	}
}
?>
