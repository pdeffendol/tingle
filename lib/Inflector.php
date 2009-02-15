<?php
/**
 * Static class for converting between word forms, esp. between
 * singular and plural.
 * 
 * Examples:
 * 
 * echo Inflector::singularize('methods');
 * "method"
 * 
 * echo Inflector::pluralize('sheep');
 * "sheep"
 */
class Tingle_Inflector
{
	private static $plural_rules = array();
	private static $singular_rules = array();
	private static $uncountables = array();
	
	/**
	 * Add a new rule for singularizing a word.
	 * 
	 * New rules are added to the top of the list - thus are will be matched
	 * before existing rules.
	 * 
	 * @param string $rule Regular expression to match
	 * @param string $replacement Replacement text to make a word singular
	 */
	public static function singular($rule, $replacement)
	{
		self::$singular_rules = array_merge(array($rule => $replacement), self::$singular_rules);
	}
	
	
	/**
	 * Add a new rule for pluralizing a word.
	 * 
	 * New rules are added to the top of the list - thus are will be matched
	 * before existing rules.
	 * 
	 * @param string $rule Regular expression to match
	 * @param string $replacement Replacement text to make a word plural
	 */
	public static function plural($rule, $replacement)
	{
		self::$plural_rules = array_merge(array($rule => $replacement), self::$plural_rules);
	}
	
	
	/**
	 * Add a new rule for irregular words.
	 * 
	 * New rules are added to the top of the list - thus are will be matched
	 * before existing rules.
	 * 
	 * @param string $singular Singular form of word
	 * @param string $plural   Plural form of word
	 */
	public static function irregular($singular, $plural)
	{
		self::plural('/('.substr($singular, 0, 1).')'.substr($singular, 1).'$/i', '\1'.substr($plural, 1));
		self::singular('/('.substr($plural, 0, 1).')'.substr($plural, 1).'$/i', '\1'.substr($singular, 1));
	}

	
	/**
	 * Add a new rule for "uncountable" words - those that are the same whether
	 * singular or plural.
	 * 
	 * @param string $word Uncountable word
	 */
	public static function uncountable($word)
	{
		self::$uncountables = array_merge(self::$uncountables, (array)$word);
	}

	
	/**
	 * Return the plural form of a word.
	 * 
	 * @param string $word Singular word
	 * @return string Plural word
	 */
	public static function pluralize($word) 
	{
		if (in_array(strtolower($word), self::$uncountables))
		{
			return $word;
		}
		
		$original = $word;
		foreach(self::$plural_rules as $rule => $replacement)
		{
			$word = preg_replace($rule, $replacement, $word);
			if ($original != $word) break;
		}
		return $word;
	}

	/**
	 * Return the singular form of a word.
	 * 
	 * @param string $word Plural word
	 * @return string Singular word
	 */
	public static function singularize($word)
	{
		if (in_array(strtolower($word), self::$uncountables))
		{
			return $word;
		}
		
		$original = $word;
		foreach(self::$singular_rules as $rule => $replacement)
		{
			$word = preg_replace($rule, $replacement, $word);
			if ($original != $word) break;
		}
		return $word;
	}


	/**
	 * Return the CamelCaseForm of a phrase.
	 */
	public static function camelize($lower_case_and_underscored_word) 
	{
		return str_replace(array(' ', "\t"), array('', '_'), ucwords(str_replace(array('_', '/'), array(' ', "\t"),$lower_case_and_underscored_word)));
	}


	/**
	 * Return the underscore_form of a phrase.
	 */
	public static function underscore($camel_cased_word) 
	{
		$camel_cased_word = preg_replace('/([A-Z]+)([A-Z])/','\1_\2',$camel_cased_word);
		return strtolower(preg_replace('/([a-z])([A-Z])/','\1_\2',$camel_cased_word));
	}


	/**
	 * Return the "Human readable form" of a phrase.
	 */
	public static function humanize($lower_case_and_underscored_word) 
	{
		return ucwords(str_replace("_"," ",$lower_case_and_underscored_word));
	}


	public static function tableize($class_name) 
	{
		return self::pluralize(self::underscore($class_name));
	}


	public static function classify($table_name)
	{
		return self::camelize(self::singularize($table_name));
	}


	public static function foreign_key($class_name) 
	{
		return self::underscore($class_name) . "_id";
	}
}

Tingle_Inflector::singular('/s$/i' , '');
Tingle_Inflector::singular('/(n)ews$/i' , '\1ews');
Tingle_Inflector::singular('/([ti])a$/i' , '\1um');
Tingle_Inflector::singular('/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' , '\1\2sis');
Tingle_Inflector::singular('/(^analy)ses$/i' , '\1sis');
Tingle_Inflector::singular('/([^f])ves$/i' , '\1fe');
Tingle_Inflector::singular('/(hive)s$/i' , '\1');
Tingle_Inflector::singular('/(tive)s$/i' , '\1');
Tingle_Inflector::singular('/([lr])ves$/i' , '\1f');
Tingle_Inflector::singular('/([^aeiouy]|qu)ies$/i' , '\1y');
Tingle_Inflector::singular('/(s)eries$/i' , '\1eries');
Tingle_Inflector::singular('/(m)ovies$/i' , '\1ovie');
Tingle_Inflector::singular('/(x|ch|ss|sh)es$/i' , '\1');
Tingle_Inflector::singular('/([m|l])ice$/i' , '\1ouse');
Tingle_Inflector::singular('/(bus)es$/i' , '\1');
Tingle_Inflector::singular('/(o)es$/i' , '\1');
Tingle_Inflector::singular('/(shoe)s$/i' , '\1');
Tingle_Inflector::singular('/(cris|ax|test)es$/i' , '\1is');
Tingle_Inflector::singular('/([octop|vir])i$/i' , '\1us');
Tingle_Inflector::singular('/(alias|status)es$/i' , '\1');
Tingle_Inflector::singular('/^(ox)en/i' , '\1');
Tingle_Inflector::singular('/(vert|ind)ices$/i' , '\1ex');
Tingle_Inflector::singular('/(matr)ices$/i' , '\1ix');
Tingle_Inflector::singular('/(quiz)zes$/i' , '\1');

Tingle_Inflector::plural('/$/' , 's');
Tingle_Inflector::plural('/s$/i' , 's');
Tingle_Inflector::plural('/(ax|test)is$/i' , '\1es');
Tingle_Inflector::plural('/(octop|vir)us$/i' , '\1i');
Tingle_Inflector::plural('/(alias|status)$/i' , '\1es');
Tingle_Inflector::plural('/(bu)s$/i' , '\1ses');
Tingle_Inflector::plural('/(buffal|tomat)o$/i' , '\1oes');
Tingle_Inflector::plural('/([ti])um$/i' , '\1a');
Tingle_Inflector::plural('/sis$/i' , 'ses');
Tingle_Inflector::plural('/(?:([^f])fe|([lr])f)$/i' , '\1\2ves');
Tingle_Inflector::plural('/(hive)$/i' , '\1s');
Tingle_Inflector::plural('/([^aeiouy]|qu)y$/i' , '\1ies');
Tingle_Inflector::plural('/([^aeiouy]|qu)ies$/i' , '\1y');
Tingle_Inflector::plural('/(x|ch|ss|sh)$/i' , '\1es');
Tingle_Inflector::plural('/(matr|vert|ind)ix|ex$/i' , '\1ices');
Tingle_Inflector::plural('/([m|l])ouse$/i' , '\1ice');
Tingle_Inflector::plural('/^(ox)$/i' , '\1en');
Tingle_Inflector::plural('/(quiz)$/i' , '\1zes');

Tingle_Inflector::irregular('person', 'people');
Tingle_Inflector::irregular('man', 'men');
Tingle_Inflector::irregular('child', 'children');
Tingle_Inflector::irregular('sex', 'sexes');
Tingle_Inflector::irregular('move', 'moves');

Tingle_Inflector::uncountable(array('equipment', 'information', 'rice', 
                             'money', 'species', 'series', 'fish', 
                             'sheep', 'deer', 'elk', 'cattle'));
?>
