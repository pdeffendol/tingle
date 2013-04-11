<?php
namespace Tingle\Tests;

use Tingle\Template;

class LayoutTest extends BaseTest
{
    protected function setUp()
    {
        $this->tpl = new Template;
        $this->tpl->set_template_path($this->getTemplatePath());
        $this->tpl->set_layout('layouts/basic.tpl');
        $this->tpl->data = 'Hello';
    }

    public function test_should_fail_on_missing_templates()
    {
        $this->setExpectedException('Tingle\Exception\TemplateNotFound');
        $this->tpl->set_layout('bad_template.tpl');
    $result = $this->tpl->render('layout_variables.tpl');
    }

    public function test_should_render_inside_layout()
    {
    $result = $this->tpl->render('layout_variables.tpl');
    $this->assertContains('header', $result);
    }

    public function test_should_access_variables_from_template()
    {
    $result = $this->tpl->render('layout_variables.tpl');
    $this->assertContains('Layout Test', $result);
    }
}
?>
