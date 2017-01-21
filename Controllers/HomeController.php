<?php

require_once 'Core'.D_S.'Controller.php';
require_once 'Models'.D_S.'Utilisateur.php';


class HomeController extends Controller
{
    public function index()
    {
        $this->render('Home/index.php', array('pageName' => 'Accueil'));
    }
    
    public function redirectLogin()
    {
        $this->redirect('?page=login&id=1');
    }
}