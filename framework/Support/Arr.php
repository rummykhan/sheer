<?php

namespace Sheer\Support;


/**
 * Class Arr
 * @package Sheer\Support
 */
class Arr
{
    /**
     * Checks if field exists in Array.
     *
     * @param $item
     * @param $field
     * @param bool $value
     * @return bool
     */
    public static function isExists($item, $field, $value = true)
    {
        if ($value) {
            return isset($item[$field]);
        }

        return !isset($item[$field]);
    }

    /**
     * Checks if field is equal to value.
     *
     * @param $item
     * @param $field
     * @param $value
     * @return bool
     */
    public static function isEqual($item, $field, $value)
    {
        return Arr::isExists($item, $field) && $item[$field] === $value;
    }

    /**
     * Checks if field is not equal to value.
     * @param $item
     * @param $field
     * @param $value
     * @return bool
     */
    public static function isNotEqual($item, $field, $value)
    {
        return Arr::isExists($item, $field) && $item[$field] !== $value;
    }

    /**
     * Checks if field is greater then value.
     *
     * @param $item
     * @param $field
     * @param $value
     * @return bool
     */
    public static function isGreaterThan($item, $field, $value)
    {
        return Arr::isExists($item, $field) && $item[$field] > $value;
    }

    /**
     * Checks if field is greater then or equal to value.
     *
     * @param $item
     * @param $field
     * @param $value
     * @return bool
     */
    public static function isGreaterThanEqual($item, $field, $value)
    {
        return Arr::isExists($item, $field) && $item[$field] >= $value;
    }

    /**
     * Checks if field is less the value.
     *
     * @param $item
     * @param $field
     * @param $value
     * @return bool
     */
    public static function isLessThan($item, $field, $value)
    {
        return Arr::isExists($item, $field) && $item[$field] < $value;
    }

    /**
     * Check if field is less than or equal to value.
     * @param $item
     * @param $field
     * @param $value
     * @return bool
     */
    public static function isLessThanEqual($item, $field, $value)
    {
        return Arr::isExists($item, $field) && $item[$field] <= $value;
    }

    /**
     * Check if field is like value
     * @param $item
     * @param $field
     * @param $value
     * @return bool
     */
    public static function isLike($item, $field, $value)
    {
        return Arr::isExists($item, $field) && strpos($item[$field], $value) !== false;
    }
}