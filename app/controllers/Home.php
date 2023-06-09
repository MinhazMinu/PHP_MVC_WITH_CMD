<?php

class Home extends Controller
{
    public function index()
    {
        $model = new Model;
        $model->insert([
            'name' => 'Jan',
            'age' => 20,

        ]);
        $this->view("home");
    }
}
