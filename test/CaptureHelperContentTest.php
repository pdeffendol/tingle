<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/helpers/Capture_Content.php';

class CaptureHelperContentTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->content = new Tingle_Helper_Capture_Content('test');
	}
	
	protected function setup_basic_capture()
	{
		$this->content->start();
		echo "Hello";
		$this->content->end();
	}
	
	public function test_should_start_empty()
	{
		$this->assertEquals('', strval($this->content));
	}
	
	public function test_should_not_use_prefix_when_empty()
	{
		$this->content->set_prefix('prefix');
		$this->assertEquals('', strval($this->content));
	}

	public function test_should_not_use_suffix_when_empty()
	{
		$this->content->set_suffix('suffix');
		$this->assertEquals('', strval($this->content));
	}

	public function test_should_not_use_separator_when_empty()
	{
		$this->content->set_separator(',');
		$this->assertEquals('', strval($this->content));
	}
	
	public function test_should_capture()
	{
		$this->setup_basic_capture();
		$this->assertEquals('Hello', strval($this->content));
	}

	public function test_should_prepend_prefix()
	{
		$this->setup_basic_capture();
		$this->content->set_prefix('.');
		$this->assertEquals('.Hello', strval($this->content));
	}
	
	public function test_should_append_suffix()
	{
		$this->setup_basic_capture();
		$this->content->set_suffix('.');
		$this->assertEquals('Hello.', strval($this->content));
	}
	
	public function test_should_not_use_separator_for_single_capture()
	{
		$this->setup_basic_capture();
		$this->content->set_separator('.');
		$this->assertEquals('Hello', strval($this->content));
	}
	
	public function test_should_allow_appending_captures()
	{
		$this->setup_basic_capture();
		$this->content->start('append');
		echo "again";
		$this->content->end();
		$this->assertEquals('Helloagain', strval($this->content));
	}
	
	public function test_should_use_separator_for_multiple_captures()
	{
		$this->setup_basic_capture();
		$this->content->set_separator(' ');
		$this->content->start('append');
		echo "again";
		$this->content->end();
		$this->assertEquals('Hello again', strval($this->content));
	}
	
	public function test_should_allow_direct_setting_of_content()
	{
		$this->content->set('Hello');
		$this->assertEquals('Hello', strval($this->content));
	}
	
	public function test_should_allow_direct_appending_of_content()
	{
		$this->content->append('Hello');
		$this->content->append(' again');
		$this->assertEquals('Hello again', strval($this->content));
	}

	public function test_should_not_allow_end_when_not_started()
	{
		$this->setExpectedException('Tingle_RenderingError');
		$this->content->end();
	}
	
	public function test_should_not_allow_concurrent_captures()
	{
		$this->setExpectedException('Tingle_RenderingError');
		$another_capture = new Tingle_Helper_Capture_Content('another');
		$this->content->start();
		$another_capture->start();
	}
	
	public function test_should_require_ending_capture()
	{
		$this->markTestIncomplete('Not sure how to test this');
	}
}
?>