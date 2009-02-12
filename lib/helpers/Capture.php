<?php
require_once dirname(__FILE__).'/../Helper.php';
require_once dirname(__FILE__).'/Capture_Content.php';

class Tingle_Helper_Capture extends Tingle_Helper
{
	private $contents;
	
	public function __construct($template)
	{
		parent::__construct($template);
		$this->contents = array();
	}
	
	
	/**
	 * Access and manipulate a piece of captured content.
	 *
	 * This is useful for creating placeholders in your templates,
	 * which can then be filled with content extracted from another part
	 * of the template.
	 *
	 * Basic Usage
	 *
	 * <code>
	 * <?php $this->capture_for('sidebar')->start(); ?>
	 * Sidebar content goes here
	 * <?php $this->capture_for('sidebar')->end(); ?>
	 *
	 * <div id="content">Main content</div>
	 * <div id="sidebar"><?php echo $this->capture_for('sidebar'); ?></div>
	 * </code>
	 *
	 * @param string $name Name of content
	 * @return object New or existing Tingle_Helper_Capture_Content object
	 */
	public function content_for($name)
	{
		if (!isset($this->contents[$name]))
		{
			$this->contents[$name] = new Tingle_Helper_Capture_Content($name);
		}
		
		return $this->contents[$name];
	}
}
?>
