<?php

// TODO: Commented for production deploy.
// Display alert-error
// ini_set('display_errors', 1);
error_reporting(E_ALL);

//header("Cache-Control: no cache");
//session_cache_limiter("private_no_expire");
session_start();

define('ROOT', dirname((__FILE__)));
require_once(ROOT . '/components/Autoload.php');

$router = new Router();
$router->run();