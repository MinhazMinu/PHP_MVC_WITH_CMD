<?php

namespace Controller;

use \Model\User;
use \Core\Request;

defined('ROOTPATH') or exit('Access Denied!');

class Login extends MainController
{
    public function index()
    {

        $data['user'] = new \Model\User;
        $req = new \Core\Request;
        if ($req->posted()) {
            $data['user']->login($_POST);
        }

        $this->view('login', $data);
    }
}
