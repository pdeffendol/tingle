<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/helpers/Capture.php';

class CaptureHelperTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$template = $this->getMock('Tingle_Template');
		$this->helper = new Tingle_Helper_Capture($template);
	}
	
	public function test_content_for_should_return_content()
	{
		$this->assertTrue($this->helper->content_for('capture') instanceof Tingle_Helper_Capture_Content);
	}
}
?>
