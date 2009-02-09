<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/Template.php';

class SimpleTemplateTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->tpl = new Tingle_Template;
		$this->tpl->set_template_path(dirname(__FILE__).'/templates');
		$this->tpl->data = 'Hello';
	}
	
	public function test_should_fail_on_missing_templates()
	{
		$this->setExpectedException('Tingle_TemplateNotFoundException');
		
		$result = $this->tpl->render('bad_template.tpl');
	}
	
	public function test_should_render_template()
	{
		$this->assertEquals('Data: Hello', $this->tpl->render('basic.tpl'));
	}
	
	public function test_should_render_templates_in_folders()
	{
		$this->assertEquals('Hello, this template is in a folder.', $this->tpl->render('more/basic.tpl'));
	}
	
	public function test_should_not_allow_templates_outside_path()
	{
		$this->setExpectedException('Tingle_TemplateNotFoundException');

		$this->tpl->set_template_path('templates/more');
		
		// Make sure template is readable by other means
		$this->assertTrue(file_exists('templates/more/../basic.tpl'));
		
		// Tingle should not allow this template to be rendered
		$result = $this->tpl->render('../basic.tpl');
	}
	
	public function test_should_set_default_template_path_to_current_dir()
	{
		$this->tpl->set_template_path(null);
		$this->assertEquals('Data: Hello', $this->tpl->render('templates/basic.tpl'));
	}
	
	public function test_should_allow_setting_template_before_render()
	{
		$this->tpl->set_template('basic.tpl');
		$this->assertEquals('Data: Hello', $this->tpl->render());
	}
	
	public function test_should_cast_to_string()
	{
		$this->tpl->set_template('basic.tpl');
		$this->assertEquals('Data: Hello', strval($this->tpl));
	}
	
	public function test_should_cast_to_empty_string_without_template()
	{
		$this->assertEquals('', strval($this->tpl));
	}
	
	public function test_should_allow_extracted_variables()
	{
		$this->tpl->set_extract_vars(true);
		$this->assertEquals('Data: Hello', $this->tpl->render('extracted.tpl'));
	}
	
	public function test_should_not_extract_configuration()
	{
		$this->tpl->set_extract_vars(true);
		$this->assertEquals('something: [Hello] empty: []', $this->tpl->render('extracted_no_config.tpl'));
	}
}
?>
