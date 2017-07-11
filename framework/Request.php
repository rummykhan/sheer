<?php

namespace Sheer;

/**
 * Class Request
 * @package Core
 */
class Request
{
    /**
     * @var null
     */
    protected $root = null;
    /**
     * @var null
     */
    protected $method = null;
    /**
     * @var null
     */
    protected $path = null;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        // Update the required parameters from the global $_SERVER variable
        $this->init($_SERVER);
    }

    /**
     * Get the required parameter from the global $_SERVER
     * @param array $request
     */
    protected function init(array $request)
    {
        $this->root = $request['DOCUMENT_ROOT'];
        $this->method = $request['REQUEST_METHOD'];
        $this->path = $request['REQUEST_URI'];
    }

    /**
     * Get the root of the application public
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Get the request method
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->method;
    }

    /**
     * Get the request path
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}