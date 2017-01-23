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
    private static $errorshorsforfait;
    private static $lesMois;
    private static $lesFraisForfait;
    private static $lesFraisHorsForfait;
    private static $lesInfosFicheFrais;

	public function __construct()
    {
        self::$user                = unserialize(serialize($_SESSION['user']));
        self::$frais               = new Frais();
        self::$date                = $this->returnDateInfo();
        self::$errors              = array();
        self::$errorshorsforfait   = array();
        self::$lesMois             = self::$frais->getLesMoisDisponibles(self::$user->getId());
        self::$lesFraisForfait     = self::$frais->getLesFraisForfait(self::$user->getId(), self::$date['mois']);
        self::$lesFraisHorsForfait = self::$frais->getLesFraisHorsForfait(self::$user->getId(), self::$date['mois']);
        self::$lesInfosFicheFrais  = self::$frais->getLesInfosFicheFrais(self::$user->getId(), self::$date['mois']);
    }

    public function index()
    {
    	if (self::$frais->estPremierFraisMois(self::$user->getId(), self::$date['mois']))
    		self::$frais->creeNouvellesLignesFrais(self::$user->getId(), self::$date['mois']);
       	$this->render('Frais/saisie_fiche.php', array('pageName'  => 'Saisie fiche de frais',
                                                      'errorshf'  => self::$errorshorsforfait,
                                                      'errors'    => self::$errors,
                                                      'date'      => self::$date,
                                                      'fraishf'   => self::$lesFraisHorsForfait,
                                                      'lesFrais'  => self::$lesFraisForfait));
    }

    public function mesfiches()
    {
        $this->render('Frais/fiches_frais.php', array_merge(array('pageName'          => 'Mes fiches frais',
                                                                  'moisASelectionner' => $this->moisSelect(self::$lesMois),
                                                                  'lesMois'           => self::$lesMois)));
    }

    public function voirEtatFrais()
    {
        $numdate = $this->couperDate($this->moisSelect(self::$lesMois));
        $this->render('Frais/fiches_frais.php', array('pageName'          => 'Mes fiches frais',
                                                      'moisASelectionner' => $this->moisSelect(self::$lesMois),
                                                      'lesMois'           => self::$lesMois,
                                                      'fraishf'           => self::$lesFraisHorsForfait,
                                                      'lesFrais'          => self::$lesFraisForfait,
                                                      'infoFiche'         => self::$lesInfosFicheFrais,
                                                      'dateModif'         => self::$frais->dateAnglaisVersFrancais(self::$lesInfosFicheFrais['dateModif'], false),
                                                      'numDate'           => $numdate,
                                                      'txtMois'           => $this->retournerMoisLettre($numdate['mois'])));
    }

    private function moisSelect($lesMois)
    {
        $lesCles = array_keys($lesMois);
        return $lesCles[0];
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
        if (empty($_POST))
            $this->redirect('?page=frais');
        $this->valideInfosFrais($_REQUEST['dateFrais'], $_REQUEST['libelle'], $_REQUEST['montant']);
        $libelle = $this->replacequote($_REQUEST['libelle']);
        if (count(self::$errorshorsforfait) != 0)
            $this->index();
        else
        {
            self::$frais->creeNouveauFraisHorsForfait(self::$user->getId(), self::$date['mois'], $libelle, $_REQUEST['dateFrais'], $_REQUEST['montant']);
            $this->index();
        }
    }

    public function deleteFraisHf($id = NULL)
    {
        $lesFraisHorsForfait = self::$frais->getLesFraisHorsForfait(self::$user->getId(), self::$date['mois']);
        if (isset($id) && $this->checkBeforeDelete($lesFraisHorsForfait, $id)) {
            self::$frais->supprimerFraisHorsForfait($id);
            $this->redirect('?page=frais#element-horsforfait');
        }
        else
            $this->redirect('?page=frais');
    }

    private function checkBeforeDelete($lesFraisHorsForfait, $id)
    {
        foreach ($lesFraisHorsForfait as $frais)
        {
            if ($frais['id'] == $id)
                return (true);
        }
        return false;
    }

    private function returnDateInfo()
    {
        $mois     = $this->getMois(date("d/m/Y"));
        $numDate  = $this->couperDate($mois);
        return (array('mois'     => $mois,
                      'numDate'  => $numDate,
                      'numAnnee' => substr($mois,0,4),
                      'numMois'  => $this->retournerMoisLettre($numDate['mois'])));
    }

    /**
    * Vérifie la validité des trois arguments : la date, le libellé du frais et le montant 
    * des messages d'erreurs sont ajoutés au tableau des erreurs
    * @param string $dateFrais valeur de date à vérifier 
    * @param string $libelle valeur de libelle à vérifier
    * @param float $montant valeur de montant à vérifier
    */
    private function valideInfosFrais($dateFrais, $libelle, $montant) {
        if ($dateFrais == "") {
            $this->addErrorHorsForfait(array("date" => "Le champ date ne doit pas être vide"));
        } else {
            if (!$this->estDateValide($dateFrais)) {
                $this->addErrorHorsForfait(array("date_invalide" => "Date invalide"));
            } else {
                if ($this->estDateDepassee($dateFrais)) {
                    $this->addErrorHorsForfait(array("date_year" => "Date d'enregistrement du frais dépassé, plus de 1 an"));
                }
            }
        }
        if ($libelle == "") {
            $this->addErrorHorsForfait(array("libelle" => "Le libelle ne peut pas être vide"));
        }
        if ($montant == "") {
            $this->addErrorHorsForfait(array("montant" => "Le champ montant ne peut pas être vide"));
        } else {
            if (!is_numeric($montant)) {
                $this->addErrorHorsForfait(array("montant_invalide" => "Le champ montant doit être numérique"));
            }
        }
    }

    private function addError($errors)
    {
        return self::$errors = array_merge($errors, self::$errors);
    }

    private function addErrorHorsForfait($errors)
    {
        return self::$errorshorsforfait = array_merge($errors, self::$errorshorsforfait);
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