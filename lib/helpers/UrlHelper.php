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
		return Tingle_TagHelper::content_tag('a', $label, array_merge($html_options, array('href'=>$url)));
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
}
?>
