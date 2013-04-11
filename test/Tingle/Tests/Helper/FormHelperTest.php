<?php
namespace Tingle\Tests\Helper;

use Tingle\Tests\BaseTest;
use Tingle\FormBuilder;
use Tingle\Helper\FormHelper;

class CustomBuilder extends FormBuilder {}

class FormHelperTest extends BaseTest
{
    public function test_form_for()
    {
        $form = FormHelper::form_for('foo', array());
        $this->assertTrue($form instanceof FormBuilder);
    }

    public function test_form_for_with_custom_builder()
    {
        $form = FormHelper::form_for('foo2', array(), array('builder' => __NAMESPACE__.'\CustomBuilder'));
        $this->assertTrue($form instanceof CustomBuilder);
    }

    public function test_form_for_with_bad_builder()
    {
        $this->setExpectedException('Tingle\Exception\RenderingError');
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
