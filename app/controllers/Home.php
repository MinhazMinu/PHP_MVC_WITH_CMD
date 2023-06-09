<?php

class Home extends Controller
{
    public function index()
    {
        $user = new User;

        $user->update(['name' => 'bb', 'age' => 48], ['age' => 38], ['name' => 'minhaz']);
        $this->view("home");
    }
}
