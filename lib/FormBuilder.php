<?php
require_once dirname(__FILE__).'/helpers/FormTagHelper.php';
require_once dirname(__FILE__).'/Exception.php';

class Tingle_FormBuilder
{
	private $model;
	
	public function __construct($model = array())
	{
		$this->model = $model;
	}
	
	/**
	 * Retrieve a value from the data model that matches the
	 * field name given.  Field names are in the format:
	 *
	 * name([key])*
	 */
	private function get_model_data($name)
	{
		if (!preg_match('/^[a-zA-Z0-9_]+(?:\[[a-zA-Z0-9_]+\])*(?:\[\])?$/', $name))
		{
			throw new Tingle_Exception("Invalid form field name syntax {$name}");
		}
		
		$keys = explode('[', str_replace(array('[]', ']'), '', $name));

		// Iterate down through the data model for each nested key
		$data = $this->model;
		foreach ($keys as $key)
		{
			if (is_array($data))
			{
				$data = $data[$key];
			}
			elseif (is_object($data))
			{
				$data = $data->$key;
			}
			else
			{
				$data = null;;
				break;
			}
		}
		
		return $data;
	}
	
	/**
	 * return string Checkbox HTML
	 */
	public function checkbox($name, $html_attributes = array(), $checked_value = '1', $unchecked_value = '0')
	{
		$value = $this->get_model_data($name);
		$checked = ($value == $checked_value);
		return Tingle_FormTagHelper::hidden_field_tag($name, $unchecked_value, array('id' => null)).Tingle_FormTagHelper::checkbox_tag($name, $checked_value, $checked, $html_attributes);
	}
	
	public function grouped_checkbox($name, $tag_value, $html_attributes = array())
	{
		$values = $this->get_model_data($name);
		
		$name = (substr($name, -2, 2) == '[]') ? $name : $name.'[]';
		$checked = is_array($values) ? in_array($tag_value, $values) : $tag_value == $values;
		$id = Tingle_FormTagHelper::sanitize_id($name).'_'.Tingle_FormTagHelper::sanitize_id($tag_value);
		$html_attributes = array_merge(array('id' => $id), $html_attributes);
		return Tingle_FormTagHelper::checkbox_tag($name, $tag_value, $checked, $html_attributes);
	}

	public function file_field($name, $html_attributes = array())
	{
		return Tingle_FormTagHelper::file_field_tag($name, $html_attributes);
	}
	
	public function hidden_field($name, $html_attributes = array())
	{
		$value = strval($this->get_model_data($name));
		return Tingle_FormTagHelper::hidden_field_tag($name, $value, $html_attributes);
	}

	public function label($name, $text, $html_attributes = array())
	{
		return Tingle_TagHelper::content_tag('label', $text, array_merge(array('for' => Tingle_FormTagHelper::sanitize_id($name)), $html_attributes));
	}

	public function password_field($name, $html_attributes = array())
	{
		$value = strval($this->get_model_data($name));
		return Tingle_FormTagHelper::password_field_tag($name, $value, $html_attributes);
	}

	public function radio_button($name, $tag_value, $html_attributes = array())
	{
		$value = strval($this->get_model_data($name));
		$checked = ($value == $tag_value);
		return Tingle_FormTagHelper::radio_button_tag($name, $tag_value, $checked, $html_attributes);
	}

	public function text_area($name, $html_attributes = array())
	{
		$value = strval($this->get_model_data($name));
		return Tingle_FormTagHelper::text_area_tag($name, $value, $html_attributes);
	}

	public function text_field($name, $html_attributes = array())
	{
		$value = strval($this->get_model_data($name));
		return Tingle_FormTagHelper::text_field_tag($name, $value, $html_attributes);
	}
}
?>
