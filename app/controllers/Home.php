<?php

namespace Controller;

use \Model\User;

defined('ROOTPATH') or exit('Access Denied!');

class Home extends MainController
{
    public function index()
    {
        $user = new User;

        $res =  $user->select(['id' => 1])->get();

        $this->view("home");
    }
}
