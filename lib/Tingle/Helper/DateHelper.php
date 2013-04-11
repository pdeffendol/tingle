<?php
namespace Tingle\Helper;

class DateHelper
{
  /**
 	 * Format a date and return the result.
   *
   * The default format is RFC822 which looks like:
   * Mon, 15 Aug 05 15:52:01 +0000
   * See http://us.php.net/manual/en/function.date.php for
   * the list of formatting options.
   *
   * @param mixed $date Anything that can be turned into a DateTime object
   * @param string $format Date formatting string
   * @return string Formatted date string
   */ 
	public static function format_date($date, $format = \DateTime::RFC822)
  {
    if (!($date instanceof \DateTime))
    {
      $date = new \DateTime($date);
    }
    
    return $date->format($format);
  }

	/**
	 * Output an <abbr> tag as defined by the Datetime Design Pattern,
	 * which is described at http://microformats.org/wiki/datetime-design-pattern
	 *
	 * @param mixed $date Something that can be converted to a DateTime object
	 * @param string $viewable_format Format for visible date text
	 * @return string HTML tag
	 */
	public static function datetime_tag($date, $viewable_format = \DateTime::RFC822, $html_attributes = array())
	{
		return TagHelper::content_tag('abbr', 
			self::format_date($date, $viewable_format), 
			array_merge((array)$html_attributes, array('title' => self::format_date($date, \DateTime::ISO8601))));
	}
}
?>
