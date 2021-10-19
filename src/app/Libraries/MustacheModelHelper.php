<?php

namespace App\Libraries;

class MustacheModelHelper
{
    private $model;

    public function __construct(\Illuminate\Database\Eloquent\Model $model)
    {
        $this->model = $model;
    }

    public function __get($key)
    {
        if (isset($this->model->$key)) {
            $ret = $this->model->$key;
            if ($ret instanceof \Illuminate\Database\Eloquent\Model) {
                return new MustacheModelHelper($ret);
            }
            return $ret;
        }
        else if (method_exists($this->model, $key)) {
            return $this->model->$key();
        }
    }

    public function __isset($key)
    {
        if (isset($this->model->$key)) {
            return true;
        }
        else if (method_exists($this->model, $key)) {
            return true;
        }
        return false;
    }
}