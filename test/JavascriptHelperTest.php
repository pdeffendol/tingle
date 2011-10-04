<?php
require_once dirname(__FILE__).'/../lib/Tingle.php';

use Tingle\JavascriptHelper;

class JavascriptHelperTest extends PHPUnit_Framework_TestCase
{
	public function test_escape_javascript_quotes()
	{
		$script = "text = \"<p>'\\ \r\n \r \n</p>\"";
		$this->assertEquals("text \\' text", JavascriptHelper::escape_javascript("text ' text"), 'single quotes');
		$this->assertEquals("text \\\" text", JavascriptHelper::escape_javascript('text " text'), 'double quotes');
	}
	
	public function test_escape_javascript_newlines()
	{
		$this->assertEquals("text \\n text", JavascriptHelper::escape_javascript("text \r\n text"), 'carriage return + newline');
		$this->assertEquals("text \\n text", JavascriptHelper::escape_javascript("text \n text"), 'newline');
		$this->assertEquals("text \\n text", JavascriptHelper::escape_javascript("text \r text"), 'carriage return');
	}
	
	public function test_escape_javascript_slashes()
	{
		$this->assertEquals("text \\\\ text", JavascriptHelper::escape_javascript("text \\ text"), 'backslashes');
		$this->assertEquals("text <\/p> text", JavascriptHelper::escape_javascript("text </p> text"), 'forward slashes in closing HTML tags');
	}
}
?>
