<?php
namespace Tingle\Tests\Helper;

use Tingle\Tests\BaseTest;
use Tingle\Helper\TextHelper;

class TextHelperTest extends BaseTest
{
    public function test_pluralize_should_retain_singular_for_one_item()
    {
        $this->assertEquals('1 item', TextHelper::pluralize(1, 'item'));
    }

    public function test_pluralize_should_pluralize_for_multiple_items()
    {
        $this->assertEquals('2 items', TextHelper::pluralize(2, 'item'));
    }

    public function test_pluralize_should_format_number()
    {
        $this->assertEquals('3001.00 items', TextHelper::pluralize(3001, 'item', "%.2f"));
    }

    public function test_truncate()
    {
        $text = "This text is too long";

        $this->assertEquals($text, TextHelper::truncate($text, strlen($text)), 'No truncation if text is short enough');
        $this->assertEquals('This text...', TextHelper::truncate($text, 12), 'Default truncation options');
        $this->assertEquals('This text!', TextHelper::truncate($text, 12, '!'), 'Different replacement text');
        $this->assertEquals('This te...', TextHelper::truncate($text, 10, '...', true), 'Break words');
    }

    public function test_cycle_with_one_value()
    {
        $this->assertEquals('only', TextHelper::cycle('only'), 'First call returns value');
        $this->assertEquals('only', TextHelper::cycle('only'), 'Second call returns same value');
    }

    public function test_cycle_with_multiple_values()
    {
        $this->assertEquals('one', TextHelper::cycle('one', 'two'), 'First call returns first value');
        $this->assertEquals('two', TextHelper::cycle('one', 'two'), 'Second call returns second value');
        $this->assertEquals('one', TextHelper::cycle('one', 'two'), 'Third call returns first value');
    }

    public function test_changing_values_creates_new_cycle()
    {
        $throwaway = TextHelper::cycle('one', 'two');
        $this->assertEquals('first', TextHelper::cycle('first', 'second'));
    }

    public function test_named_cycle()
    {
        $this->assertEquals('only', TextHelper::cycle('only', array('name' => 'named')), 'First call returns value');
        $this->assertEquals('only', TextHelper::cycle('only', array('name' => 'named')), 'Second call returns same value');
    }

    public function test_named_cycle_concurrency()
    {
        $throwaway = TextHelper::cycle('only', array('name' => 'concurrent_1'));
        $this->assertEquals('one', TextHelper::cycle('one', 'two', array('name' => 'concurrent_2')));
        $this->assertEquals('only', TextHelper::cycle('only', array('name' => 'concurrent_1')));
        $this->assertEquals('two', TextHelper::cycle('one', 'two', array('name' => 'concurrent_2')));
    }

    public function test_reset_cycle_default()
    {
        $throwaway = TextHelper::cycle('one', 'two');
        TextHelper::reset_cycle();
        $this->assertEquals('one', TextHelper::cycle('one', 'two'));
    }

    public function test_reset_cycle_named()
    {
        $throwaway = TextHelper::cycle('one', 'two', array('name' => 'reset_named'));
        TextHelper::reset_cycle('reset_named');
        $this->assertEquals('one', TextHelper::cycle('one', 'two', array('name' => 'reset_named')));
    }

    public function test_current_cycle_default()
    {
      TextHelper::reset_cycle();
        $throwaway = TextHelper::cycle('one', 'two');
        $this->assertEquals('one', $throwaway);
    // $this->assertEquals('one', TextHelper::current_cycle());
    }

    public function test_current_cycle_named()
    {
        $throwaway = TextHelper::cycle('one', 'two', array('name' => 'current_cycle_named'));
        $this->assertEquals('one', TextHelper::current_cycle('current_cycle_named'));
    }

    public function test_current_cycle_invalid()
    {
        $this->assertNull(TextHelper::current_cycle('invalid'));
    }
}
?>
