<?php

require_once 'Auth.php';
require_once 'Core'.D_S.'Database.php';

class Controller
{
    protected function redirect($url = '')
    {
        // on cree l'url avecv  nom hote / fichier / requete http
        $url = $_SERVER['HTTP_ORIGIN'].$_SERVER['SCRIPT_NAME'].$url;
        header('Location: ' . $url);
    }

    protected function render($view, $vars = array(), $template = 'default')
    {
        $vars = array_merge($vars, $this->userData());
        $view = str_replace('/', D_S, $view);
        ob_start();
        extract($vars);
        require 'views'.D_S.$view;
        $content = ob_get_clean();
        require 'views'.D_S.'Template'.D_S.$template.'.php';
    }

    protected function userData()
    {
        if (isset($_SESSION['user']) && isset($_SESSION['role']))
        {
            $user = unserialize(serialize($_SESSION['user']));
            $role = unserialize(serialize($_SESSION['role']));
            return (array('nom'    => $user->getNom(),
                          'prenom' => $user->getPrenom(),
                          'login'  => $user->getLogin(),
                          'role'   => ucfirst($role->getLibelle())));
        }
        else
            return (array('login' => 'undefined'));
    }
}