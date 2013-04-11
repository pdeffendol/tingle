<?php
namespace Tingle\Exception;

class InvalidHelperClass extends \Exception
{
    private $attempted_class;
    
    public function __construct($attempted_class)
    {
        $this->attempted_class = $attempted_class;
    }
    
    public function __toString()
    {
        return __CLASS__.": '{$this->attempted_class}' is not a helper class";
    }
}
