<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/helpers/CaptureHelper.php';

class CaptureHelperTest extends PHPUnit_Framework_TestCase
{
	public function test_content_for_should_return_content()
	{
		$this->assertTrue(Tingle_CaptureHelper::content_for('capture') instanceof Tingle_CaptureContent);
	}
}
?>
