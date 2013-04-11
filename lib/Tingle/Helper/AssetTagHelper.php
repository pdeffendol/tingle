<?php
namespace Tingle\Helper;

class AssetTagHelper
{
    private static $expansions = array();
    private static $asset_paths = array(
        'stylesheet' => '/stylesheets',
        'javascript' => '/javascripts');

    private static $feed_types = array(
        'rss' =>  'application/rss+xml',
        'atom' => 'application/atom+xml');

    /**
     * Return a stylesheet link tag for the given sources.
     */
    public static function stylesheet_link_tag($sources, $options = array())
    {
        $options = array_merge(
            array('media' => 'screen'),
            $options);
        unset($options['href'], $options['rel'], $options['type']);

        $urls = self::expand_asset_sources('stylesheet', $sources);

        $tags = array();
        foreach ($urls as $url)
        {
            $tags[] = TagHelper::tag('link', array_merge(array('rel'=>'stylesheet', 'href'=>$url, 'type'=>'text/css'), $options));
        }

        return implode("\n", $tags);
    }

    public static function javascript_include_tag($sources)
    {
        $urls = self::expand_asset_sources('javascript', $sources);
        $tags = array();
        foreach ($urls as $url)
        {
            $tags[] = TagHelper::content_tag('script', '', array('src'=>$url, 'type'=>'text/javascript'));
        }
        return implode("\n", $tags);
    }

    public static function feed_link_tag($url, $type = 'rss', $options = array())
    {
        $options = array_merge(
            array('title' => strtoupper($type)),
            $options);
        unset($options['href'], $options['rel'], $options['type']);

        return TagHelper::tag('link', array_merge(array('rel'=>'alternate', 'href'=>$url, 'type'=>self::$feed_types[$type]), $options));
    }

    public static function set_stylesheet_path($path)
    {
        self::set_default_asset_path('stylesheet', $path);
    }

    public static function set_javascript_path($path)
    {
        self::set_default_asset_path('javascript', $path);
    }

    public static function register_stylesheet_expansion($name, $urls)
    {
        self::register_expansion('stylesheet', $name, $urls);
    }

    public static function register_javascript_expansion($name, $urls)
    {
        self::register_expansion('javascript', $name, $urls);
    }

    private static function set_default_asset_path($type, $path)
    {
        self::$asset_paths[$type] = $path;
    }

    private static function register_expansion($type, $name, $urls)
    {
        self::$expansions[$type][$name] = (array)$urls;
    }

    private static function get_asset_path($type, $url)
    {
        return (substr($url, 0, 1) != '/') ? self::$asset_paths[$type].'/'.$url : $url;
    }

    private static function expand_asset_sources($type, $sources)
    {
        $paths = array();

        if (isset(self::$expansions[$type][$sources]))
        {
            $sources = self::$expansions[$type][$sources];
        }

        if (is_array($sources))
        {
            foreach ($sources as $u)
            {
                $paths = array_merge($paths, self::expand_asset_sources($type, $u));
            }
        }
        else
        {
            $paths[] = self::get_asset_path($type, $sources);
        }

        return $paths;
    }
}
?>