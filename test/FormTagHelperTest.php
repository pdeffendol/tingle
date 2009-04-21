<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/helpers/FormTagHelper.php';

class FormTagHelperTest extends PHPUnit_Framework_TestCase
{
	public function test_form_start_tag()
	{
		$actual = Tingle_FormTagHelper::start_form_tag('test');
		$matcher = array('tag' => 'form',
		                 'attributes' => array(
			                 'action' => 'test',
			                 'method' => 'post'));
		$this->assertTag($matcher, $actual, 'Default attributes');
		
		$actual = Tingle_FormTagHelper::start_form_tag(array('action' => 'test'));
		$this->assertTag($matcher, $actual, 'Default attributes in one parameter');
		
		$this->assertRegexp('/method="get"/', Tingle_FormTagHelper::start_form_tag('test', array('method' => 'get')), 'Setting additional attributes');
		$this->assertRegexp('/enctype="multipart\/form-data"/', Tingle_FormTagHelper::start_form_tag('test', array('multipart' => true)), 'Setting multipart');
	}
	
	public function test_form_tag()
	{
		$actual = Tingle_FormTagHelper::form_tag('test');
		$matcher = array('tag' => 'form',
		                 'attributes' => array(
			                 'action' => 'test',
			                 'method' => 'post'));
		$this->assertTag($matcher, $actual, 'Default attributes');
	}
	
	public function test_form_end_tag()
	{
		$this->assertEquals('</form>', Tingle_FormTagHelper::end_form_tag());
	}
	
	public function test_checkbox_tag()
	{
		$actual = Tingle_FormTagHelper::checkbox_tag('passed');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'checkbox',
			                 'name' => 'passed',
			                 'id' => 'passed',
			                 'value' => '1',
			                 'checked' => null));
		$this->assertTag($matcher, $actual, 'Default attributes');
		
		$actual = Tingle_FormTagHelper::checkbox_tag('passed', 1, true);
		$this->assertRegexp('/checked="checked"/', $actual, "Checked box");
		
		$actual = Tingle_FormTagHelper::checkbox_tag('foo', 1, false, array('class' => 'blah'));
		$this->assertRegexp('/class="blah"/', $actual, "Additional attributes");
	}
	
	public function test_file_field_tag()
	{
		$actual = Tingle_FormTagHelper::file_field_tag('upload');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'file',
											 'id' => 'upload',
			                 'name' => 'upload'));
		$this->assertTag($matcher, $actual, 'Default attributes');
	}
	
	public function test_hidden_field_tag()
	{
		$actual = Tingle_FormTagHelper::hidden_field_tag('foo', 'bar');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'hidden',
											 'value' => 'bar',
											 'id' => 'foo',
			                 'name' => 'foo'));
		$this->assertTag($matcher, $actual, 'Default attributes');
	}
	
	public function test_image_submit_tag()
	{
		$actual = Tingle_FormTagHelper::image_submit_tag('submit.gif');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'image',
			                 'name' => null,
											 'src' => 'submit.gif'));
		$this->assertTag($matcher, $actual, 'Default attributes');
		$this->assertNotContains('id=', $actual, 'No id attribute by default');
		
		$actual = Tingle_FormTagHelper::image_submit_tag('submit.gif', array('name' => 'foo'));
		$this->assertRegexp('/name="foo"/', $actual, "Additional attributes");
	}
	
	public function test_password_field_tag()
	{
		$actual = Tingle_FormTagHelper::password_field_tag('foo', 'bar');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'password',
											 'value' => 'bar',
											 'id' => 'foo',
			                 'name' => 'foo'));
		$this->assertTag($matcher, $actual, 'Default attributes');
	}
	
	public function test_radio_button_tag()
	{
		$actual = Tingle_FormTagHelper::radio_button_tag('passed', 'yes');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'radio',
			                 'name' => 'passed',
			                 'id' => 'passed_yes',
			                 'value' => 'yes',
			                 'checked' => null));
		$this->assertTag($matcher, $actual, 'Default attributes');
		
		$actual = Tingle_FormTagHelper::radio_button_tag('passed', 'yes', true);
		$this->assertRegexp('/checked="checked"/', $actual, "Checked radio button");
		
		$actual = Tingle_FormTagHelper::radio_button_tag('foo', 'bar', false, array('class' => 'blah'));
		$this->assertRegexp('/class="blah"/', $actual, "Additional attributes");	
	}

	public function test_select_tag()
	{
		$actual = Tingle_FormTagHelper::select_tag('foo');
		$matcher = array('tag' => 'select', 
		                 'attributes' => array(
			                 'name' => 'foo'),
			               'content' => null);
		$this->assertTag($matcher, $actual, 'Default attributes');
		
		$actual = Tingle_FormTagHelper::select_tag('foo', '<option>Bar</option>');
		$matcher = array('tag' => 'select', 
		                 'attributes' => array(
											 'id' => 'foo',
			                 'name' => 'foo'),
			               'descendant' => array(
				               'tag' => 'option',
				               'content' => 'Bar'));
		$this->assertTag($matcher, $actual, 'with options');
		
		$actual = Tingle_FormTagHelper::select_tag('foo', null, array('multiple' => true));
		$this->assertRegexp('/name="foo\[\]"/', $actual, "Multiple select");
		
		$actual = Tingle_FormTagHelper::select_tag('foo[]', null, array('multiple' => true));
		$this->assertRegexp('/name="foo\[\]"/', $actual, "Multiple select with brackets already in name");
		
		$actual = Tingle_FormTagHelper::select_tag('foo', null, array('class' => 'blah'));
		$this->assertRegexp('/class="blah"/', $actual, "Additional attributes");	
	}
	
	public function test_submit_tag()
	{
		$actual = Tingle_FormTagHelper::submit_tag();
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'submit',
											 'value' => 'Save',
											 'name' => null));
		$this->assertTag($matcher, $actual, 'Default attributes');
		$this->assertNotContains('id=', $actual, 'No id attribute by default');
		
		$actual = Tingle_FormTagHelper::submit_tag('Update');
		$this->assertRegexp('/value="Update"/', $actual, "Setting button label");
		
		$actual = Tingle_FormTagHelper::submit_tag('Submit', array('class' => 'blah'));
		$this->assertRegexp('/class="blah"/', $actual, "Additional attributes");
	}
	
	public function test_text_area_tag()
	{
		$actual = Tingle_FormTagHelper::text_area_tag('foo');
		$matcher = array('tag' => 'textarea', 
		                 'attributes' => array(
			                 'id' => 'foo',
			                 'name' => 'foo'),
			               'content' => null);
		$this->assertTag($matcher, $actual, 'Default attributes');
		
		$actual = Tingle_FormTagHelper::text_area_tag('foo', 'bar');
		$matcher = array('tag' => 'textarea', 
		                 'attributes' => array(
			                 'id' => 'foo',
			                 'name' => 'foo'),
			               'content' => 'bar');
		$this->assertTag($matcher, $actual, 'with contents');
		
		$actual = Tingle_FormTagHelper::text_area_tag('foo', 'bar', array('class' => 'blah'));
		$this->assertRegexp('/class="blah"/', $actual, "Additional attributes");
	}
	
	public function test_text_field_tag()
	{
		$actual = Tingle_FormTagHelper::text_field_tag('foo', 'bar');
		$matcher = array('tag' => 'input', 
		                 'attributes' => array(
			                 'type' => 'text',
											 'value' => 'bar',
											 'id' => 'foo',
			                 'name' => 'foo'));
		$this->assertTag($matcher, $actual, 'Default attributes');
		
		$actual = Tingle_FormTagHelper::text_field_tag('foo', 'bar', array('class' => 'blah'));
		$this->assertRegexp('/class="blah"/', $actual, "Additional attributes");
	}
	
	public function test_sanitize_id()
	{
		$this->assertEquals('foo', Tingle_FormTagHelper::sanitize_id('foo'), 'Standard name');
		$this->assertEquals('foo_name', Tingle_FormTagHelper::sanitize_id('foo[name]'), 'Name with subscript');
		$this->assertEquals('foo', Tingle_FormTagHelper::sanitize_id('foo[]'), 'Name with empty subscript');
		$this->assertEquals('foo_bar_name', Tingle_FormTagHelper::sanitize_id('foo[bar][name]'), 'Name with multiple subscripts');
	}
	
	public function test_options_for_select()
	{
		$actual = Tingle_FormTagHelper::options_for_select(array(1 => 'one', 2 => 'two'));
		$this->assertEquals("<option value=\"1\">one</option>\n<option value=\"2\">two</option>\n", $actual, 'should output correct option tags');

		$actual = Tingle_FormTagHelper::options_for_select(array(1 => 'one', 'others' => array(2 => 'two')));
		$this->assertEquals("<option value=\"1\">one</option>\n<optgroup label=\"others\"><option value=\"2\">two</option>\n</optgroup>\n", $actual, 'should output correct optgroup tags');

		$actual = Tingle_FormTagHelper::options_for_select(array(1 => 'one', 2 => 'two'), 1);
		$this->assertEquals("<option value=\"1\" selected=\"selected\">one</option>\n<option value=\"2\">two</option>\n", $actual, 'should mark single selected value');

		$actual = Tingle_FormTagHelper::options_for_select(array(1 => 'one', 2 => 'two'), array(1, 2));
		$this->assertEquals("<option value=\"1\" selected=\"selected\">one</option>\n<option value=\"2\" selected=\"selected\">two</option>\n", $actual, 'should mark multiple selected values');
	}
	
	public function test_options_for_select_from_collection_with_arrays()
	{
		$array_data = array(
			array('id' => 1, 'name' => 'one'),
			array('id' => 2, 'name' => 'two'),
			);
			
		$actual = Tingle_FormTagHelper::options_for_select_from_collection($array_data, 'id', 'name');
		$this->assertEquals("<option value=\"1\">one</option>\n<option value=\"2\">two</option>\n", $actual, 'should output correct option tags');
		
		$actual = Tingle_FormTagHelper::options_for_select_from_collection($array_data, 'id', 'name', 1);
		$this->assertEquals("<option value=\"1\" selected=\"selected\">one</option>\n<option value=\"2\">two</option>\n", $actual, 'should mark selected values');
	}

	
	public function test_options_for_select_from_collection_with_objects()
	{
		$obj1 = new StdClass;
		$obj1->id = 1;
		$obj1->name = 'one';
		$obj2 = new StdClass;
		$obj2->id = 2;
		$obj2->name = 'two';
		
		$obj_data = array($obj1, $obj2);
			
		$actual = Tingle_FormTagHelper::options_for_select_from_collection($obj_data, 'id', 'name');
		$this->assertEquals("<option value=\"1\">one</option>\n<option value=\"2\">two</option>\n", $actual, 'should output correct option tags');
		
		$actual = Tingle_FormTagHelper::options_for_select_from_collection($obj_data, 'id', 'name', 1);
		$this->assertEquals("<option value=\"1\" selected=\"selected\">one</option>\n<option value=\"2\">two</option>\n", $actual, 'should mark selected values');
	}
}
?>
