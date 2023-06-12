<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');
class _404 extends MainController
{
    public function index()
    {
        echo "404";
    }
}
