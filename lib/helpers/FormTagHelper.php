<?php
require_once dirname(__FILE__).'/TagHelper.php';

class Tingle_FormTagHelper
{
	public static function start_form_tag($attributes = array())
	{
		if ($attributes['multipart'])
		{
			$attributes['enctype'] = 'multipart/form-data';
			unset($attributes['multipart']);
		}
		
		return Tingle_TagHelper::tag('form', array_merge(array('method' => 'post'), $attributes), true);
	}
	
	public static function end_form_tag()
	{
		return '</form>';
	}
	
	public static function form_tag($attributes = array())
	{
		return self::start_form_tag($attributes);
	}
	
	public static function checkbox_tag($name, $value = "1", $checked = false, $attributes = array())
	{
		$checked = $checked ? "checked" : null;
		return Tingle_TagHelper::tag('input', array_merge(array('type' => 'checkbox', 'name' => $name, 'value' => $value, 'checked' => $checked, 'id' => self::sanitize_id($name)), $attributes));
	}
	
	public static function file_field_tag($name, $attributes = array())
	{
		return self::text_field_tag($name, null, array_merge(array('type' => 'file'), $attributes));
	}
	
	public static function hidden_field_tag($name, $value = null, $attributes = array())
	{
		return self::text_field_tag($name, $value, array_merge(array('type' => 'hidden'), $attributes));
	}
	
	public static function image_submit_tag($source, $attributes = array())
	{
		return Tingle_TagHelper::tag('input', array_merge(array('type' => 'image', 'src' => $source), $attributes));
	}
	
	public static function password_field_tag($name, $value = null, $attributes = array())
	{
		return self::text_field_tag($name, $value, array_merge(array('type' => 'password'), $attributes));
	}
	
	public static function radio_button_tag($name, $value, $checked = false, $attributes = array())
	{
		$checked = $checked ? "checked" : null;
		$id = self::sanitize_id($name.'_'.strtolower($value));
		return Tingle_TagHelper::tag('input', array_merge(array('type' => 'radio', 'name' => $name, 'value' => $value, 'id' => $id, 'checked' => $checked), $attributes));		
	}
	
	public static function select_tag($name, $option_tags = null, $attributes = array())
	{
		$name_attr = ($attributes['multiple'] && substr($name, -2, 2) != '[]') ? $name.'[]' : $name;
		return Tingle_TagHelper::content_tag('select', $option_tags, array_merge(array('name' => $name_attr, 'id' => self::sanitize_id($name)), $attributes));
	}
	
	public static function submit_tag($value = 'Save', $attributes = array())
	{
		return Tingle_TagHelper::tag('input', array_merge(array('type' => 'submit', 'value' => $value, 'id' => self::sanitize_id($name)), $attributes));
	}
	
	public static function text_area_tag($name, $content = null, $attributes = array())
	{
		return Tingle_TagHelper::content_tag('textarea', htmlspecialchars($content), array_merge(array('name' => $name, 'id' => self::sanitize_id($name)), $attributes));
	}
	
	public static function text_field_tag($name, $value = null, $attributes = array())
	{
		return Tingle_TagHelper::tag('input', array_merge(array('type' => 'text', 'name' => $name, 'value' => $value, 'id' => self::sanitize_id($name)), $attributes));
	}
	
	
	/**
	 * Convert a string into that suitable for use in an HTML ID attribute.
	 *
	 * See W3 spec at http://www.w3.org/TR/html4/types.html#type-name:
	 *   ID and NAME tokens must begin with a letter ([A-Za-z]) and may be followed by any 
	 *   number of letters, digits ([0-9]), hyphens ("-"), underscores ("_"), colons (":"), 
	 *   and periods (".").
	 *
	 * @param string $string Text to convert
	 * return string Sanitized text
	 */
	private static function sanitize_id($string)
	{
		return preg_replace('/[^-a-zA-Z0-9:.]/', '_', str_replace(array('[]', ']'), '', $string));
	}
}
?>