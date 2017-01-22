<?php

require_once 'Core'.D_S.'Controller.php';
require_once 'Models'.D_S.'Utilisateur.php';
require_once 'Models'.D_S.'Frais.php';

class FraisController extends Controller
{
	private static $user;
	private static $frais;
	private static $date;
    private static $errors;
    

	public function __construct()
    {
        self::$user   = unserialize(serialize($_SESSION['user']));
        self::$frais  = new Frais();
        self::$date   = $this->returnDateInfo();
        self::$errors = array();
    }

    public function index()
    {
    	if (self::$frais->estPremierFraisMois(self::$user->getId(), self::$date['mois']))
    		self::$frais->creeNouvellesLignesFrais(self::$user->getId(), self::$date['mois']);
        $lesFraisForfait     = self::$frais->getLesFraisForfait(self::$user->getId(), self::$date['mois']);
        $lesFraisHorsForfait = self::$frais->getLesFraisHorsForfait(self::$user->getId(), self::$date['mois']);
       	$this->render('Frais/saisie_fiche.php', array_merge(array('pageName' => 'Saisie fiche de frais'),
                                                            self::$date,  array('lesFrais' => $lesFraisForfait),
                                                            array('errors' => self::$errors)));
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
        {
    		self::$frais->majFraisForfait(self::$user->getId(), self::$date['mois'], $lesFrais);
            $this->redirect('?page=frais');
        }
    	else
        {
            $this->addError(array('errors' => '<strong>Attention !</strong> Les valeurs des frais doivent être numériques'));
            $this->index();
        }
    }

    public function validerCreationFrais()
    {
        $this->valideInfosFrais($_REQUEST['dateFrais'], $_REQUEST['libelle'], $_REQUEST['montant']);
    }

    /**
    * Vérifie la validité des trois arguments : la date, le libellé du frais et le montant 
    * des messages d'erreurs sont ajoutés au tableau des erreurs
    * @param string $dateFrais valeur de date à vérifier 
    * @param string $libelle valeur de libelle à vérifier
    * @param float $montant valeur de montant à vérifier
    */
    public function valideInfosFrais($dateFrais, $libelle, $montant) {
        if ($dateFrais == "") {
            $this->addError(array("date" => "Le champ date ne doit pas être vide"));
        } else {
            if (!$this->estDateValide($dateFrais)) {
                $this->addError(array("date_invalide" => "Date invalide"));
            } else {
                if ($this->estDateDepassee($dateFrais)) {
                    $this->addError(array("date_year" => "Date d'enregistrement du frais dépassé, plus de 1 an"));
                }
            }
        }
        if ($libelle == "") {
            $this->addError(array("libelle" => "Le libelle ne peut pas être vide"));
        }
        if ($montant == "") {
            $this->addError(array("montant" => "Le champ montant ne peut pas être vide"));
        } else {
            if (!is_numeric($montant)) {
                $this->addError(array("montant_invalide" => "Le champ montant doit être numérique"));
            }
        }
    }

    public function addError($errors)
    {
        return self::$errors = array_merge($errors, self::$errors);
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