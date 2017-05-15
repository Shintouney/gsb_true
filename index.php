<?php 
session_start();

define('D_S', DIRECTORY_SEPARATOR);
define('Corp', 'Corp'.D_S);
define('BR', '<br/>');
define('ROOT', dirname(__DIR__));

//require_once 'vendor/autoload.php'; // for plugins (mailer etc.)

$path 	= 'Controllers'.D_S;
$page 	= isset($_GET['page']) 	 ? $_GET['page']     : 'home';
$action = isset($_GET['action']) ? $_GET['action'] 	 : 'index';
$id 	= isset($_GET['id'])	 ? $_GET['id']       : null;

// conditions pour rediriger vers login sinon on lance les actions standards sinon homepage
if ($page != 'password' && $action != 'login' && notLogged()) {
    $controller = 'HomeController';
    $action     = 'redirectToLogin';
} else {
    $controller = ucfirst($page).'Controller';
}

if (file_exists($path.$controller.'.php'))
{
    // inclusion du fichier avec require
    require_once $path.$controller.'.php';
    // instanciation du controller
    $controller = new $controller();
    // On verifie si la méthode existe
    if (method_exists($controller, $action)) {
        // execution de la méthode
        $id ? $controller->$action($id) : $controller->$action();
    } else {
        $error = 404;
    }
} else {
    $error = 404;
}

if(isset($error)) {
    header('Location: ' . $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].'?action=error&id=2');
    die();
}

function notLogged()
{
    return !isset($_SESSION['logged']) || $_SESSION['logged'] == false;
}
