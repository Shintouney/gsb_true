<?php

require_once 'Core'.D_S.'Controller.php';
require_once 'Models'.D_S.'Utilisateur.php';
require_once 'Models'.D_S.'Frais.php';

class FraisController extends Controller
{
	private static $user;
	private static $frais;
	private static $date;

	public function __construct()
    {
        FraisController::$user  = unserialize(serialize($_SESSION['user']));
        FraisController::$frais = new Frais();
        FraisController::$date = $this->returnDateInfo();
    }

    public function index()
    {
    	$lesFraisForfait = self::$frais->getLesFraisForfait(self::$user->getId(), self::$date['mois']);
    	if (self::$frais->estPremierFraisMois(self::$user->getId(), self::$date['mois']))
    	{
    		echo '<pre>';
    		var_dump(self::$date);
    		echo '</pre>';
    		die();
    		self::$frais->creeNouvellesLignesFrais(self::$user->getId(), self::$date['mois']);
    	}
       	$this->render('Frais/saisie_fiche.php', array_merge(array('pageName' => 'Saisie fiche de frais'), self::$date,  array('lesFrais' => $lesFraisForfait)));
    }

    private function returnDateInfo()
    {
    	$mois 	  = $this->getMois(date("d/m/Y"));
    	$numDate  = $this->couperDate($mois);
    	return (array('mois'     => $mois,
    				  'numDate'  => $numDate,
    				  'numAnnee' => substr($mois,0,4),
    				  'numMois'  => $this->retournerMoisLettre($numDate['mois'])));
    }

    public function validerForfait()
    {
    	$lesFrais = $_REQUEST['lesFrais'];
    	if ($this->lesQteFraisValides($lesFrais))
    		self::$frais->majFraisForfait(self::$user->getId(),self::$date['mois'],$lesFrais);
    	else
    		die();
    	$this->redirect('?page=frais');
    }

    /**
    * Vérifie que le tableau de frais ne contient que des valeurs numériques 
    * @param array $lesFrais tableau associatif de frais
    * @return boolean false ou true
    */
    private function lesQteFraisValides($lesFrais) {
        return $this->estTableauEntiers($lesFrais);
    }
}