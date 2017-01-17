<?php

require_once 'Core'.D_S.'Controller.php';
require_once 'Models'.D_S.'Utilisateur.php';

class UserController extends Controller
{
    private static $errors = array(
        2 => 'Vous n\'avez spécifié aucun champ.',
        3 => 'Vos identifiants de connexion sont incorrect.');

    public function index($id)
    {
        $auth  = new Auth();
        $bruno = new Utilisateur();
        $bruno->setLogin('bruno');
        $bruno->encrypt('1234');
        $error = $this->getError($id);

        if (isset($_POST['login']) && isset($_POST['mdp'])) {
            if (!empty($_POST['login']) && !empty($_POST['mdp'])) {
                if ($auth->login($bruno, $_POST['mdp'])) {
                    echo "mdp correct";
                    die();
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
        else
            return '<h2>Une erreur c\'est produite  </h2><p>'.$this::$errors[$id].'</p>';
    }
}