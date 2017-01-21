<?php 

require_once 'Core'.D_S.'Controller.php';
require_once 'Models'.D_S.'Utilisateur.php';

class ErrorController extends Controller
{
	private static $errors = array(
    1 => 'Une erreurs c\'est produite.',
    2 => 'Cette page n\'existe pas.',
    3 => 'Vous êtes déjà connecté.',
    4 => 'Action interdite.',);

    public function index($id = 1)
    {
    	$error = $this->getError($id);
    	$this->render('Home/error.php', array('error' => $error, 'pageName' => 'Erreur'));
    }

    private function getError($id) 
    {
    	if(isset($this::$errors[$id]) == false)
    		return $this::$errors[1];
    	else
        	return $this::$errors[$id];
    }

}