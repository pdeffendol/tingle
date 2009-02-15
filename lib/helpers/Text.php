<?php
require_once dirname(__FILE__).'/../Helper.php';
require_once dirname(__FILE__).'/../Inflector.php';

class Tingle_Helper_Text extends Tingle_Helper
{
	public function pluralize($count, $singular, $number_format = null)
	{
		$word = ($count == 1) ? $singular : Tingle_Inflector::pluralize($singular);
		
		return ($number_format === null) ? "$count $word" : sprintf($number_format, $count).' '.$word;
	}
}
?>