<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/Tingle.php';

use Tingle\UrlHelper;

class UrlHelperTest extends PHPUnit_Framework_TestCase
{	
	public function test_link_to()
	{
		$this->assertEquals('<a href="test.html">Test</a>', UrlHelper::link_to('Test', 'test.html'));
	}
	
	public function test_link_to_with_attributes()
	{
		$tag = UrlHelper::link_to('Test', 'test.html', array('class'=>'test'));
		$this->assertRegexp('/class="test"/', $tag);
	}
	
	public function test_link_to_if_true()
	{
		$this->assertEquals('<a href="test.html">Test</a>', UrlHelper::link_to_if(true, 'Test', 'test.html'));
	}
	
	public function test_link_to_if_false()
	{
		$this->assertEquals('Test', UrlHelper::link_to_if(false, 'Test', 'test.html'));
	}
	
	public function test_link_to_if_content()
	{
		$this->assertEquals('<a href="test.html">Test</a>', UrlHelper::link_to_if_content('Test', 'test.html'), 'Link with a label');
		$this->assertEquals('', UrlHelper::link_to_if_content('', 'test.html'), 'Link without a label');
	}
	
	public function test_link_to_unless_true()
	{
		$this->assertEquals('Test', UrlHelper::link_to_unless(true, 'Test', 'test.html'));
	}
	
	public function test_link_to_unless_false()
	{
		$this->assertEquals('<a href="test.html">Test</a>', UrlHelper::link_to_unless(false, 'Test', 'test.html'));
	}
	
	public function test_mail_to()
	{
		$this->assertEquals('<a href="mailto:test@example.com">Test</a>', UrlHelper::mail_to('test@example.com', 'Test'), 'Mailto with label');
		$this->assertEquals('<a href="mailto:test@example.com">test@example.com</a>', UrlHelper::mail_to('test@example.com'), 'Mailto without label defaults to address');
		$this->assertEquals('<a class="test" href="mailto:test@example.com">Test</a>', UrlHelper::mail_to('test@example.com', 'Test', array('class' => 'test')), 'Mailto with additional options');
	}
	
	public function test_mail_to_hex_escape()
	{
		$this->assertEquals('<a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;%74%65%73%74@%65%78%61%6d%70%6c%65.%63%6f%6d">&#x54;&#x65;&#x73;&#x74;</a>', UrlHelper::mail_to('test@example.com', 'Test', array('encode' => 'hex')), 'Mailto with hex encoding');
	}
}