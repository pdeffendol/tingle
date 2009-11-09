<?php
namespace Tingle;

class FormTagHelper
{
	/**
	 * Output tag to start a form.
	 *
	 * // Default
	 * start_form_tag('process.php'):
	 *   <form action="process.php" method="post">
	 *
	 * // Form containing file uploads
	 * start_form_tag("process.php", array('multipart' => true)):
	 *   <form action="process.php" method="post" enctype="multipart/form-data">
	 *
	 * // Additional attributes
	 * start_form_tag('process.php', array('method' => 'get', 'class' => 'search'))
	 *   <form action="process.php" method="get" class="search">
	 *
	 * @param array $html_attributes Set of HTML attributes and other options
	 * return string Form tag
	 */
	public static function start_form_tag($action_or_attributes, $html_attributes = array())
	{
		if (!is_array($action_or_attributes))
		{
			$action_or_attributes = array('action' => $action_or_attributes);
		}
		$html_attributes = array_merge($action_or_attributes, $html_attributes);
		
		if ($html_attributes['multipart'])
		{
			$html_attributes['enctype'] = 'multipart/form-data';
			unset($html_attributes['multipart']);
		}

		return TagHelper::tag('form', array_merge(array('method' => 'post'), $html_attributes), true);
	}


	/**
	 * Output closing form tag.
	 *
	 * return string Closing tag
	 */
	public static function end_form_tag()
	{
		return '</form>';
	}


	/**
	 * Output tag to start a form. (alternate method)
	 *
	 * @see start_form_tag
	 */
	public static function form_tag($html_attributes = array())
	{
		return self::start_form_tag($html_attributes);
	}


	/**
	 * Output an HTML checkbox
	 *
	 *
	 * @param string $name Name of field
	 * @param string $value Value to submit when box is checked
	 * @param boolean $checked Whether to check the box
	 * @param array $html_attributes Set of HTML attributes and other options
	 * return string Checkbox HTML
	 */
	public static function checkbox_tag($name, $value = "1", $checked = false, $html_attributes = array())
	{
		$checked = $checked ? "checked" : null;
		return TagHelper::tag('input', array_merge(array('type' => 'checkbox', 'name' => $name, 'value' => $value, 'checked' => $checked, 'id' => self::sanitize_id($name)), $html_attributes));
	}


	/**
	 * Output an HTML file upload field
	 *
	 * Be sure to use the "multipart" setting on your <form> tag when using this control.
	 *
	 * @param string $name Name of field
	 * @param array $html_attributes Set of HTML attributes and other options
	 * return string File upload field HTML
	 */
	public static function file_field_tag($name, $html_attributes = array())
	{
		return self::text_field_tag($name, null, array_merge(array('type' => 'file'), $html_attributes));
	}


	/**
	 * Output an HTML hidden field
	 *
	 * @param string $name Name of field
	 * @param string $value Value to store in field
	 * @param array $html_attributes Set of HTML attributes and other options
	 * return string Hidden field HTML
	 */
	public static function hidden_field_tag($name, $value = null, $html_attributes = array())
	{
		return self::text_field_tag($name, $value, array_merge(array('type' => 'hidden'), $html_attributes));
	}


	/**
	 * Output an HTML image submit button
	 *
	 * @param string $source URL of image
	 * @param array $html_attributes Set of HTML attributes and other options
	 * return string button HTML
	 */
	public static function image_submit_tag($source, $html_attributes = array())
	{
		return TagHelper::tag('input', array_merge(array('type' => 'image', 'src' => $source), $html_attributes));
	}
	
	
	/**
	 * Output a form field label
	 *
	 * @param string $name Name of associated field
	 * @param string $label Content of label
	 * @param array $html_attributes Set of additional HTML attributes
	 * return string Label tag
	 */
	public static function label_tag($name, $label, $html_attributes = array())
	{
		return TagHelper::content_tag('label', $label, array_merge(array('for' => self::sanitize_id($name)), $html_attributes));
	}


	/**
	 * Output an HTML password field
	 *
	 * @param string $name Name of field
	 * @param string $value Initial value to display in field
	 * @param array $html_attributes Set of HTML attributes and other options
	 * return string Password field HTML
	 */
	public static function password_field_tag($name, $value = null, $html_attributes = array())
	{
		return self::text_field_tag($name, $value, array_merge(array('type' => 'password'), $html_attributes));
	}


	/**
	 * Output an HTML radio button field
	 *
	 * @param string $name Name of field
	 * @param string $value Value to store in field when button is checked
	 * @param boolean $checked Whether to check radio button
	 * @param array $html_attributes Set of HTML attributes and other options
	 * return string Radio button HTML
	 */
	public static function radio_button_tag($name, $value, $checked = false, $html_attributes = array())
	{
		$checked = $checked ? "checked" : null;
		$id = self::sanitize_id($name.'_'.$value);
		return TagHelper::tag('input', array_merge(array('type' => 'radio', 'name' => $name, 'value' => $value, 'id' => $id, 'checked' => $checked), $html_attributes));
	}


	/**
	 * Output an HTML selection list
	 *
	 * @param string $name Name of list
	 * @param string $option_tags List of <option> tags to include in list
	 * @param array $html_attributes Set of HTML attributes and other options
	 * return string Selection list HTML
	 */
	public static function select_tag($name, $option_tags = null, $html_attributes = array())
	{
		$name_attr = ($html_attributes['multiple'] && substr($name, -2, 2) != '[]') ? $name.'[]' : $name;
		return TagHelper::content_tag('select', $option_tags, array_merge(array('name' => $name_attr, 'id' => self::sanitize_id($name)), $html_attributes));
	}


	/**
	 * Output an HTML submit button
	 *
	 * @param string $value Label of button
	 * @param array $html_attributes Set of HTML attributes and other options
	 * return string Submit button HTML
	 */
	public static function submit_tag($value = 'Save', $html_attributes = array())
	{
		return TagHelper::tag('input', array_merge(array('type' => 'submit', 'value' => $value), $html_attributes));
	}


	/**
	 * Output an HTML text area field
	 *
	 * @param string $name Name of field
	 * @param string $content Contents of field
	 * @param array $html_attributes Set of HTML attributes and other options
	 * return string Text area HTML
	 */
	public static function text_area_tag($name, $content = null, $html_attributes = array())
	{
		return TagHelper::content_tag('textarea', htmlspecialchars($content), array_merge(array('name' => $name, 'id' => self::sanitize_id($name)), $html_attributes));
	}


	/**
	 * Output an HTML text field
	 *
	 * @param string $name Name of field
	 * @param string $value Initial value to display in field
	 * @param array $html_attributes Set of HTML attributes and other options
	 * return string Text field HTML
	 */
	public static function text_field_tag($name, $value = null, $html_attributes = array())
	{
		return TagHelper::tag('input', array_merge(array('type' => 'text', 'name' => $name, 'value' => $value, 'id' => self::sanitize_id($name)), $html_attributes));
	}
	
	
	/**
	 * Generate a list of <option> tags from an array, using the array keys as
	 * the value attributes, and the corresponding array values as the labels.
	 *
	 * If arrays are nested, an <optgroup> tag will be generated using the array
	 * key as its label, and containing <option> tags for each element of the
	 * nested array.
	 *
	 * @param array $choices Set of $value=>$label choices
	 * @param mixed $selected Single value or array of values that indicate keys of
	 *                        options to mark as selected.
	 * @return string Set of <option> tags
	 */
	public static function options_for_select($choices, $selected = null)
	{
		$selected_reversed = array_flip(array_map('strval', array_values((array)$selected)));
		
		$option_tags = '';
		foreach ($choices as $key => $value)
		{
		  if (is_array($value))
		  {
		    $option_tags .= TagHelper::content_tag('optgroup', self::options_for_select($value, $selected), array('label' => $key))."\n";
		  }
		  else
		  {
		    $html_attributes = array('value' => $key);
		    if (isset($selected_reversed[strval($key)])) $html_attributes['selected'] = 'selected';
		
		    $option_tags .= TagHelper::content_tag('option', TagHelper::escape_once($value), $html_attributes)."\n";
		  }
		}
		
		return $option_tags;
	}
	
	
	/**
	 * Generate a list of <option> tags from a collection of arrays or objects,
	 * given the array keys or object attributes to use for the <option> label
	 * and value.
	 *
	 * @param array $collection Array of arrays or objects
	 * @param string $value_field Array key or object attribute from which to obtain option value
	 * @param string $label_field Array key or object attribute from which to obtain option label
	 * @param mixed $selected Single value or array of values that indicate keys of
	 *                        options to mark as selected.
	 * @return string Set of <option> tags
	 */
	public static function options_for_select_from_collection($collection, $value_field, $label_field, $selected = null)
	{
		$options = array();
		foreach ((array)$collection as $item)
		{
			if (is_object($item))
			{
				$options[$item->$value_field] = $item->$label_field;
			}
			else
			{
				$options[$item[$value_field]] = $item[$label_field];
			}
		}

		return self::options_for_select($options, $selected);
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
	public static function sanitize_id($string)
	{
		return preg_replace('/[^-a-zA-Z0-9:.]/', '_', str_replace(array('[]', ']'), '', $string));
	}
}
?>