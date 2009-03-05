#!/usr/bin/env php
<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
	define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

// Tests
require_once dirname(__FILE__).'/AssignmentTest.php';
require_once dirname(__FILE__).'/TemplateTest.php';
require_once dirname(__FILE__).'/LayoutTest.php';
require_once dirname(__FILE__).'/HelperTest.php';
require_once dirname(__FILE__).'/CaptureHelperTest.php';
require_once dirname(__FILE__).'/CaptureHelperContentTest.php';
require_once dirname(__FILE__).'/TextHelperTest.php';
require_once dirname(__FILE__).'/AssetTagHelperTest.php';

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
		$suite->addTestSuite('TemplateTest');
		$suite->addTestSuite('LayoutTest');
		$suite->addTestSuite('HelperTest');
		$suite->addTestSuite('CaptureHelperTest');
		$suite->addTestSuite('CaptureHelperContentTest');
		$suite->addTestSuite('TextHelperTest');
		$suite->addTestSuite('AssetTagHelperTest');
 
		return $suite;
	}
}
 
if (PHPUnit_MAIN_METHOD == 'AllTests::main') {
	AllTests::main();
}
?>
