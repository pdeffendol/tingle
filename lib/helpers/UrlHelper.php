<?php
require_once dirname(__FILE__).'/TagHelper.php';

class Tingle_UrlHelper
{
	/**
	 * Create an HTML link tag.
	 * 
	 * @param string $label String to make hyperlinked
	 * @param string $url		Destination URL of link
	 * @param array	 $html_options Array of name/value pairs for additional tag attributes
	 * @return string HTML link tag
	 */
	public static function link_to($label, $url, $html_options = array())
	{
		$html_options['href'] = $url;
		$html_options = self::options_for_javascript($html_options);
		return Tingle_TagHelper::content_tag('a', $label, $html_options);
	}
	
	/**
	 * Create an HTML link tag only if the condition is met,
	 * otherwise output only the label.
	 * 
	 * @param bool	 $condition Make a link if true
	 * @param string $label String to make hyperlinked
	 * @param string $url		Destination URL of link
	 * @param array	 $html_options Array of name/value pairs for additional tag attributes
	 * @return string HTML link tag
	 */
	public static function link_to_if($condition, $label, $url, $html_options = array())
	{
		return (bool)$condition ? self::link_to($label, $url, $html_options) : $label;
	}
	
	
	/**
	 * Create an HTML link tag only if its label is not empty, otherwise output
	 * only the label.	(Which will in effect output nothing.)
	 * @param string $label String to make hyperlinked
	 * @param string $url		Destination URL of link
	 * @param array	 $html_options Array of name/value pairs for additional tag attributes
	 * @return string HTML link tag or nothing
	 */
	public static function link_to_if_content($label, $url, $html_options = array())
	{
		return self::link_to_if(trim(strval($label)) != '', $label, $url, $html_options);
	}


	/**
	 * Create an HTML link tag only if the condition is not met,
	 * otherwise output only the label.
	 * 
	 * @param bool	 $condition Make a link if true
	 * @param string $label String to make hyperlinked
	 * @param string $url		Destination URL of link
	 * @param array	 $html_options Array of name/value pairs for additional tag attributes
	 * @return string HTML link tag
	 */
	public static function link_to_unless($condition, $label, $url, $html_options = array())
	{
		return self::link_to_if(!$condition, $label, $url, $html_options);
	}
	
	
	/**
	 * Create an HTML link to an e-mail address.
	 *
	 * Valid options (for the last parameter) are:
	 *
	 * encode - Set to "hex" to hex-encode the e-mail address in the link
	 * cc - Add recipients to the CC (carbon copy) of the email
	 * bcc - Add recipients to the BCC (blind carbon copy) of the email
	 * subject - Preset the subject line of the resulting email
	 * body - Preset the body of the resulting email
	 * 
	 * Other options will be passed as HTML attributes on the link tag.
	 *
	 * @param string $address E-mail address of recipient
	 * @param string $label String to show in link, defaults to e-mail address
	 * @param array	 $html_options Options for outputting link
	 * @return string HTML mailto: link tag
	 */
	public static function mail_to($address, $label = null, $html_options = array())
	{
		$encode = $html_options['encode'];
		$url_params = array();
		if ($html_options['cc']) $url_params['cc'] = $cc;
		if ($html_options['bcc']) $url_params['bcc'] = $bcc;
		if ($html_options['subject']) $url_params['subject'] = $subject;
		if ($html_options['body']) $url_params['body'] = $body;
		unset($html_options['encode'], 
			$html_options['cc'], 
			$html_options['bcc'], 
			$html_options['subject'], 
			$html_options['body']);
		
		$address = strval($address);
		$url_params = (count($url_params) == 0 ? '' : '?'.http_build_query($url_params));
		
		if ($encode == 'hex')
		{
			$address_encoded = '';
			for ($i=0; $i < strlen($address); $i++) 
			{
				if (preg_match('/\w/', $address[$i])) 
				{
						$address_encoded .= '%'.bin2hex($address[$i]);
				} 
				else 
				{
						$address_encoded .= $address[$i];
				}
			}
			
			$label = $label ? $label : $address;
			for ($i=0; $i < strlen($label); $i++) 
			{
					$label_encoded .= '&#x'.bin2hex($label[$i]).';';
			}

			$mailto = "&#109;&#97;&#105;&#108;&#116;&#111;&#58;";
			return Tingle_TagHelper::content_tag('a', $label_encoded, array_merge($html_options, array('href' => $mailto.$address_encoded.$url_params)));
		}
		else
		{
			return Tingle_TagHelper::content_tag('a', $label ? $label : $address, array_merge($html_options, array('href' => 'mailto:'.$address.$url_params)));
		}
	}
	
	
	/**
	 * Convert special link options into appropriate Javascript event
	 * handler code.
	 *
	 * The following options are converted and removed from the
	 * array:
	 *
	 *	 confirm - Generates a Javascript confirm dialog containing the provided text
	 *	 method	 - Causes the link to POST a hidden form instead of making a normal
	 *						 GET request
	 *
	 * @param array $options Set of link options
	 * @return array Modified set of link options
	 */
	private static function options_for_javascript($options)
	{
		$confirm = $options['confirm'];
		$method = $options['method'];
		$popup = $options['popup'];
		$href = $options['href'];
		unset($options['confirm'], $options['method'], $options['popup']);

		if ($popup && $method)
		{
			throw new Tingle_RenderingError('Cannot use both popup and method options in a link');
		}
		elseif ($confirm && $method)
		{
			$options['onclick'] = "if (".self::javascript_function_for_confirm($confirm).") {".self::javascript_function_for_method($method, $href)."} return false;";
		}
		elseif ($confirm && $popup)
		{
			$options['onclick'] = "if (".self::javascript_function_for_confirm($confirm).") {".self::javascript_function_for_popup($popup)."} return false;";
		}
		elseif ($confirm)
		{
			$options['onclick'] = "return ".self::javascript_function_for_confirm($confirm).";";
		}
		elseif ($method)
		{
			$options['onclick'] = self::javascript_function_for_method($method, $href).' return false;';
		}
		elseif ($popup)
		{
			$options['onclick'] = self::javascript_function_for_popup($popup).' return false;';
		}
		
		return $options;
	}
	

	/**
	 * Generate the Javascript code to handle the "confirm"
	 * link option.	 
	 *
	 * @param string $confirm Text to show in dialog box
	 * @return string Javascript code 
	 */
	private static function javascript_function_for_confirm($confirm)
	{
		return "confirm('".self::escape_for_javascript($confirm)."')";
	}
	
	
	/**
	 * Generate the Javascript code to handle the "method"
	 * link option.	 Creates a form and submits it via POST.
	 *
	 * @param string $method Value of method option (value doesn't matter)
	 * @param string $href	 Value of href attribute of link, to which
	 *											 form will be submitted
	 * @return string Javascript code
	 */
	private static function javascript_function_for_method($method, $href)
	{
		$form_action = $href ? "'{$href}'" : 'this.href';

		$js_submit = "var form = document.createElement('form'); form.style.display = 'none'; " .
			"this.parentNode.appendChild(form); form.method = 'post'; form.action = {$form_action};";
		$js_submit .= "form.submit();";

		return $js_submit;
	}
	

	/**
	 * Generate the Javascript code to open link in a new window.
	 *
	 * @param mixed $popup Array of options to window.open, or just a string value that doesn't matter
	 * @return string Javascript code
	 */
	private static function javascript_function_for_popup($popup)
	{
		return is_array($popup) ? "window.open(this.href, '{$popup[0]}', '{$popup[1]}');" : "window.open(this.href);";
	}
	
	
	/**
	 * Escape newlines, carriage returns, and quotes for use in a
	 * JavaScript string.
	 *
	 * @param string $string 
	 * @return string Escaped string
	 */
	private static function escape_for_javascript($string)
	{
		$javascript = preg_replace('/\r\n|\n|\r/', "\\n", $string);
		$javascript = preg_replace('/(["\'])/', '\\\\\1', $javascript);
	
		return $javascript;
	}
}
?>
