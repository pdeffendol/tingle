#!/usr/bin/env php
<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
	define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

// Tests
require_once dirname(__FILE__).'/AssignmentTest.php';
require_once dirname(__FILE__).'/SimpleTemplateTest.php';
require_once dirname(__FILE__).'/NestedTemplateTest.php';
require_once dirname(__FILE__).'/HelperTest.php';
require_once dirname(__FILE__).'/CaptureHelperTest.php';
require_once dirname(__FILE__).'/CaptureHelperContentTest.php';

class AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}
 
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Tingle');

		$suite->addTestSuite('AssignmentTest');
		$suite->addTestSuite('SimpleTemplateTest');
		$suite->addTestSuite('NestedTemplateTest');
		$suite->addTestSuite('HelperTest');
		$suite->addTestSuite('CaptureHelperTest');
		$suite->addTestSuite('CaptureHelperContentTest');
 
		return $suite;
	}
}
 
if (PHPUnit_MAIN_METHOD == 'AllTests::main') {
	AllTests::main();
}
?>
