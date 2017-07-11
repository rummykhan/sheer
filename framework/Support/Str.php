<?php

namespace Sheer\Support;

/**
 * Class Str
 * @package Sheer\Support
 */
class Str
{
    /**
     * Checks if string is JSON string
     *
     * @param $string
     * @return bool
     */
    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}