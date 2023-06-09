<?php
class APP
{
    private $controller = "Home";
    private $method = "index";
    public function __construct()
    {
        $this->loadController();
    }
    private function splitURL()
    {
        $URL = $_GET['url'] ?? "Home";
        $URL = explode("/", $URL);
        return $URL;
    }

    private function loadController()
    {
        $URL = $this->splitURL();
        $filename = "../app/controllers/" . ucfirst($URL[0]) . ".php";
        if (file_exists($filename)) {
            require_once($filename);
            $this->controller = ucfirst($URL[0]);
        } else {
            $filename = "../app/controllers/_404.php";
            require_once($filename);
            $this->controller = "_404";
        }

        $controller = new $this->controller;
        call_user_func_array([$controller, $this->method], []);
    }
}
