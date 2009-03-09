<?php
require_once dirname(__FILE__).'/TagHelper.php';

class Tingle_UrlHelper
{
	/**
	 * Create an HTML link tag.
	 * 
	 * @param string $label String to make hyperlinked
	 * @param string $url   Destination URL of link
	 * @param array  $html_options Array of name/value pairs for additional tag attributes
	 * @return string HTML link tag
	 */
	public static function link_to($label, $url, $html_options = array())
	{
		return Tingle_TagHelper::content_tag('a', $label, array_merge($html_options, array('href'=>$url)));
	}
	
	/**
	 * Create an HTML link tag only if the condition is met,
	 * otherwise output only the label.
	 * 
	 * @param bool   $condition Make a link if true
	 * @param string $label String to make hyperlinked
	 * @param string $url   Destination URL of link
	 * @param array  $html_options Array of name/value pairs for additional tag attributes
	 * @return string HTML link tag
	 */
	public static function link_to_if($condition, $label, $url, $html_options = array())
	{
		return (bool)$condition ? self::link_to($label, $url, $html_options) : $label;
	}


	/**
	 * Create an HTML link tag only if the condition is not met,
	 * otherwise output only the label.
	 * 
	 * @param bool   $condition Make a link if true
	 * @param string $label String to make hyperlinked
	 * @param string $url   Destination URL of link
	 * @param array  $html_options Array of name/value pairs for additional tag attributes
	 * @return string HTML link tag
	 */
	public static function link_to_unless($condition, $label, $url, $html_options = array())
	{
		return self::link_to_if(!$condition, $label, $url, $html_options);
	}
}
?>
