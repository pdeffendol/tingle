<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/helpers/FormHelper.php';

class MockBuilder extends Tingle_FormBuilder
{
	public function checkbox($name, $attributes = array(), $checked_value = '1', $unchecked_value = '0')
	{
		return 'mock form tag';
	}
	
	public function file_field($name, $attributes = array())
	{
		return 'mock form tag';
	}
	
	public function grouped_checkbox($name, $tag_value, $attributes = array())
	{
		return 'mock form tag';
	}

	public function hidden_field($name, $attributes = array())
	{
		return 'mock form tag';
	}

	public function label($name, $text, $attributes = array())
	{
		return 'mock form tag';
	}

	public function password_field($name, $attributes = array())
	{
		return 'mock form tag';
	}

	public function radio_button($name, $value, $attributes = array())
	{
		return 'mock form tag';
	}

	public function text_area($name, $attributes = array())
	{
		return 'mock form tag';
	}

	public function text_field($name, $attributes = array())
	{
		return 'mock form tag';
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
		$start_tag = Tingle_FormHelper::start_form_for(array());
		$this->assertEquals('<form method="post">', $start_tag);
	}
	
	public function test_start_form_for_with_custom_builder()
	{
		$start_tag = Tingle_FormHelper::start_form_for(array(), array('builder' => 'MockBuilder'));
		$this->assertEquals('<form method="post">', $start_tag);
	}
	
	public function test_start_form_for_with_invalid_builder()
	{
		$this->setExpectedException('Tingle_RenderingError');
		$start_tag = Tingle_FormHelper::start_form_for(array(), array('builder' => 'Bad'));
	}
	
	public function test_end_form_for()
	{
		Tingle_FormHelper::start_form_for(array(), array('builder' => 'MockBuilder'));
		$this->assertEquals('</form>', Tingle_FormHelper::end_form_for());		
	}
	
	public function test_field_without_builder()
	{
		$this->setExpectedException('Tingle_RenderingError');
		Tingle_FormHelper::checkbox('foo');
	}
	
	public function test_checkbox()
	{
		Tingle_FormHelper::start_form_for(array(), array('builder' => 'MockBuilder'));
		$this->assertEquals('mock form tag', Tingle_FormHelper::checkbox('foo'));
	}
	
	public function test_file_field()
	{
		Tingle_FormHelper::start_form_for(array(), array('builder' => 'MockBuilder'));
		$this->assertEquals('mock form tag', Tingle_FormHelper::file_field('foo'));
	}
	
	public function hidden_field()
	{
		Tingle_FormHelper::start_form_for(array(), array('builder' => 'MockBuilder'));
		$this->assertEquals('mock form tag', Tingle_FormHelper::hidden_field('foo'));
	}
	
	public function test_label()
	{
		Tingle_FormHelper::start_form_for(array(), array('builder' => 'MockBuilder'));
		$this->assertEquals('mock form tag', Tingle_FormHelper::label('foo'));
	}
	
	public function test_password_field()
	{
		Tingle_FormHelper::start_form_for(array(), array('builder' => 'MockBuilder'));
		$this->assertEquals('mock form tag', Tingle_FormHelper::password_field('foo'));
	}
	
	public function test_radio_button()
	{
		Tingle_FormHelper::start_form_for(array(), array('builder' => 'MockBuilder'));
		$this->assertEquals('mock form tag', Tingle_FormHelper::radio_button('foo', 'bar'));
	}
	
	public function test_text_area()
	{
		Tingle_FormHelper::start_form_for(array(), array('builder' => 'MockBuilder'));
		$this->assertEquals('mock form tag', Tingle_FormHelper::checkbox('foo'));
	}
	
	public function test_text_field()
	{
		Tingle_FormHelper::start_form_for(array(), array('builder' => 'MockBuilder'));
		$this->assertEquals('mock form tag', Tingle_FormHelper::checkbox('foo'));
	}
}
?>
