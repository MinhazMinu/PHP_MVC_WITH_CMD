<?php

class Home extends Controller
{
    public function index()
    {
        $model = new Model;

        $model->update(['name' => 'aa', 'age' => 38], ['age' => 30], ['name' => 'minhaz']);
        $this->view("home");
    }
}
