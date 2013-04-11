<?php
namespace Tingle\Tests\Helper;

use Tingle\Tests\BaseTest;
use Tingle\Helper\TagHelper;

class TagHelperTest extends BaseTest
{
	public function test_escape_once()
	{
		$this->assertEquals('cut &amp; paste', TagHelper::escape_once('cut & paste'));
	}
	
	public function test_prevent_double_escapes()
	{
		$this->assertEquals('cut &amp; paste', TagHelper::escape_once('cut &amp; paste'));
	}
	
	public function test_tag()
	{
		$this->assertEquals('<div />', TagHelper::tag('div'), 'Empty closed tag');
		$this->assertEquals('<div>', TagHelper::tag('div', array(), true), 'Open tag');
	}
	
	public function test_content_tag()
	{
		$this->assertEquals('<div>Test</div>', TagHelper::content_tag('div', 'Test'));
	}
	
	public function test_tag_with_attributes()
	{
		$tag = TagHelper::tag('img', array('src' => 'blah.gif', 'alt' => 'blah'));
		$this->assertRegexp('/src="blah.gif"/', $tag);
		$this->assertRegexp('/alt="blah"/', $tag);
	}
	
	public function test_tag_without_name()
	{
		$this->assertEquals('', TagHelper::tag(''));
	}
	
	public function test_tag_ignores_null_attributes()
	{
		$this->assertEquals('<div />', TagHelper::tag('div', array('ignore' => null)));
	}
	
	public function test_tag_allows_blank_attributes()
	{
		$this->assertEquals('<div empty="" />', TagHelper::tag('div', array('empty' => '')));
	}
	
	public function test_tag_allows_boolean_attributes()
	{
		$this->assertEquals('<div disabled="disabled" multiple="multiple" readonly="readonly" />', TagHelper::tag('div', array('disabled' => true, 'multiple' => true, 'readonly' => true)));
	}
	
	public function test_tag_allows_false_attributes()
	{
		$this->assertEquals('<div value="false" />', TagHelper::tag('div', array('value' => false)));
	}
}
?>
