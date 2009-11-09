<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/Tingle.php';

use Tingle\CaptureHelper, Tingle\CaptureContent;

class CaptureHelperTest extends PHPUnit_Framework_TestCase
{
	public function test_content_for_should_return_content()
	{
		$this->assertTrue(CaptureHelper::content_for('capture') instanceof CaptureContent);
	}
}
?>
