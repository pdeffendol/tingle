<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/helpers/FormHelper.php';

class MockBuilder extends Tingle_FormBuilder
{
	public function checkbox($name, $html_attributes = array(), $checked_value = '1', $unchecked_value = '0')
	{
		return 'checkbox';
	}
	
	public function file_field($name, $html_attributes = array())
	{
		return 'file field';
	}
	
	public function grouped_checkbox($name, $tag_value, $html_attributes = array())
	{
		return 'grouped checkbox';
	}

	public function hidden_field($name, $html_attributes = array())
	{
		return 'hidden field';
	}

	public function label($name, $text, $html_attributes = array())
	{
		return 'label';
	}

	public function password_field($name, $html_attributes = array())
	{
		return 'password field';
	}

	public function radio_button($name, $value, $html_attributes = array())
	{
		return 'radio button';
	}
	
	public function select($name, $value, $options = array(), $html_attributes = array())
	{
		return 'select';
	}

	public function text_area($name, $html_attributes = array())
	{
		return 'text area';
	}

	public function text_field($name, $html_attributes = array())
	{
		return 'text field';
	}
}

class FormHelperTest extends PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		Tingle_FormHelper::end_form_for();
	}
	
	public function test_start_form_for_with_default_builder()
	{
		$actual = Tingle_FormHelper::start_form_for(array(), 'test');
		$matcher = array('tag' => 'form',
		                 'attributes' => array(
			                 'action' => 'test',
			                 'method' => 'post'));
		$this->assertTag($matcher, $actual, 'Default attributes');
	}
	
	public function test_start_form_for_with_two_parameters()
	{
		$actual = Tingle_FormHelper::start_form_for(array(), array('action' => 'test'));
		$matcher = array('tag' => 'form',
		                 'attributes' => array(
			                 'action' => 'test',
			                 'method' => 'post'));
		$this->assertTag($matcher, $actual, 'Default attributes in one parameter');
	}

	public function test_start_form_for_with_custom_builder()
	{
		$actual = Tingle_FormHelper::start_form_for(array(), 'test', array('builder' => 'MockBuilder'));
		$matcher = array('tag' => 'form',
		                 'attributes' => array(
			                 'action' => 'test',
			                 'method' => 'post'));
		$this->assertTag($matcher, $actual);
	}
	
	public function test_start_form_for_with_invalid_builder()
	{
		$this->setExpectedException('Tingle_RenderingError');
		$start_tag = Tingle_FormHelper::start_form_for(array(), 'test', array('builder' => 'Bad'));
	}
	
	public function test_end_form_for()
	{
		Tingle_FormHelper::start_form_for(array(), 'test', array('builder' => 'MockBuilder'));
		$this->assertEquals('</form>', Tingle_FormHelper::end_form_for());		
	}
	
	public function test_field_without_builder()
	{
		$this->setExpectedException('Tingle_RenderingError');
		Tingle_FormHelper::checkbox('foo');
	}
	
	public function test_checkbox()
	{
		Tingle_FormHelper::start_form_for(array(), 'test', array('builder' => 'MockBuilder'));
		$this->assertEquals('checkbox', Tingle_FormHelper::checkbox('foo'));
	}
	
	public function test_file_field()
	{
		Tingle_FormHelper::start_form_for(array(), 'test', array('builder' => 'MockBuilder'));
		$this->assertEquals('file field', Tingle_FormHelper::file_field('foo'));
	}
	
	public function hidden_field()
	{
		Tingle_FormHelper::start_form_for(array(), 'test', array('builder' => 'MockBuilder'));
		$this->assertEquals('hidden field', Tingle_FormHelper::hidden_field('foo'));
	}
	
	public function test_label()
	{
		Tingle_FormHelper::start_form_for(array(), 'test', array('builder' => 'MockBuilder'));
		$this->assertEquals('label', Tingle_FormHelper::label('foo'));
	}
	
	public function test_password_field()
	{
		Tingle_FormHelper::start_form_for(array(), 'test', array('builder' => 'MockBuilder'));
		$this->assertEquals('password field', Tingle_FormHelper::password_field('foo'));
	}
	
	public function test_radio_button()
	{
		Tingle_FormHelper::start_form_for(array(), 'test', array('builder' => 'MockBuilder'));
		$this->assertEquals('radio button', Tingle_FormHelper::radio_button('foo', 'bar'));
	}
	
	public function test_select()
	{
		Tingle_FormHelper::start_form_for(array(), 'test', array('builder' => 'MockBuilder'));
		$this->assertEquals('select', Tingle_FormHelper::select('foo', 'bar'));
	}
	
	public function test_text_area()
	{
		Tingle_FormHelper::start_form_for(array(), 'test', array('builder' => 'MockBuilder'));
		$this->assertEquals('text area', Tingle_FormHelper::text_area('foo'));
	}
	
	public function test_text_field()
	{
		Tingle_FormHelper::start_form_for(array(), 'test', array('builder' => 'MockBuilder'));
		$this->assertEquals('text field', Tingle_FormHelper::text_field('foo'));
	}
}
?>
