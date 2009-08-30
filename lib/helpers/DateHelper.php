<?php
class Tingle_DateHelper
{
  public static function format_date($date, $format = DATE_RFC822)
  {
    if (!($date instanceof DateTime))
    {
      $date = new DateTime($date);
    }
    
    return $date->format($format);
  }
}
?>
