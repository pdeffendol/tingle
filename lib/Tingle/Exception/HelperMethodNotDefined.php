<?php
namespace Tingle\Exception;

class HelperMethodNotDefined extends \Exception
{
    private $helper_name;
    
    public function __construct($helper_name)
    {
        $this->helper_name = $helper_name;
    }
    
    public function __toString()
    {
        return __CLASS__.": '{$this->helper_name}'";
    }   
}
