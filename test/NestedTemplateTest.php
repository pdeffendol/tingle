<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/Template.php';

class NestedTemplateTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->tpl = new Tingle_Template;
		$this->tpl->set_template_path(dirname(__FILE__).'/templates');
		$this->tpl->data = 'Hello';
	}
	
	public function test_should_allow_nested_templates()
	{
		$this->assertEquals('Nesting: Data: Hello', $this->tpl->render('nested.tpl'));
	}
	
	public function test_should_fail_on_missing_nested_template()
	{
		$this->setExpectedException('Tingle_TemplateNotFoundException');

		$result = $this->tpl->render('nested_error.tpl');
	}
}
?>
