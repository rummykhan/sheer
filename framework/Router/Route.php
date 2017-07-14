<?php

namespace Sheer\Router;

use Sheer\Contracts\Support\Arrayable;
use Sheer\Contracts\Support\Jsonable;

class Route implements Arrayable, Jsonable
{
    protected $method = null;
    protected $uri = null;
    protected $compiled = null;
    protected $type = null;
    protected $parameters = [];
    protected $parameterNames = [];

    public function __construct($method, $uri, $type)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->type = $type;
    }

    public function compileRoute()
    {
        $this->updateParameterNames();

        if (count($this->parameterNames) && count($this->parameterNames[0])) {
            $this->compileParametersRegex();
        } else {
            $this->compileRegex();
        }

        return $this;
    }

    public function updateParameterNames()
    {
        preg_match_all('#\{(\w+)\}#', $this->getPath(), $this->parameterNames);
    }

    public function getPath()
    {
        return $this->uri;
    }

    public function getMethod()
    {
        return $this->method;
    }

    protected function compileParametersRegex()
    {
        $compiled = $this->getPath();

        $dictionary = [];

        foreach ($this->parameterNames[0] as $parameter) {

            $unique = sha1(uniqid());

            $dictionary[$unique] = preg_replace('/\}/', '', preg_replace('/\{/', '', $parameter));

            $compiled = str_replace($parameter, $unique, $compiled);
        }

        $compiled = preg_quote($compiled);

        foreach ($dictionary as $key => $item) {
            $compiled = str_replace($key, $item, $compiled);
        }

        foreach ($this->parameterNames[1] as $parameterName) {
            $compiled = str_replace($parameterName, "(?P<$parameterName>\w+)", $compiled);
        }

        $this->compiled = $this->addDelimiters($compiled);
    }

    protected function addDelimiters($value)
    {
        return "#^{$value}$#s";
    }

    protected function compileRegex()
    {
        $this->compiled = $this->addDelimiters($this->getPath());
    }

    public function match($path)
    {
        return preg_match($this->getCompiledRegex(), rawurldecode("/{$path}"), $this->parameters);
    }

    public function getCompiledRegex()
    {
        if (!!$this->compiled) {
            return $this->compiled;
        }

        $this->compileRoute();

        return $this->compiled;
    }

    public function getParameters()
    {
        $parameters = [];
        foreach ($this->parameterNames[1] as $parameter) {
            if (isset($this->parameters[$parameter])) {
                $parameters[$parameter] = $this->parameters[$parameter];
            }
        }

        return $parameters;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'method' => $this->getMethod(),
            'uri' => $this->getPath(),
            'parameters' => $this->getParameters(),
            'type' => $this->getType()
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }
}