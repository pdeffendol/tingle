<?php
require_once dirname(__FILE__).'/FormTagHelper.php';
require_once dirname(__FILE__).'/../FormBuilder.php';
require_once dirname(__FILE__).'/../Exception.php';

class Tingle_FormHelper
{
	private static $form_builder;
	
	public static function start_form_for($model_name, $model = array(), $action_or_attributes, $html_attributes = array())
	{
		if (isset(self::$form_builder))
		{
			throw new Tingle_RenderingError('Nested form_for not allowed.  (Already inside a form_for block.)');
		}
		
		if (!is_array($action_or_attributes))
		{
			$action_or_attributes = array('action' => $action_or_attributes);
		}
		$html_attributes = array_merge($action_or_attributes, $html_attributes);

		$builder = $html_attributes['builder'] ? strval($html_attributes['builder']) : new Tingle_FormBuilder($model_name, $model);
		unset($html_attributes['builder']);

		if (!($builder instanceof Tingle_FormBuilder))
		{
			if (class_exists($builder))
			{
				$builder = new $builder($model_name, $model);
			}
			else
			{
				throw new Tingle_RenderingError('Form builder '.$builder.' not found.');
			}
		}
		
		self::$form_builder = $builder;

		return Tingle_FormTagHelper::start_form_tag($action_or_attributes, $html_attributes);
	}
	
	public static function end_form_for()
	{
		self::$form_builder = null;
		return Tingle_FormTagHelper::end_form_tag();
	}
	
	public static function form_for($model = array(), $html_attributes = array())
	{
		return self::start_form_for($model, $html_attributes);
	}
	
	/**
	 * return string Checkbox HTML
	 */
	public static function checkbox($name, $html_attributes = array(), $checked_value = '1', $unchecked_value = '0')
	{
		return self::delegate_to_builder('checkbox', $name, $html_attributes, $checked_value, $unchecked_value);
	}

	public static function file_field($name, $html_attributes = array())
	{
		return self::delegate_to_builder('file_field', $name, $html_attributes);
	}
	
	public static function grouped_checkbox($name, $value, $html_attributes = array())
	{
		return self::delegate_to_builder('grouped_checkbox', $name, $value, $html_attributes);
	}
	
	public static function hidden_field($name, $html_attributes = array())
	{
		return self::delegate_to_builder('hidden_field', $name, $html_attributes);
	}

	public static function label($name, $text = null, $html_attributes = array())
	{
		return self::delegate_to_builder('label', $name, $text, $html_attributes);
	}

	public static function password_field($name, $html_attributes = array())
	{
		return self::delegate_to_builder('password_field', $name, $html_attributes);
	}

	public static function radio_button($name, $value, $html_attributes = array())
	{
		return self::delegate_to_builder('radio_button', $name, $value, $html_attributes);
	}
	
	public static function select($name, $choices, $options = array(), $html_attributes = array())
	{
		return self::delegate_to_builder('select', $name, $choices, $options, $html_attributes);
	}

	public static function text_area($name, $html_attributes = array())
	{
		return self::delegate_to_builder('text_area', $name, $html_attributes);
	}

	public static function text_field($name, $html_attributes = array())
	{
		return self::delegate_to_builder('text_field', $name, $html_attributes);
	}
	
	private static function delegate_to_builder()
	{
		$args = func_get_args();
		$method = array_shift($args);
		
		if (!self::$form_builder instanceof Tingle_FormBuilder)
		{
			throw new Tingle_RenderingError("Must be inside a form_for block to call '{$method}'");
		}
		
		return call_user_func_array(array(self::$form_builder, $method), $args);
	}
}
?>
