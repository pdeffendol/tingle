<?php
namespace Tingle\Helper;

class TagHelper
{
    public static $boolean_attributes = array(
        'disabled',
        'multiple',
        'readonly');

    /**
     * Construct and output an HTML tag.  By default it will
     * be XHTML compliant.
     *
     * @param  string $name    Name of tag
     * @param  array  $options HTML attributes to include within the tag
     * @param  bool   $open    When set to true, will prevent XHTML closing slash
     * @return string Complete HTML tag
     */
    public static function tag($name, $html_attributes = array(), $open = false)
    {
      return ($name ? '<'.$name.self::tag_attributes($html_attributes).(($open) ? '>' : ' />') : '');
    }

    /**
     * Construct and output an HTML container tag.
     *
     * @param  string $name    Name of tag
     * @param  string $content Text to put inside container
     * @param  array  $options HTML attributes to include within the opening tag
     * @return string Complete HTML container
     */
    public static function content_tag($name, $content = '', $html_attributes = array())
    {
        return ($name ? '<'.$name.self::tag_attributes($html_attributes).'>'.$content.'</'.$name.'>' : '');
    }

    /**
     * Escapes special characters in an HTML string.
     *
     * @param  string $html HTML string to escape
     * @return string Escaped string
     */
    public static function escape_once($string)
    {
      return self::fix_double_escape(htmlspecialchars($string, ENT_COMPAT));
    }

    /**
     * Fixes HTML strings without double-escaped entities
     *
     * @param  string $escaped HTML string to fix
     * @return string fixed escaped string
     */
    public static function fix_double_escape($escaped)
    {
      return preg_replace('/&amp;([a-z]+|(#\d+)|(#x[\da-f]+));/i', '&$1;', $escaped);
    }

    /**
     * Construct snippet of HTML containing tag attributes, that
     * are properly escaped.
     *
     * @param  array  $options Array of name/value pairs of tag attributes
     * @return string HTML options to put inside a tag
     */
    private static function tag_attributes($html_attributes = array())
    {
      $html = '';
      foreach ($html_attributes as $key => $value) {
            $value = (in_array($key, self::$boolean_attributes) && $value) ? $key : ($value === false ? 'false' : $value);
        if ($value !== null) {
                $html .= ' '.$key.'="'.self::escape_once($value).'"';
            }
      }

      return $html;
    }
}
