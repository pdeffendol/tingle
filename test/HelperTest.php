<?php
namespace Tingle\Tests;

use Tingle\Template;

class TestHelper
{
    public static function do_something()
    {
        return 'Hello';
    }

    public static function do_something_with_param($text)
    {
        return $text;
    }

    private static function not_available()
    {

    }
}

class HelperTest extends BaseTest
{
    protected function setUp()
    {
        $this->tpl = new Template;
    }

    public function test_should_register_helper()
    {
        $this->assertTrue($this->tpl->register_helper('Tingle\Tests\TestHelper'));

        $this->assertContains('do_something', $this->tpl->get_registered_helpers());
        $this->assertContains('do_something_with_param', $this->tpl->get_registered_helpers());
    }

    public function test_should_not_register_constructor()
    {
        $this->tpl->register_helper('Tingle\Tests\TestHelper');

        $this->assertNotContains('__construct', $this->tpl->get_registered_helpers());
    }

    public function test_should_not_register_private_methods()
    {
        $this->tpl->register_helper('Tingle\Tests\TestHelper');

        $this->assertNotContains('not_available', $this->tpl->get_registered_helpers());
    }

    public function test_should_not_register_invalid_helper()
    {
        $this->setExpectedException('Tingle\Exception\InvalidHelperClass');
        $this->tpl->register_helper('ImaginaryHelper');
    }

    public function test_should_call_helper_method()
    {
        $this->tpl->register_helper('Tingle\Tests\TestHelper');
        $this->assertEquals('Hello', $this->tpl->do_something());
    }

    public function test_should_call_helper_method_with_params()
    {
        $this->tpl->register_helper('Tingle\Tests\TestHelper');
        $this->assertEquals('Hello', $this->tpl->do_something_with_param('Hello'));
    }

    public function test_should_handle_bad_helpers()
    {
        $this->setExpectedException('Tingle\Exception\HelperMethodNotDefined');
        $this->tpl->bogus_helper();
    }
}
