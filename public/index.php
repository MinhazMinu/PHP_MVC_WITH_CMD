<?php
session_start();
$minPhpVersion = '8.0';
if (phpversion() < $minPhpVersion) {
    die("Your PHP version must be {$minPhpVersion} or higher to run this application.</br>Current version: " . phpversion() . ".");
}

define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
require "../app/core/init.php";

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

$app = new App;
