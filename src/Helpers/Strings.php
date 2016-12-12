<?php

namespace Mcms\Core\Helpers;


class Strings
{
    /**
     * Parse named array as sprintf does not
     * @example : vksprintf('/users/%id$s/%title$s', ['id' => 1, 'title' => 'sd'])
     *
     * @param string $str
     * @param array $args
     * @return string
     */
    public function vksprintf($str, array $args)
    {
        if (is_object($args)) {
            $args = get_object_vars($args);
        }

        $map = array_flip(array_keys($args));

        $new_str = preg_replace_callback('/(^|[^%])%([a-zA-Z0-9_-]+)\$/',
            function ($m) use ($map) {
                return $m[1] . '%' . ($map[$m[2]] + 1) . '$';
            },
            $str);
        return vsprintf($new_str, $args);
    }
}