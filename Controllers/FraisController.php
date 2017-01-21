<?php 

require_once 'Core'.D_S.'Controller.php';
require_once 'Models'.D_S.'Utilisateur.php';

class FraisController extends Controller
{
    public function index($id = 1)
    {
       	$this->render('Frais/saisie_fiche.php', array('pageName' => 'Saisie fiche de frais'));
    }
}