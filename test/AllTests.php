#!/usr/bin/env php
<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
	define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

class AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}
 
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('Tingle');

		$tests = array(
			'AssignmentTest',
			'TemplateTest',
			'LayoutTest',
			'HelperTest',
			'CaptureHelperTest',
			'CaptureContentTest',
			'TextHelperTest',
			'TagHelperTest',
			'UrlHelperTest',
			'AssetTagHelperTest',
			'FormTagHelperTest');
		
		foreach ($tests as $test)
		{
			include_once dirname(__FILE__)."/{$test}.php";
			$suite->addTestSuite($test);
		}
 
		return $suite;
	}
}
 
if (PHPUnit_MAIN_METHOD == 'AllTests::main') {
	AllTests::main();
}
?>
