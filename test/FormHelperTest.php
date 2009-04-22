<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/helpers/FormHelper.php';

class CustomBuilder extends Tingle_FormBuilder {}

class FormHelperTest extends PHPUnit_Framework_TestCase
{
	public function test_form_for()
	{
		$form = Tingle_FormHelper::form_for('foo', array());
		$this->assertType('Tingle_FormBuilder', $form);
	}
	
	public function test_form_for_with_custom_builder()
	{
		$form = Tingle_FormHelper::form_for('foo2', array(), array('builder' => 'CustomBuilder'));
		$this->assertType('CustomBuilder', $form);
	}
	
	public function test_form_for_with_bad_builder()
	{
		$this->setExpectedException('Tingle_RenderingError');
		$form = Tingle_FormHelper::form_for('foo3', array(), array('builder' => 'BadBuilder'));
	}
	
	public function test_form_for_returns_existing_builder()
	{
		$form = Tingle_FormHelper::form_for('foo4', array());
		$form2 = Tingle_FormHelper::form_for('foo4');
		$this->assertEquals($form, $form2);
	}
}
?>
