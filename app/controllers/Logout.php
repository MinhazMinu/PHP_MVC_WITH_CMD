<?php

namespace Controller;

use \Core\Session;

defined('ROOTPATH') or exit('Access Denied!');

class Logout extends MainController
{
    public function index()
    {
        $ses = new Session;
        $ses->logout();
        redirect('login');
    }
}
