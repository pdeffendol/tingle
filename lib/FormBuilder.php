<?php
require_once dirname(__FILE__).'/helpers/FormTagHelper.php';
require_once dirname(__FILE__).'/Exception.php';

class Tingle_FormBuilder
{
	private $model_name;
	private $model_data;
	
	public function __construct($model_name, $model_data = array())
	{
		$this->model_name = $model_name;
		$this->model_data = $model_data;
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
		$data = $this->model_data;
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
		return Tingle_FormTagHelper::hidden_field_tag($this->get_field_name($name), $unchecked_value, array('id' => null)).Tingle_FormTagHelper::checkbox_tag($this->get_field_name($name), $checked_value, $checked, $html_attributes);
	}
	
	public function grouped_checkbox($name, $tag_value, $html_attributes = array())
	{
		$values = $this->get_model_data($name);
		
		$name = (substr($name, -2, 2) == '[]') ? $name : $name.'[]';
		$checked = is_array($values) ? in_array($tag_value, $values) : $tag_value == $values;
		$id = Tingle_FormTagHelper::sanitize_id($name).'_'.Tingle_FormTagHelper::sanitize_id($tag_value);
		$html_attributes = array_merge(array('id' => $id), $html_attributes);
		return Tingle_FormTagHelper::checkbox_tag($this->get_field_name($name), $tag_value, $checked, $html_attributes);
	}

	public function file_field($name, $html_attributes = array())
	{
		return Tingle_FormTagHelper::file_field_tag($this->get_field_name($name), $html_attributes);
	}
	
	public function hidden_field($name, $html_attributes = array())
	{
		$value = strval($this->get_model_data($name));
		return Tingle_FormTagHelper::hidden_field_tag($this->get_field_name($name), $value, $html_attributes);
	}

	public function label($name, $text, $html_attributes = array())
	{
		return Tingle_FormTagHelper::label_tag($this->get_field_name($name), $text, $html_attributes);
	}

	public function password_field($name, $html_attributes = array())
	{
		$value = strval($this->get_model_data($name));
		return Tingle_FormTagHelper::password_field_tag($this->get_field_name($name), $value, $html_attributes);
	}

	public function radio_button($name, $tag_value, $html_attributes = array())
	{
		$value = strval($this->get_model_data($name));
		$checked = ($value == $tag_value);
		return Tingle_FormTagHelper::radio_button_tag($this->get_field_name($name), $tag_value, $checked, $html_attributes);
	}
	
	public function select($name, $choices, $options = array(), $html_attributes = array())
	{
		$value = strval($this->get_model_data($name));

		$option_tags = $this->add_default_select_options(Tingle_FormTagHelper::options_for_select($choices, $value), $options, $value);

		return Tingle_FormTagHelper::select_tag($this->get_field_name($name), $option_tags, $html_attributes);
	}

	public function text_area($name, $html_attributes = array())
	{
		$value = strval($this->get_model_data($name));
		return Tingle_FormTagHelper::text_area_tag($this->get_field_name($name), $value, $html_attributes);
	}

	public function text_field($name, $html_attributes = array())
	{
		$value = strval($this->get_model_data($name));
		return Tingle_FormTagHelper::text_field_tag($this->get_field_name($name), $value, $html_attributes);
	}
	
	
	protected function get_field_name($name)
	{
		if (($pos = strpos($name, '[')) !== false)
		{
			$subkeys = substr($name, $pos);
			$name = substr($name, 0, $pos);
		}
		
		return $this->model_name.'['.$name.']'.$subkeys;
	}
	
	
	/**
	 * Add additional option tags for the following select options:
	 *  'include_blank'
	 *  'prompt'
	 */
	protected function add_default_select_options($option_tags, $options, $value = null)
	{
		if ($options['include_blank'])
		{
			$option_tags = '<option value="">'.(is_string($options['include_blank']) ? $options['include_blank'] : '').'</option>' . $option_tags;
		}

		if ($options['prompt'] && !$value)
		{
			$option_tags = '<option value="">'.(is_string($options['prompt']) ? $options['prompt'] : 'Select one...').'</option>' . $option_tags;
		}
		
		return $option_tags;
	}
}
?>
