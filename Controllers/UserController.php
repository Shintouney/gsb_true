<?php

require_once 'Core'.D_S.'Controller.php';
require_once 'Models'.D_S.'Utilisateur.php';

class UserController extends Controller
{
    private static $errors = array(
        2 => 'Vous n\'avez pas remplis tout les champs.',
        3 => 'Vos identifiants de connexion sont incorrect.');

    // action de connexion
    public function index($id = 1)
    {
        $this->checkLogged();
        $error = $this->getError($id);

        if (isset($_POST['login']) && isset($_POST['mdp'])) {
            if (!empty($_POST['login']) && !empty($_POST['mdp'])) {
            	$auth  = Auth::getInstance();
            	$user  = Utilisateur::findByLogin($_POST['login']);

                if ($user && $auth->login($user, $_POST['mdp'])) {
                    $this->redirect();
                }
                else {
                    $this->redirect('?page=login&id=3');
                }
            }
            else if (empty($_POST['login']) || empty($_POST['mdp'])) {
                $this->redirect('?page=login&id=2');
            }
        }

        $this->render('LoginForm/login.php', array('error' => $error), 'no_template');
    }

    private function getError($id) 
    {
        if ($id == 1)
            return '';
        else if(isset($this::$errors[$id]) == false)
            return '';
        else
            return '<h2>Une erreur c\'est produite  </h2><p>'.$this::$errors[$id].'</p>';
    }

    /* Fonction qui redirige vers la page d'erreur,
    *  Lorsqu'on est déjà connnecté et qu'on veut se diriger vers la page de login
    */
    private function checkLogged() 
    {
        $auth  = Auth::getInstance();
        if ($auth->isLogged() == true)
            $this->redirect('?page=errors&id=3');
    }
}