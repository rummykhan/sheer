<?php

namespace Sheer;

use Exception;
use ReflectionClass;

/**
 * Class Container
 * @package Sheer
 */
abstract class Container
{
    /**
     * Create Instances of Parameters
     *
     * @param array $parameters
     * @return array
     */
    protected function getDependencies(array $parameters)
    {
        $dependencies = [];

        // Iterate through all parameters and create their objects
        // Here we can iterate recursively to full all the dependencies.
        foreach ($parameters as $parameter) {
            $dependencies[] = $this->build($parameter->getClass()->name);
        }

        return $dependencies;
    }

    /**
     * Create Object and manage its dependencies.
     *
     * @param $class
     * @return object
     * @throws Exception
     */
    public function build($class)
    {
        // check if class exists or not
        if (!class_exists($class)) {
            throw new Exception("{$class} Class not found.");
        }

        // Get reflector for for the class
        $reflector = new ReflectionClass($class);

        // Get constructor of the the class
        $constructor = $reflector->getConstructor();

        // if constructor is null mean this class doesn't has a dependency
        if (is_null($constructor)) {
            return new $class();
        }

        // Get constructor parameters
        $parameters = $constructor->getParameters();

        // Get Dependencies as Objects
        $dependencies = $this->getDependencies($parameters);

        // Create Instance of the class by fulfilling their dependencies
        return $reflector->newInstanceArgs($dependencies);
    }
}