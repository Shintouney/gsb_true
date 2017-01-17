<?php
session_start();

define('D_S', DIRECTORY_SEPARATOR);
define('Corp', 'Corp'.D_S);
define('BR', '<br/>');
define('ROOT', dirname(__DIR__));

$path 	= 'Controllers'.D_S;
$page 	= isset($_GET['page']) 	 ? $_GET['page'] 	: null;
$action = isset($_GET['action']) ? $_GET['action']  : 'index';
$id 	= isset($_GET['id'])	 ? $_GET['id'] 		: null;

// conditions pour rediriger vers login sinon on lance les actions standards sinon homepage
if ($page != 'login' && notLogged()) {
	$controller = 'HomeController';
	$action     = 'redirectLogin';
} else if ($page === 'login') {
	$controller = 'UserController';
} else if ($page) {
	$controller = ucfirst($page).'Controller';
} else {
    $controller = 'HomeController';
    $action 	= 'index';
}

// inclusion du fichier controller avec require
require_once $path.$controller.'.php';
// instanciation du controller
$controller = new $controller();
// execution de la méthode
$id ? $controller->$action($id) : $controller->$action();

function notLogged()
{
    return !isset($_SESSION['logged']) || $_SESSION['logged'] == false;
}