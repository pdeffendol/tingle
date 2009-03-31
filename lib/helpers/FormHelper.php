<?php
require_once dirname(__FILE__).'/FormTagHelper.php';
require_once dirname(__FILE__).'/../FormBuilder.php';
require_once dirname(__FILE__).'/../Exception.php';

class Tingle_FormHelper
{
	private static $form_builder;
	
	public static function start_form_for($model = array(), $attributes = array())
	{
		if (isset(self::$form_builder))
		{
			throw new Tingle_RenderingError('Nested form_for not allowed.  (Already inside a form_for block.)');
		}
		
		$builder = $attributes['builder'] ? strval($attributes['builder']) : new Tingle_FormBuilder($model);
		unset($attributes['builder']);

		if (!($builder instanceof Tingle_FormBuilder))
		{
			if (class_exists($builder))
			{
				$builder = new $builder($model);
			}
			else
			{
				throw new Tingle_RenderingError('Form builder '.$builder.' not found.');
			}
		}
		
		self::$form_builder = $builder;
		
		return Tingle_FormTagHelper::start_form_tag($attributes);
	}
	
	public static function end_form_for()
	{
		self::$form_builder = null;
		return Tingle_FormTagHelper::end_form_tag();
	}
	
	public static function form_for($model = array(), $attributes = array())
	{
		return self::start_form_for($model, $attributes);
	}
	
	/**
	 * return string Checkbox HTML
	 */
	public static function checkbox($name, $attributes = array(), $checked_value = '1', $unchecked_value = '0')
	{
		return self::delegate_to_builder('checkbox', $name, $attributes, $checked_value, $unchecked_value);
	}

	public static function file_field($name, $attributes = array())
	{
		return self::delegate_to_builder('file_field', $name, $attributes);
	}
	
	public static function grouped_checkbox($name, $value, $attributes = array())
	{
		return self::delegate_to_builder('grouped_checkbox', $name, $value, $attributes);
	}
	
	public static function hidden_field($name, $attributes = array())
	{
		return self::delegate_to_builder('hidden_field', $name, $attributes);
	}

	public static function label($name, $text = null, $attributes = array())
	{
		return self::delegate_to_builder('label', $name, $text, $attributes);
	}

	public static function password_field($name, $attributes = array())
	{
		return self::delegate_to_builder('password_field', $name, $attributes);
	}

	public static function radio_button($name, $value, $attributes = array())
	{
		return self::delegate_to_builder('radio_button', $name, $value, $attributes);
	}

	public static function text_area($name, $attributes = array())
	{
		return self::delegate_to_builder('text_area', $name, $attributes);
	}

	public static function text_field($name, $attributes = array())
	{
		return self::delegate_to_builder('text_field', $name, $attributes);
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
