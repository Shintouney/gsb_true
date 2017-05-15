<?php 

require_once 'Core'.D_S.'Controller.php';
require_once 'Models'.D_S.'Utilisateur.php';
require_once 'Models'.D_S.'Role.php';

class HomeController extends Controller
{
    protected static $errors = array(
        1 => 'Une erreurs c\'est produite.',
        2 => 'Cette page n\'existe pas.',
        3 => 'Vous êtes déjà connecté.',
        4 => 'Action interdite.',);

    public function error($id = 1)
    {
        $error = isset(self::$errors[$id]) === false ? self::$errors[$id] : self::$errors[1] ;
        $this->render('Home/error.php', array('error' => $error, 'pageName' => 'Erreur'));
    }

    public function index()
    {
        $this->render('Home/index.php');
    }

    public function redirectToLogin()
    {
        $this->redirect('?action=login');
    }

    // action login
    public function login()
    {
        $this->checkLogged();

        if (!empty($_POST)) {
            if (!empty ($_POST['login']) && (!empty($_POST['mdp']))) {
                $auth  = Auth::getInstance();
                $user  = Utilisateur::findOneByLogin($_POST['login']);

                if ($user && $auth->login($user, $_POST['mdp'])) {
                    $this->redirect();
                } else {
                    $_SESSION['login_error'] = 'Vos identifiants de connexion sont incorrect.';
                }
            } else {
                $_SESSION['login_error'] = 'Vous n\'avez pas remplis tout les champs.';
            }
            $this->redirect('?action=login');
        }
        $this->render('Home/login.php', array('template' => 'login'));
    }

    /* Fonction qui redirige vers la page d'erreur,
    *  Lorsqu'on est déjà connnecté et qu'on veut se diriger vers la page de login
    */
    private function checkLogged()
    {
        $auth  = Auth::getInstance();
        if ($auth->isLogged() == true)
            $this->redirect();
    }

    // action logout
    public function logout()
    {
        $auth = Auth::getInstance();
        $auth->logout();
    }
}