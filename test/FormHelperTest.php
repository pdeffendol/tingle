<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/Tingle.php';

use Tingle\FormBuilder;
use Tingle\FormHelper;

class CustomBuilder extends FormBuilder {}

class FormHelperTest extends PHPUnit_Framework_TestCase
{
	public function test_form_for()
	{
		$form = Tingle\FormHelper::form_for('foo', array());
		$this->assertTrue($form instanceof FormBuilder);
	}
	
	public function test_form_for_with_custom_builder()
	{
		$form = FormHelper::form_for('foo2', array(), array('builder' => 'CustomBuilder'));
		$this->assertTrue($form instanceof CustomBuilder);
	}
	
	public function test_form_for_with_bad_builder()
	{
		$this->setExpectedException('Tingle\RenderingError');
		$form = FormHelper::form_for('foo3', array(), array('builder' => 'BadBuilder'));
	}
	
	public function test_form_for_returns_existing_builder()
	{
		$form = FormHelper::form_for('foo4', array());
		$form2 = FormHelper::form_for('foo4');
		$this->assertEquals($form, $form2);
	}
}
?>
