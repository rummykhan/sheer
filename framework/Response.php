<?php

namespace Sheer;

use Exception;

/**
 * Class Response
 * @package Sheer
 */
class Response
{
    /**
     * @var string
     */
    protected $view_base_path = '../views/';
    /**
     * @var string
     */
    protected $extension = '.php';
    /**
     * @var array|null
     */
    protected $data = null;
    /**
     * @var null
     */
    protected $view = null;

    /**
     * Response constructor.
     * @param $view
     * @param null $data
     */
    public function __construct($view, $data = null)
    {
        $this->view = $view;
        $this->data = $data ?: [];
    }

    /**
     * Convert the given view to path.
     *
     * @param $view
     * @return mixed
     */
    protected function viewToPath($view)
    {
        return str_replace('.', '/', $view);
    }

    /**
     * Render the file
     * In fact this method does nothing it just includes the given view
     *
     * @throws Exception
     */
    public function render()
    {
        $path = "{$this->view_base_path}".$this->viewToPath($this->view)."{$this->extension}";

        if (!file_exists($path)) {
            throw new Exception("{$this->view} not found.");
        }

        include($path);
    }
}