<?php

require_once 'Auth.php';

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
        $view = str_replace('/', D_S, $view);
        ob_start();
        extract($vars);
        require 'views'.D_S.$view;
        $content = ob_get_clean();
        require 'views'.D_S.'Template'.D_S.$template.'.php';
    }
}