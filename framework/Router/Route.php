<?php

namespace Sheer\Router;

use Sheer\Contracts\Support\Arrayable;
use Sheer\Contracts\Support\Jsonable;

class Route implements Arrayable, Jsonable
{
    /**
     * @var null
     */
    protected $method = null;
    /**
     * @var null
     */
    protected $uri = null;
    /**
     * @var null
     */
    protected $compiled = null;
    protected $type = null;
    protected $parameters = [];
    protected $parameterNames = [];

    /**
     * Route constructor.
     * @param $method
     * @param $uri
     * @param $type
     */
    public function __construct($method, $uri, $type)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->type = $type;
    }

    /**
     * This method compile the route, get its parameter.
     *
     * @return $this
     */
    public function compileRoute()
    {
        $this->updateParameterNames();

        // If there are parameters in the regex
        if (count($this->parameterNames) && count($this->parameterNames[0])) {

            // Then compile regex with parameters
            $this->compileParametersRegex();
        } else {
            // Compile Regex for the route
            $this->compileRegex();
        }

        return $this;
    }

    /**
     * Checks if this route has parameters,
     * If route has parameter, Update parameterNames from the route.
     */
    public function updateParameterNames()
    {
        preg_match_all('#\{(\w+)\}#', $this->getPath(), $this->parameterNames);
    }

    /**
     * get Path from route.
     * @return null
     */
    public function getPath()
    {
        return $this->uri;
    }

    /**
     * Get route method.
     *
     * @return null
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Compile parameters regex.
     */
    protected function compileParametersRegex()
    {
        // Consider the current path as compiled
        $compiled = $this->getPath();

        // Create empty dictionary to switch values only
        $dictionary = [];

        // Iterate over route parameters
        foreach ($this->parameterNames[0] as $parameter) {

            $unique = sha1(uniqid());

            // Replace the surrounding braces of the parameter with empty string
            // {name} will become name
            // Keep the track in the dictionary
            $dictionary[$unique] = preg_replace('/\}/', '', preg_replace('/\{/', '', $parameter));

            // In the route path replace the {name} with unique-id
            // path/to/{d} will become path/to/unique-id
            $compiled = str_replace($parameter, $unique, $compiled);
        }

        // Escape the route to be presented in regex if any.
        // we replace the {name} earlier which make it safe for preg_quote
        $compiled = preg_quote($compiled);

        // flip the array so tha twe have unique to replace at front facing.
        $dictionary = array_flip($dictionary);

        // iterate over dictionary
        foreach ($dictionary as $parameterName => $unique) {

            // Make sure that if this is the parameter we want to replace.
            if (in_array($parameterName, $this->parameterNames[1])) {

                // replace the unique with parameter name.
                $compiled = str_replace($unique, "(?P<$parameterName>\w+)", $compiled);
            }
        }

        // Add delimiters.
        $this->compiled = $this->addDelimiters($compiled);
    }

    /**
     * Add Delimiter to the values for final regex
     *
     * @param $value
     * @return string
     */
    protected function addDelimiters($value)
    {
        return "#^{$value}$#s";
    }

    /**
     * Add Delimiters to the Request Path
     */
    protected function compileRegex()
    {
        $this->compiled = $this->addDelimiters($this->getPath());
    }

    /**
     * Match the incoming path with its own compiled path.
     *
     * @param $path
     * @return int
     */
    public function match($path)
    {
        // return 0 if not matched or 1 if it is matched
        // which is finally coerced to boolean
        return preg_match($this->getCompiledRegex(), rawurldecode("/{$path}"), $this->parameters);
    }

    /**
     * Get compiled regex of its own path.
     * If it has already compiled, return that, otherwise compile and return
     *
     * @return string
     */
    public function getCompiledRegex()
    {
        if (!!$this->compiled) {
            return $this->compiled;
        }

        $this->compileRoute();

        return $this->compiled;
    }

    /**
     * Get parameter values from the incoming route
     * @return array
     */
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

    /**
     * Get
     * @return null
     */
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