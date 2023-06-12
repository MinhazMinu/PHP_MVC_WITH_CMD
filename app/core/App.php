<?php

// namespace Core;

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
        $URL = explode("/", trim($URL, "/"));
        return $URL;
    }

    private function loadController()
    {
        $URL = $this->splitURL();
        $filename = "../app/controllers/" . ucfirst($URL[0]) . ".php";
        if (file_exists($filename)) {
            require_once($filename);
            $this->controller = ucfirst($URL[0]);
            unset($URL[0]);
        } else {
            $filename = "../app/controllers/_404.php";
            require_once($filename);
            $this->controller = "_404";
        }
        $controller = new ('\Controller\\' . $this->controller);

        /** select method **/
        if (!empty($URL[1])) {
            if (method_exists($controller, $URL[1])) {
                $this->method = $URL[1];
                unset($URL[1]);
            }
        }

        call_user_func_array([$controller, $this->method], $URL);
    }
}
