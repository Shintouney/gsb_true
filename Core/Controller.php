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

    /**
    * retourne le mois au format aaaamm selon le jour dans le mois
    * @param string $date au format jj/mm/aaaa
    * @return string Le mois au format aaaamm
    */
    protected function getMois($date)
    {
        @list($jour, $mois, $annee) = explode('/', $date);
        if (strlen($mois) == 1) {
            $mois = "0" . $mois;
        }
        return $annee . $mois;
    }

    /**
    * Retourne le mois suivant la date passée en paramètre au format aaaamm
    * @param string $date au format aaaamm
    * @param integer $nb nombre de mois à avancer
    * @return string Le mois suivant ou en fonction de $nb au format aaaamm
    */
    protected function moisSuivant($date, $nb)
    {
        $date = $this->couperDate($date);
        for ($i = 0; $i < $nb; $i++){
            $date['mois'] = $date['mois']+1;
            if (strlen($date['mois']) == 1) {
                $date['mois'] = (int)"0" . $date['mois'];
            }
            if($date['mois'] == 13){
                $date['mois'] = "01";
                $date['annee'] = $date['annee']+1;
            }
        }
        return $date['annee'] . $date['mois'];
    }

    /**
    * Retourne le mois en toutes lettres d'un mois en chiffre
    * @param integer $chiffreMois numéro du mois 
    * @return string $mois le nom du mois en lettre
    */
    protected function retournerMoisLettre($chiffreMois) {
        $mois        = array(1=>'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
        $chiffreMois = (int)$chiffreMois;
        return $mois[$chiffreMois];
    }

    /**
    * Découpe la date en mois et année
    * @param string $date au format aaaamm
    * @return array $date un tableau contenant l'année et le mois
    */
    function couperDate($date){
        $annee = substr($date,0,4);
        $mois  = substr($date,4,6);
        return $date = array('annee' => $annee,'mois' => $mois);
    }

    /**
    * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
    * @param array $tabEntiers : le tableau
    * @return boolean $ok vrai ou faux
    */
    function estTableauEntiers($tabEntiers) {
        $ok = true;
        foreach ($tabEntiers as $unEntier) {
            if (!$this->estEntierPositif($unEntier)) {
                $ok = false;
            }
        }
        return $ok;
    }

    /**
    * Indique si une valeur est un entier positif ou nul
    * @param string $valeur valeur à vérifier
    * @return boolean false ou true
    */
    function estEntierPositif($valeur) {
        return preg_match("/[^0-9]/", $valeur) == 0;
    }
}