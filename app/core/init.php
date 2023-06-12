<?php
defined('ROOTPATH') or exit('Access Denied!');
spl_autoload_register(function ($className) {
    $className = explode("\\", $className);
    $className = end($className);
    require "../app/models/" . ucfirst($className) . ".php";
});

require "config.php";
require "functions.php";
require "Database.php";
require "Model.php";
require "MainController.php";
require "App.php";
