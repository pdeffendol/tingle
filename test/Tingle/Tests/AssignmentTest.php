<?php
namespace Tingle\Tests;

use Tingle\Template;

class AssignmentTest extends BaseTest
{
	protected function setUp()
	{
		$this->tpl = new Template;	
	}
	
	public function test_should_start_with_no_assignments()
  {
		$this->assertTrue(is_array($this->tpl->get_assignments()));
		$this->assertEquals(0, count($this->tpl->get_assignments()));
	}
	
	public function test_should_assign_single_value()
	{
		$this->tpl->assign('test_data', 'Hello');
		
		$this->assertEquals('Hello', $this->tpl->test_data);
		$this->assertEquals(1, count($this->tpl->get_assignments()));
	}
	
	public function test_should_assign_hash()
	{
		$data = array('one' => 'Hello', 'two' => 'World');
		$this->tpl->assign($data);
		
		$this->assertEquals(2, count($this->tpl->get_assignments()));
		$this->assertEquals('Hello', $this->tpl->one);
		$this->assertEquals('World', $this->tpl->two);
	}
	
	public function test_should_assign_object_properties()
	{
		$obj = new \StdClass;
		$obj->one = 'Hello';
		$obj->two = 'World';
		$this->tpl->assign($obj);
		
		$this->assertEquals(2, count($this->tpl->get_assignments()));
		$this->assertEquals('Hello', $this->tpl->one);
		$this->assertEquals('World', $this->tpl->two);
	}

	public function test_should_allow_direct_assignment()
	{
		$this->tpl->test_data = 'Hello';
	
		$this->assertEquals('Hello', $this->tpl->test_data);
		$this->assertEquals(1, count($this->tpl->get_assignments()));
	}
}
?>
