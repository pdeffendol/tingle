<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../lib/helpers/AssetTag.php';

class AssetTagHelperTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$template = $this->getMock('Tingle_Template');
		$this->helper = new Tingle_Helper_AssetTag($template);
	}
	
	public function test_single_stylesheet()
	{
		$this->assertEquals('<link href="/stylesheets/style.css" media="screen" rel="stylesheet" type="text/css" />', $this->helper->stylesheet_link_tag('style.css'));
	}
	
	public function test_absolute_path_stylesheet()
	{
		$this->assertEquals('<link href="/elsewhere/style.css" media="screen" rel="stylesheet" type="text/css" />', $this->helper->stylesheet_link_tag('/elsewhere/style.css'));
	}
	
	public function test_single_stylesheet_with_media()
	{
		$this->assertEquals('<link href="/stylesheets/print.css" media="print" rel="stylesheet" type="text/css" />', $this->helper->stylesheet_link_tag('print.css', array('media' => 'print')));
	}
	
	public function test_single_stylesheet_without_media()
	{
		$this->assertEquals('<link href="/stylesheets/style.css" rel="stylesheet" type="text/css" />', $this->helper->stylesheet_link_tag('style.css', array('media' => null)));
	}
	
	public function test_multiple_stylesheets()
	{
		$html = $this->helper->stylesheet_link_tag(array('one.css', 'two.css'));
		$this->assertContains('<link href="/stylesheets/one.css" media="screen" rel="stylesheet" type="text/css" />', $html, "HTML references first stylesheet");
		$this->assertContains('<link href="/stylesheets/two.css" media="screen" rel="stylesheet" type="text/css" />', $html, "HTML references second stylesheet");
	}
	
	public function test_stylesheet_expansion()
	{
		$this->helper->register_stylesheet_expansion("all", array('one.css', 'two.css'));
		$html = $this->helper->stylesheet_link_tag("all");
		$this->assertContains('<link href="/stylesheets/one.css" media="screen" rel="stylesheet" type="text/css" />', $html, "HTML references first stylesheet");
		$this->assertContains('<link href="/stylesheets/two.css" media="screen" rel="stylesheet" type="text/css" />', $html, "HTML references second stylesheet");
	}

	public function test_single_javascript()
	{
		$this->assertEquals('<script src="/javascripts/script.js" type="text/javascript"></script>', $this->helper->javascript_include_tag('script.js'));
	}
	
	public function test_multiple_javascripts()
	{
		$html = $this->helper->javascript_include_tag(array('one.js', 'two.js'));
		$this->assertContains('<script src="/javascripts/one.js" type="text/javascript"></script>', $html, "HTML references first javascript");
		$this->assertContains('<script src="/javascripts/two.js" type="text/javascript"></script>', $html, "HTML references second javascript");
	}
	
	public function test_javascript_expansion()
	{
		$this->helper->register_javascript_expansion("all", array('one.js', 'two.js'));
		$html = $this->helper->javascript_include_tag("all");
		$this->assertContains('<script src="/javascripts/one.js" type="text/javascript"></script>', $html, "HTML references first javascript");
		$this->assertContains('<script src="/javascripts/two.js" type="text/javascript"></script>', $html, "HTML references second javascript");
	}
	
	public function test_rss_feed_link()
	{
		$this->assertEquals('<link href="feed.rss" rel="alternate" title="RSS" type="application/rss+xml" />', $this->helper->feed_link_tag('feed.rss'));
	}
	
	public function test_atom_feed_link()
	{
		$this->assertEquals('<link href="feed.atom" rel="alternate" title="ATOM" type="application/atom+xml" />', $this->helper->feed_link_tag('feed.atom', 'atom'));
	}
	
	public function feed_link_with_title()
	{
		$this->assertEquals('<link href="feed.rss" rel="alternate" title="My Feed" type="application/rss+xml" />', $this->helper->feed_link_tag('feed.rss', 'rss', array('title' => 'My Feed')));
	}
}
?>
