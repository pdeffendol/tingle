<?php
namespace Tingle\Tests;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function getTemplatePath()
    {
        return realpath(__DIR__ . '/templates');
    }
}
