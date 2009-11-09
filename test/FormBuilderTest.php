<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/Tingle.php';

use Tingle\FormBuilder;

class TestBuilder extends FormBuilder {}

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
		$this->builder = new FormBuilder('data', $data, array('action' => 'foo'));
		$this->custom_builder = new TestBuilder('data', $data, array('action' => 'foo'));
	}
	
	public function test_start()
	{
		$result = $this->builder->start();
		$this->assertTrue($result instanceof FormBuilder);
		$matcher = array('tag' => 'form',
		                 'attributes' => array(
			                 'method' => 'post',
			                 'action' => 'foo'));
		$this->assertTag($matcher, strval($result));
	}
	
	public function test_start_with_attributes()
	{
		$result = $this->builder->start(array('action' => 'bar', 'method' => 'get'));
		$this->assertEquals($this->builder, $result, 'Return value is itself');
		$matcher = array('tag' => 'form',
		                 'attributes' => array(
			                 'method' => 'get',
			                 'action' => 'bar'));
		$this->assertTag($matcher, strval($result));
	}
	
	public function test_end()
	{
		$result = $this->builder->end();
		$this->assertEquals($this->builder, $result, 'Return value is itself');
		$this->assertEquals('</form>', strval($result), 'String value is form end tag');
	}
	
	public function test_string_value()
	{
		$this->assertEquals('', strval($this->builder));
	}
	
	public function test_common_calling_convention()
	{
		// start() will commonly be called in a template like this:
		// <?= $form = $this->form_for('foo', $this->foo, ...)->start() ? >
		$result = strval($form = $this->builder->start());
		$this->assertEquals($this->builder, $form, '$form gets assigned to builder');
		$matcher = array('tag' => 'form',
		                 'attributes' => array(
			                 'method' => 'post',
			                 'action' => 'foo'));
		$this->assertTag($matcher, strval($result), 'String value is form start tag');
	}
	
	public function test_checkbox()
	{
		$actual = $this->builder->checkbox('true_field');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'checkbox',
			                 'name' => 'data[true_field]',
			                 'value' => '1',
			                 'checked' => 'checked'));
		$this->assertTag($matcher, $actual, 'Checkbox should be checked for true value');
		
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'hidden',
			                 'name' => 'data[true_field]',
			                 'value' => '0'));
		$this->assertTag($matcher, $actual, 'Checkbox should include hidden field to ensure submitted value');
		
		$actual = $this->builder->checkbox('false_field');
		$matcher['attributes']['name'] = 'data[false_field]';
		$matcher['attributes']['checked'] = null;
		$this->assertTag($matcher, $actual, 'Checkbox should not be checked for false value');
	}
	
	public function test_fields_for()
	{
		$data = array('foo' => 'bar');
		$ff = $this->builder->fields_for('other_data', $data);
		$this->assertType(get_class($this->builder), $ff, 'Returns builder with same class as caller');
		
		$actual = $ff->text_field('foo');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'text',
			                 'name' => 'other_data[foo]',
			                 'value' => 'bar'));
		$this->assertTag($matcher, $actual, 'Produces correct form fields');
		
		$ff = $this->builder->fields_for('other_data', $data, array('builder' => 'TestBuilder'));
		$this->assertType('TestBuilder', $ff, 'Allows specifying builder class');
	}
	
	public function test_file_field()
	{
		$actual = $this->builder->file_field('file');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'file',
			                 'name' => 'data[file]'));
		$this->assertTag($matcher, $actual);
	}
	
	public function test_grouped_checkbox()
	{
		$actual = $this->builder->grouped_checkbox('array_field', 'foo');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'checkbox',
			                 'name' => 'data[array_field][]',
			                 'value' => 'foo',
			                 'checked' => 'checked'));
		$this->assertTag($matcher, $actual, 'Checkbox should be checked for value in array');

		$actual = $this->builder->grouped_checkbox('array_field[]', 'foo');
		$this->assertTag($matcher, $actual, 'Should accept empty brackets in name');

		$actual = $this->builder->grouped_checkbox('array_field', 'bogus');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'checkbox',
			                 'name' => 'data[array_field][]',
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
			                 'name' => 'data[string_field]',
			                 'value' => 'foo'));
		$this->assertTag($matcher, $actual);
	}
	
	public function test_label()
	{
		$actual = $this->builder->label('string_field', 'String');
		$matcher = array('tag' => 'label', 
		                 'attributes' => array(
		                 'for' => 'data_string_field'),
		                 'content' => 'String');
		$this->assertTag($matcher, $actual, 'Label text specified');

		$actual = $this->builder->label('string_field');
		$matcher = array('tag' => 'label', 
		                 'attributes' => array(
		                 'for' => 'data_string_field'),
		                 'content' => 'String Field');
		$this->assertTag($matcher, $actual, 'Default label text');
		
		$actual = $this->builder->label('array_field[Foo]', 'Foo');
		$matcher = array('tag' => 'label', 
		                 'attributes' => array(
		                 'for' => 'data_array_field_Foo'),
		                 'content' => 'Foo');
		$this->assertTag($matcher, $actual, 'Label on array field');
	}
	
	public function test_password_field()
	{
		$actual = $this->builder->password_field('string_field');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'password',
			                 'name' => 'data[string_field]',
			                 'value' => 'foo'));
		$this->assertTag($matcher, $actual);
	}
	
	public function test_radio_button()
	{
		$actual = $this->builder->radio_button('string_field', 'foo');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'radio',
			                 'name' => 'data[string_field]',
			                 'value' => 'foo',
			                 'checked' => 'checked'));
		$this->assertTag($matcher, $actual, 'Radio button matching value should be checked');

		$actual = $this->builder->radio_button('string_field', 'bar');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'radio',
			                 'name' => 'data[string_field]',
			                 'value' => 'bar',
			                 'checked' => null));
		$this->assertTag($matcher, $actual, 'Radio button not matching value should not be checked');
	}
	
	public function test_select()
	{
		$actual = $this->builder->select('string_field', array('bar' => 'Bar'));
		$matcher = array('tag' => 'select', 
		                 'attributes' => array(
			                 'name' => 'data[string_field]'),
		                 'descendant' => array(
			                 'tag' => 'option',
			                   'attributes' => array(
				                   'value' => 'bar',
				                   'selected' => null)));
		$this->assertTag($matcher, $actual, 'No options are selected when value doesn\'t match');
		
		$actual = $this->builder->select('string_field', array('foo' => 'Bar'));
		$matcher['descendant']['attributes']['value'] = 'foo';
		$matcher['descendant']['attributes']['selected'] = 'selected';
		$this->assertTag($matcher, $actual, 'Matching option is selected');
		
		$actual = $this->builder->select('bogus', array('foo' => 'Bar'), array('include_blank' => true));
		$matcher = array('tag' => 'option', 
		                 'attributes' => array(
			                 'value' => ''),
			               'content' => null);
		$this->assertTag($matcher, $actual, 'include_blank as true creates an empty option');
		
		$actual = $this->builder->select('bogus', array('foo' => 'Bar'), array('include_blank' => 'None'));
		$matcher = array('tag' => 'option', 
		                 'attributes' => array(
			                 'value' => ''),
			               'content' => 'None');
		$this->assertTag($matcher, $actual, 'include_blank as string creates an empty option with string as label');
		
		$actual = $this->builder->select('bogus', array('foo' => 'Bar'), array('prompt' => true));
		$matcher = array('tag' => 'option', 
		                 'attributes' => array(
			                 'value' => ''),
			               'content' => 'Select one...');
		$this->assertTag($matcher, $actual, 'prompt as true creates an empty option when no value is chosen');
		
		$actual = $this->builder->select('bogus', array('foo' => 'Bar'), array('prompt' => 'Choose'));
		$matcher = array('tag' => 'option', 
		                 'attributes' => array(
			                 'value' => ''),
			               'content' => 'Choose');
		$this->assertTag($matcher, $actual, 'prompt as string creates an empty option with string as label when no value is chosen');

		$actual = $this->builder->select('string_field', array('foo' => 'Bar'), array('prompt' => true));
		$matcher = array('tag' => 'select', 
		                 'children' => array(
			                 'count' => 1));
		$this->assertTag($matcher, $actual, 'prompt does not create an option when a value is chosen');
	}
	
	public function test_submit()
	{
		$actual = $this->builder->submit();
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'submit',
											 'value' => 'Save',
											 'name' => null));
		$this->assertTag($matcher, $actual, 'Default attributes');
		$this->assertNotContains('id=', $actual, 'No id attribute by default');
		
		$actual = $this->builder->submit('Update');
		$this->assertRegexp('/value="Update"/', $actual, "Setting button label");
		
		$actual = $this->builder->submit('Submit', array('class' => 'blah'));
		$this->assertRegexp('/class="blah"/', $actual, "Additional attributes");
	}
	
	public function test_text_area()
	{
		$actual = $this->builder->text_area('string_field');
		$matcher = array('tag' => 'textarea', 
		                 'attributes' => array(
			                 'name' => 'data[string_field]'),
			               'content' => 'foo');
		$this->assertTag($matcher, $actual);
	}
	
	public function test_text_field()
	{
		$actual = $this->builder->text_field('string_field');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'text',
			                 'name' => 'data[string_field]',
			                 'value' => 'foo'));
		$this->assertTag($matcher, $actual, 'Should populate value from single-value key');

		$actual = $this->builder->text_field('bogus_field');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'text',
			                 'name' => 'data[bogus_field]',
			                 'value' => ''));
		$this->assertTag($matcher, $actual, 'Should populate empty value from non-existent key');

		$actual = $this->builder->text_field('hash_field[foo]');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'text',
			                 'name' => 'data[hash_field][foo]',
			                 'value' => 'bar'));
		$this->assertTag($matcher, $actual, 'Should populate value from a nested hash key');

		$actual = $this->builder->text_field('array_field[1]');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'text',
			                 'name' => 'data[array_field][1]',
			                 'value' => 'bar'));
		$this->assertTag($matcher, $actual, 'Should populate value from a nested array index');

		$actual = $this->builder->text_field('object_field[foo]');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
		                 'type' => 'text',
			                 'name' => 'data[object_field][foo]',
			                 'value' => 'bar'));
		$this->assertTag($matcher, $actual, 'Should populate value from an object key');
	}
}
?>
