<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/helpers/TagHelper.php';

class TagHelperTest extends PHPUnit_Framework_TestCase
{
	public function test_escape_once()
	{
		$this->assertEquals('cut &amp; paste', Tingle_TagHelper::escape_once('cut & paste'));
	}
	
	public function test_prevent_double_escapes()
	{
		$this->assertEquals('cut &amp; paste', Tingle_TagHelper::escape_once('cut &amp; paste'));
	}
	
	public function test_tag()
	{
		$this->assertEquals('<img src="blah.gif" alt="cut &amp; paste" />', Tingle_TagHelper::tag('img', array('src' => 'blah.gif', 'alt' => 'cut & paste')));
	}
	
	public function test_content_tag()
	{
		$this->assertEquals('<div class="test">Test</div>', Tingle_TagHelper::content_tag('div', 'Test', array('class' => 'test')));
	}
	
	public function test_tag_without_name()
	{
		$this->assertEquals('', Tingle_TagHelper::tag(''));
	}
}
?>
