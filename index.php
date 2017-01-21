<?php
/**
 * Fichier d'entrée de l'application
 * Met en place le MVC
 * @author  	 bruno et haitem
 * @package 	 vendor (chargeur du pluging du mail)
 * @version      1.0
 */
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

if (file_exists($path.$controller.'.php'))
{
	// inclusion du fichier controller avec require
	require_once $path.$controller.'.php';
	// instanciation du controller
	$controller = new $controller();
	// On verifie si la méthode existe
	if (method_exists($controller, $action)) {
		// execution de la méthode
		$id ? $controller->$action($id) : $controller->$action();
	}
	else
		header('Location: ' . $_SERVER['HTTP_ORIGIN'].$_SERVER['SCRIPT_NAME'].'?page=error&id=4');
}
else
	header('Location: ' . $_SERVER['HTTP_ORIGIN'].$_SERVER['SCRIPT_NAME'].'?page=error&id=2');

function notLogged()
{
    return !isset($_SESSION['logged']) || $_SESSION['logged'] == false;
}