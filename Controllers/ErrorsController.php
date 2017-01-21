<?php 

require_once 'Core'.D_S.'Controller.php';
require_once 'Models'.D_S.'Utilisateur.php';

class ErrorsController extends Controller
{
	private static $errors = array(
    1 => 'Une erreurs c\'est produite.',
    2 => 'Cette page n\'existe pas.',
    3 => 'Vous êtes déjà connecté.');

    public function index($id = 1)
    {
    	$error = $this->getError($id);
    	$this->render('Home/errors.php', array('error' => $error));
    }

    private function getError($id) 
    {
    	if(isset($this::$errors[$id]) == false)
    		return $this::$errors[1];
    	else
        	return $this::$errors[$id];
    }
}