<?php
namespace Tingle\Tests\Helper;

use Tingle\Tests\BaseTest;
use Tingle\Helper\DateHelper;

class DateHelperTest extends BaseTest
{
    private $date = 'Mon, 15 Aug 05 15:52:01 +0000';

    public function test_date_with_default_format()
    {
        $this->assertEquals('Mon, 15 Aug 05 15:52:01 +0000', DateHelper::format_date(new \DateTime($this->date)));
    }

    public function test_date_with_format()
    {
        $this->assertEquals('08/15/2005', DateHelper::format_date(new \DateTime($this->date), 'm/d/Y'));
    }

    public function test_date_string()
    {
        $this->assertEquals('Mon, 15 Aug 05 15:52:01 +0000', DateHelper::format_date($this->date));
    }

    public function test_datetime_tag_defaults()
    {
        $actual = DateHelper::datetime_tag($this->date);
        $matcher = array('tag' => 'abbr',
                         'attributes' => array(
                             'title' => '2005-08-15T15:52:01+0000'),
                                          'content' => 'Mon, 15 Aug 05 15:52:01 +0000');
        $this->assertTag($matcher, $actual, 'Default attributes');
    }

    public function test_datetime_tag_with_format()
    {
        $actual = DateHelper::datetime_tag($this->date, 'm/d/Y');
        $matcher = array('tag' => 'abbr',
                         'attributes' => array(
                             'title' => '2005-08-15T15:52:01+0000'),
                                         'content' => '08/15/2005');
        $this->assertTag($matcher, $actual, 'format specified');
    }

    public function test_datetime_tag_with_html_attributes()
    {
        $actual = DateHelper::datetime_tag($this->date, 'm/d/Y', array('class' => 'date'));
        $matcher = array('tag' => 'abbr',
                         'attributes' => array(
                                           'class' => 'date',
                             'title' => '2005-08-15T15:52:01+0000'),
                                         'content' => '08/15/2005');
        $this->assertTag($matcher, $actual, 'additional attributes specified');
    }

    public function test_datetime_tag_overwrites_specified_title_attribute()
    {
        $actual = DateHelper::datetime_tag($this->date, 'm/d/Y', array('title' => 'expendable'));
        $matcher = array('tag' => 'abbr',
                         'attributes' => array(
                             'title' => '2005-08-15T15:52:01+0000'),
                                         'content' => '08/15/2005');
        $this->assertTag($matcher, $actual, 'title attribute specified');
    }
}
