<?php
require_once dirname(__FILE__).'/FormTagHelper.php';
require_once dirname(__FILE__).'/../FormBuilder.php';
require_once dirname(__FILE__).'/../Exception.php';

class Tingle_FormHelper
{
	private static $forms = array();
	
	/**
	 * Obtain a FormBuilder for a given data model.
	 *
	 * Examples:
	 *
	 * // Fields will be prefixed with "user" and use data from
	 * // the template's $user attribute.
	 * $form = $this->form_for('user', $this->user);
	 *
	 * // Specify a custom FormBuilder class
	 * $form = $this->form_for('user', $this->user, array('builder' => 'CustomBuilder));
	 *
	 * // Specify other attributes to use for the <form> tag.
	 * $form = $this->form_for('user', $this->user, array('action' => '/search', 'method' => 'get'));
	 *
	 * Most of the time you will want to call the FormBuilder's start() method
	 * immediately in order to output the opening <form> tag.  You can still obtain
	 * a reference to the FormBuilder by assigning it to a local template variable:
	 *
	 * <?php= $form = $this->form_for('user', $this->user, array('action' => '/blah'))->start() ?>
	 * // form content
	 * <?php= $this->form_for('user')->end() ?>
	 * 
	 * @param string $model_name Name of model, used as prefix on form field names
	 * @param string $model_data Array or object holding model data for populating fields (optional)
	 * @param array  $options    Array of options for creating form or HTML attributes of <form> tag
	 * @return object Instance of FormBuilder
	 */
	public static function form_for($model_name, $model_data = array(), $html_attributes = array())
	{
		if (!isset(self::$forms[$model_name]))
		{
			$builder = $html_attributes['builder'] ? strval($html_attributes['builder']) : 'Tingle_FormBuilder';
			unset($html_attributes['builder']);
			
			if (!class_exists($builder))
			{
				throw new Tingle_RenderingError('Form builder '.$builder.' not found.');
			}

			self::$forms[$model_name] = new $builder($model_name, $model_data, $html_attributes);
		}
		
		return self::$forms[$model_name];
	}
}
?>
