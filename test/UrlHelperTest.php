<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/helpers/UrlHelper.php';

class UrlHelperTest extends PHPUnit_Framework_TestCase
{	
	public function test_link_to()
	{
		$this->assertEquals('<a href="test.html">Test</a>', Tingle_UrlHelper::link_to('Test', 'test.html'));
	}
	
	public function test_link_to_with_attributes()
	{
		$this->assertEquals('<a href="test.html" class="test">Test</a>', Tingle_UrlHelper::link_to('Test', 'test.html', array('class'=>'test')));
	}
	
	public function test_link_to_if_true()
	{
		$this->assertEquals('<a href="test.html">Test</a>', Tingle_UrlHelper::link_to_if(true, 'Test', 'test.html'));
	}
	
	public function test_link_to_if_false()
	{
		$this->assertEquals('Test', Tingle_UrlHelper::link_to_if(false, 'Test', 'test.html'));
	}
	
	public function test_link_to_unless_true()
	{
		$this->assertEquals('Test', Tingle_UrlHelper::link_to_unless(true, 'Test', 'test.html'));
	}
	
	public function test_link_to_unless_false()
	{
		$this->assertEquals('<a href="test.html">Test</a>', Tingle_UrlHelper::link_to_unless(false, 'Test', 'test.html'));
	}
}