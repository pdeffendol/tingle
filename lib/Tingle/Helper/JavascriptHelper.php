<?php
namespace Tingle\Helper;

class JavascriptHelper
{
    private static $escapes = array(
        '\\' => '\\\\',
        '</' => '<\/',
        "\r\n" => '\n',
        "\n" => '\n',
        "\r" => '\n',
        '"' => '\\"',
        "'" => "\\'" );

    /**
     * Escapes newlines, quotes, and slashes in a string, for use in Javascript.
     */
    public static function escape_javascript($javascript)
    {
        return $javascript ? str_replace(array_keys(self::$escapes), array_values(self::$escapes), $javascript) : '';
    }
}
