<?php
namespace Tingle\Exception;

class TemplateNotFound extends \Exception
{
    private $template;
    private $template_path;

    public function __construct($template, $template_path)
    {
        $this->template = $template;
        $this->template_path = $template_path;
    }

    public function __toString()
    {
        return __CLASS__.": Template '{$this->template}' not found (template path: '".implode(':', $this->template_path)."')";
    }
}
