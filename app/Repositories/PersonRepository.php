<?php

namespace App\Repositories;

use App\Models\Person;

class PersonRepository
{
    protected $model = null;

    public function all()
    {
        if( !$this->model ){
            $this->model = new Person();
        }

        return $this->model->all();
    }
}