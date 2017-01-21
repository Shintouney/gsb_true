<?php

require_once 'Core'.D_S.'Controller.php';
require_once 'Models'.D_S.'Utilisateur.php';
require_once 'Models'.D_S.'Frais.php';

class FraisController extends Controller
{
    public function index($id = 1)
    {
    	$frais = new Frais();
    	$user  = unserialize(serialize($_SESSION['user']));
    	$date  = $this->returnDateInfo();
    	if ($frais->estPremierFraisMois($user->getId(), $date['date']))
    		$frais->creeNouvellesLignesFrais($user->getId(), $date['date']);
       	$this->render('Frais/saisie_fiche.php', array_merge(array('pageName' => 'Saisie fiche de frais'), $date));
    }

    private function returnDateInfo()
    {
    	$mois 	  = $this->getMois(date("d/m/Y"));
    	$numDate  = $this->couperDate($mois);
    	return (array('date'     => $mois,
    				  'numDate'  => $numDate,
    				  'numAnnee' => substr($mois,0,4),
    				  'numMois'  => $this->retournerMoisLettre($numDate['mois'])));
    }
}