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
}
?>
