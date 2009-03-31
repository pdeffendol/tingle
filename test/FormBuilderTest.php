<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/FormBuilder.php';

class FormBuilderTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$obj = new StdClass;
		$obj->foo = 'bar';
		$data = array(
			'string_field' => 'foo',
			'true_field' => true,
			'false_field' => false,
			'hash_field' => array(
				'foo' => 'bar'
				),
			'array_field' => array('foo', 'bar'),
			'object_field' => $obj
			);
		$this->builder = new Tingle_FormBuilder($data);
	}
	
	public function test_checkbox()
	{
		$actual = $this->builder->checkbox('true_field');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'checkbox',
			                 'name' => 'true_field',
			                 'value' => '1',
			                 'checked' => 'checked'));
		$this->assertTag($matcher, $actual, 'Checkbox should be checked for true value');
		
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'hidden',
			                 'name' => 'true_field',
			                 'value' => '0'));
		$this->assertTag($matcher, $actual, 'Checkbox should include hidden field to ensure submitted value');
		
		$actual = $this->builder->checkbox('false_field');
		$matcher['attributes']['name'] = 'false_field';
		$matcher['attributes']['checked'] = null;
		$this->assertTag($matcher, $actual, 'Checkbox should not be checked for false value');
	}
	
	public function test_file_field()
	{
		$actual = $this->builder->file_field('file');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'file',
			                 'name' => 'file'));
		$this->assertTag($matcher, $actual);
	}
	
	public function test_grouped_checkbox()
	{
		$actual = $this->builder->grouped_checkbox('array_field', 'foo');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'checkbox',
			                 'name' => 'array_field[]',
			                 'value' => 'foo',
			                 'checked' => 'checked'));
		$this->assertTag($matcher, $actual, 'Checkbox should be checked for value in array');

		$actual = $this->builder->grouped_checkbox('array_field[]', 'foo');
		$this->assertTag($matcher, $actual, 'Should accept empty brackets in name');

		$actual = $this->builder->grouped_checkbox('array_field', 'bogus');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'checkbox',
			                 'name' => 'array_field[]',
			                 'value' => 'bogus',
			                 'checked' => null));
		$this->assertTag($matcher, $actual, 'Checkbox should not be checked for value missing from array');
	}
	
	public function test_hidden_field()
	{
		$actual = $this->builder->hidden_field('string_field');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'hidden',
			                 'name' => 'string_field',
			                 'value' => 'foo'));
		$this->assertTag($matcher, $actual);
	}
	
	public function test_label()
	{
		$actual = $this->builder->label('string_field', 'String');
		$matcher = array('tag' => 'label', 
		                 'attributes' => array(
		                 'for' => 'string_field'),
		                 'content' => 'String');
		$this->assertTag($matcher, $actual);
	}
	
	public function test_password_field()
	{
		$actual = $this->builder->password_field('string_field');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'password',
			                 'name' => 'string_field',
			                 'value' => 'foo'));
		$this->assertTag($matcher, $actual);
	}
	
	public function test_radio_button()
	{
		$actual = $this->builder->radio_button('string_field', 'foo');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'radio',
			                 'name' => 'string_field',
			                 'value' => 'foo',
			                 'checked' => 'checked'));
		$this->assertTag($matcher, $actual, 'Radio button matching value should be checked');

		$actual = $this->builder->radio_button('string_field', 'bar');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'radio',
			                 'name' => 'string_field',
			                 'value' => 'bar',
			                 'checked' => null));
		$this->assertTag($matcher, $actual, 'Radio button not matching value should not be checked');
	}
	
	public function test_text_area()
	{
		$actual = $this->builder->text_area('string_field');
		$matcher = array('tag' => 'textarea', 
		                 'attributes' => array(
			                 'name' => 'string_field'),
			               'content' => 'foo');
		$this->assertTag($matcher, $actual);
	}
	
	public function test_text_field()
	{
		$actual = $this->builder->text_field('string_field');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'text',
			                 'name' => 'string_field',
			                 'value' => 'foo'));
		$this->assertTag($matcher, $actual, 'Should populate value from single-value key');

		$actual = $this->builder->text_field('bogus_field');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'text',
			                 'name' => 'bogus_field',
			                 'value' => ''));
		$this->assertTag($matcher, $actual, 'Should populate empty value from non-existent key');

		$actual = $this->builder->text_field('hash_field[foo]');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'text',
			                 'name' => 'hash_field[foo]',
			                 'value' => 'bar'));
		$this->assertTag($matcher, $actual, 'Should populate value from a nested hash key');

		$actual = $this->builder->text_field('array_field[1]');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'text',
			                 'name' => 'array_field[1]',
			                 'value' => 'bar'));
		$this->assertTag($matcher, $actual, 'Should populate value from a nested array index');

		$actual = $this->builder->text_field('object_field[foo]');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'text',
			                 'name' => 'object_field[foo]',
			                 'value' => 'bar'));
		$this->assertTag($matcher, $actual, 'Should populate value from an object key');
	}
}
?>
