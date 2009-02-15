<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/helpers/Text.php';

class TextHelperTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$template = $this->getMock('Tingle_Template');
		$this->helper = new Tingle_Helper_Text($template);
	}
	
	public function test_pluralize_should_retain_singular_for_one_item()
	{
		$this->assertEquals('1 item', $this->helper->pluralize(1, 'item'));
	}
	
	public function test_pluralize_should_pluralize_for_multiple_items()
	{
		$this->assertEquals('2 items', $this->helper->pluralize(2, 'item'));
	}
	
	public function test_pluralize_should_format_number()
	{
		$this->assertEquals('3001.00 items', $this->helper->pluralize(3001, 'item', "%.2f"));
	}
}
?>
