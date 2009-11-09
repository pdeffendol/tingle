<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/Tingle.php';

use Tingle\AssetTagHelper;

class AssetTagHelperTest extends PHPUnit_Framework_TestCase
{
	public function test_single_stylesheet()
	{
		$this->assertEquals('<link rel="stylesheet" href="/stylesheets/style.css" type="text/css" media="screen" />', AssetTagHelper::stylesheet_link_tag('style.css'));
	}
	
	public function test_absolute_path_stylesheet()
	{
		$this->assertEquals('<link rel="stylesheet" href="/elsewhere/style.css" type="text/css" media="screen" />', AssetTagHelper::stylesheet_link_tag('/elsewhere/style.css'));
	}
	
	public function test_single_stylesheet_with_media()
	{
		$this->assertEquals('<link rel="stylesheet" href="/stylesheets/print.css" type="text/css" media="print" />', AssetTagHelper::stylesheet_link_tag('print.css', array('media' => 'print')));
	}
	
	public function test_single_stylesheet_without_media()
	{
		$this->assertEquals('<link rel="stylesheet" href="/stylesheets/style.css" type="text/css" />', AssetTagHelper::stylesheet_link_tag('style.css', array('media' => null)));
	}
	
	public function test_multiple_stylesheets()
	{
		$html = AssetTagHelper::stylesheet_link_tag(array('one.css', 'two.css'));
		$this->assertContains('<link rel="stylesheet" href="/stylesheets/one.css" type="text/css" media="screen" />', $html, "HTML references first stylesheet");
		$this->assertContains('<link rel="stylesheet" href="/stylesheets/two.css" type="text/css" media="screen" />', $html, "HTML references second stylesheet");
	}
	
	public function test_stylesheet_expansion()
	{
		AssetTagHelper::register_stylesheet_expansion("all", array('one.css', 'two.css'));
		$html = AssetTagHelper::stylesheet_link_tag("all");
		$this->assertContains('<link rel="stylesheet" href="/stylesheets/one.css" type="text/css" media="screen" />', $html, "HTML references first stylesheet");
		$this->assertContains('<link rel="stylesheet" href="/stylesheets/two.css" type="text/css" media="screen" />', $html, "HTML references second stylesheet");
	}

	public function test_single_javascript()
	{
		$this->assertEquals('<script src="/javascripts/script.js" type="text/javascript"></script>', AssetTagHelper::javascript_include_tag('script.js'));
	}
	
	public function test_multiple_javascripts()
	{
		$html = AssetTagHelper::javascript_include_tag(array('one.js', 'two.js'));
		$this->assertContains('<script src="/javascripts/one.js" type="text/javascript"></script>', $html, "HTML references first javascript");
		$this->assertContains('<script src="/javascripts/two.js" type="text/javascript"></script>', $html, "HTML references second javascript");
	}
	
	public function test_javascript_expansion()
	{
		AssetTagHelper::register_javascript_expansion("all", array('one.js', 'two.js'));
		$html = AssetTagHelper::javascript_include_tag("all");
		$this->assertContains('<script src="/javascripts/one.js" type="text/javascript"></script>', $html, "HTML references first javascript");
		$this->assertContains('<script src="/javascripts/two.js" type="text/javascript"></script>', $html, "HTML references second javascript");
	}
	
	public function test_rss_feed_link()
	{
		$this->assertEquals('<link rel="alternate" href="feed.rss" type="application/rss+xml" title="RSS" />', AssetTagHelper::feed_link_tag('feed.rss'));
	}
	
	public function test_atom_feed_link()
	{
		$this->assertEquals('<link rel="alternate" href="feed.atom" type="application/atom+xml" title="ATOM" />', AssetTagHelper::feed_link_tag('feed.atom', 'atom'));
	}
	
	public function feed_link_with_title()
	{
		$this->assertEquals('<link rel="alternate" href="feed.rss" type="application/rss+xml" title="My Feed" />', AssetTagHelper::feed_link_tag('feed.rss', 'rss', array('title' => 'My Feed')));
	}
}
?>
