<?php
require_once dirname(__FILE__).'/../lib/Tingle.php';

use Tingle\Template;

class TemplateTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		try {
		$this->tpl = new Template;
		$this->tpl->set_template_path(dirname(__FILE__).'/templates');
		$this->tpl->data = 'Hello';
	} catch (Exception $e) { echo $e; print_r(get_declared_classes());}
	}
	
	public function test_should_locate_template()
	{
		$this->assertEquals(realpath(dirname(__FILE__).'/templates/basic.tpl'), $this->tpl->template('basic.tpl'));
	}
	
	public function test_should_not_locate_missing_template()
	{
		$this->assertFalse($this->tpl->template('bad_template.tpl'));
	}
	
	public function test_should_fail_on_missing_templates()
	{
		$this->setExpectedException('Tingle\TemplateNotFoundException');
		
		$result = $this->tpl->render('bad_template.tpl');
	}
	
	public function test_should_locate_templates_in_folders()
	{
		$this->assertEquals(realpath(dirname(__FILE__).'/templates/more/basic.tpl'), $this->tpl->template('more/basic.tpl'));
	}

	public function test_should_not_allow_templates_outside_path()
	{
		$this->tpl->set_template_path('templates/more');
		
		// Make sure template is readable by other means
		$this->assertTrue(file_exists(dirname(__FILE__).'/templates/more/../basic.tpl'));
		
		// Tingle should not allow this template to be rendered
		$this->assertFalse($this->tpl->template('../basic.tpl'));
	}
	
	public function test_should_set_default_template_path_to_current_dir()
	{
		$oldcwd = getcwd();
		chdir(dirname(__FILE__));
		$this->tpl->set_template_path(null);
		$this->assertEquals(dirname(__FILE__).'/templates/basic.tpl', $this->tpl->template('templates/basic.tpl'));
		chdir($oldcwd);
	}
	
	public function test_should_render_template()
	{
		$this->assertEquals('Data: Hello', $this->tpl->render('basic.tpl'));
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
	
	public function test_render_partial()
	{
		$this->tpl->partial = 'basic.tpl';
		$this->assertEquals('Data: Hello', $this->tpl->render('partial.tpl'));
	}
	
	public function test_render_partial_with_locals()
	{
		$this->tpl->partial = 'extracted.tpl';
		$this->assertEquals('Data: Goodbye', $this->tpl->render('partial_with_locals.tpl'), 'Locals are available in template as plain variables');
		
		$this->tpl->partial = 'basic.tpl';
		$this->assertEquals('Data: Hello', $this->tpl->render('partial_with_locals.tpl'), 'Locals do not override template variables');

		$this->tpl->partial = 'extracted.tpl';
		$this->tpl->set_extract_vars();
		$this->assertEquals('Data: Goodbye', $this->tpl->render('partial_with_locals.tpl'), 'Locals override extracted template variables');
	}
	
	public function test_render_partial_should_fail_on_missing_template()
	{
		$this->setExpectedException('Tingle\TemplateNotFoundException');

		$this->tpl->partial = 'bad_template.tpl';
		$result = $this->tpl->render('partial.tpl');
	}
}
?>
