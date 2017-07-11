<?php

namespace App\Controllers;

use App\Sheer\Response;
use App\Repositories\PersonRepository;

class HomeController
{
    protected $repository = null;

    public function __construct(PersonRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data = $this->repository->all();

        return new Response('home.index', $data);
    }

    public function test()
    {
        return new Response('home.test');
    }
}