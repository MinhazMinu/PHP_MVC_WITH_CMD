<?php

namespace Controller;

use \Core\Session;

defined('ROOTPATH') or exit('Access Denied!');

class Home extends MainController
{
    public function index()
    {
        $ses = new Session;
        if (!$ses->is_logged_in()) {
            redirect('login');
        }

        $this->view("home");
    }
}
