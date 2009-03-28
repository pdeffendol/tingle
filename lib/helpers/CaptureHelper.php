<?php
require_once dirname(__FILE__).'/../CaptureContent.php';

class Tingle_CaptureHelper
{
	private static $contents = array();
	
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
	public static function content_for($name)
	{
		if (!isset(self::$contents[$name]))
		{
			self::$contents[$name] = new Tingle_CaptureContent($name);
		}
		
		return self::$contents[$name];
	}
}
?>
