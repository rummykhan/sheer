<?php

namespace App\Sheer\Support;

use Exception;

/**
 * Class Collection
 * @package App\Sheer\Support
 */
class Collection
{
    /**
     * @var array|mixed
     */
    protected $data = [];

    /**
     * Collection constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $this->normalize($data);
    }

    /**
     * Where clause for the collection.
     *
     * @param $key
     * @param $operator
     * @param null $value
     * @return Collection
     */
    public function where($key, $operator, $value = null)
    {
        if (!$value) {
            $value = $operator;
            $operator = '=';
        }

        return new self($this->iterateAndApplyWhere($key, $operator, $value));
    }

    /**
     * Add an array to collection data
     *
     * @param array $item
     */
    public function add(array $item)
    {
        $this->data[] = $item;
    }

    /**
     * iterate over collection data and apply where clause
     *
     * @param $field
     * @param $operator
     * @param $value
     * @return array
     */
    protected function iterateAndApplyWhere($field, $operator, $value)
    {
        $results = [];
        foreach ($this->data as $item) {
            if ($this->applyWhere($item, $field, $operator, $value)) {
                $results[] = $item;
            }
        }
        return $results;
    }

    /**
     * Apply where over collection item
     *
     * @param $item
     * @param $field
     * @param $operator
     * @param $value
     * @return bool
     */
    protected function applyWhere($item, $field, $operator, $value)
    {
        switch ($operator) {
            case '!=':
                return Arr::isNotEqual($item, $field, $value);
                break;
            case '>':
                return Arr::isGreaterThan($item, $field, $value);
                break;
            case '>=':
                return Arr::isGreaterThanEqual($item, $field, $value);
                break;
            case '<':
                return Arr::isLessThan($item, $field, $value);
                break;
            case '<=':
                return Arr::isLessThanEqual($item, $field, $value);
                break;
            case 'LIKE':
                return Arr::isLike($item, $field, $value);
                break;
            case 'EXISTS':
                return Arr::isExists($item, $field, $value);
                break;
            default:
                return Arr::isEqual($item, $field, $value);
                break;
        }
    }

    /**
     * normalize the data upon initialization
     *
     * @param $data
     * @return mixed
     * @throws Exception
     */
    protected function normalize($data)
    {
        if (is_array($data)) {
            return $data;
        } elseif (Str::isJson($data)) {
            return json_decode($data, true);
        }

        throw new Exception("Cannot create collection because data is invalid.");
    }


    /**
     * get first item of result
     *
     * @return mixed|null
     */
    public function first()
    {
        if( count($this->data) ){
            return $this->data[0];
        }

        return null;
    }
}