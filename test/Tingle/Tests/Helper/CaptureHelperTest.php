<?php
namespace Tingle\Tests\Helper;

use Tingle\Tests\BaseTest;
use Tingle\Helper\CaptureHelper;
use Tingle\CaptureContent;

class CaptureHelperTest extends BaseTest
{
    public function test_content_for_should_return_content()
    {
        $this->assertTrue(CaptureHelper::content_for('capture') instanceof CaptureContent);
    }
}
?>
