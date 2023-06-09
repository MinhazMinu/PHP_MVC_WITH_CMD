<?php

class Home extends Controller
{
    public function index()
    {
        $user = new User;

        $user->update(['name' => 'bb', 'sdads' => 48]);
        $this->view("home");
    }
}
