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
        self::$user                = unserialize($_SESSION['user']);
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
       if($this->getUser()->is('ROLE_COMPTABLE'))
            {
               $this->redirect('?action=error&id=2');
        }
      if (!empty($_POST['moism'])) {
        $year = date('Y', strtotime($_POST['moism']));
        $month = date('m', strtotime($_POST['moism']));
        self::$date = $this->returnDateInfol($month, $year);
      }else
        self::$date = $this->returnDateInfo();
    	if (self::$frais->estPremierFraisMois(self::$user->getId(), self::$date['mois']))
    		self::$frais->creeNouvellesLignesFrais(self::$user->getId(), self::$date['mois']);
       	$this->render('Frais/saisie_fiche.php', array('pageName'  => 'Saisie fiche de frais',
                                                      'errorshf'  => self::$errorshorsforfait,
                                                      'errors'    => self::$errors,
                                                      'date'      => self::$date,
                                                      'fraishf'   => self::$lesFraisHorsForfait,
                                                      'lesFrais'  => self::$lesFraisForfait));
    }

    public function validerFraisHf($id = NULL)
    {
      self::$frais->majEtatHorsFrais($id, 'VA');
      $lesVisiteurs = self::$frais->getListVisiteur();
      $numdate = $this->couperDate($this->moisSelect(self::$lesMois));
      $lesMois = self::$frais->getLesMoisAValider('CL');
      $this->render('Frais/validation.php', array('pageName' => 'Valider les fiches de frais du mois écoulé',
            'lesVisiteurs' => $lesVisiteurs,
            'lesMois' => $lesMois,
            'infos'  => true,
            'infol' => false,
            'validateHF' => true));
    }

    public function voirFraisAValider()
    {
        if($this->getUser()->is('ROLE_USER'))
            {
               $this->redirect('?action=error&id=2');
        }
        $lesVisiteurs = self::$frais->getCountFraisAValider($_POST['utilisateur'], $_POST['MoisSelect'], 'CL');
        if($lesVisiteurs)
        {
          self::$lesFraisForfait     = self::$frais->getLesFraisForfait($_POST['utilisateur'], $_POST['MoisSelect']);
          self::$lesFraisHorsForfait = self::$frais->getLesFraisHorsForfait($_POST['utilisateur'], $_POST['MoisSelect']);
          self::$lesInfosFicheFrais  = self::$frais->getLesInfosFicheFrais($_POST['utilisateur'], $_POST['MoisSelect']);
          $etatFraisForfait = self::$frais->getInfoEtatFrais($_POST['utilisateur'], $_POST['MoisSelect']);
          $numDate = $this->couperDate($_POST['MoisSelect']);
          $txtMois = $this->retournerMoisLettre($numDate['mois']);
          $libEtat = self::$lesInfosFicheFrais['libEtat'];
          $lesMois = self::$frais->getLesMoisAValider('CL');
          $lesVisiteurs = self::$frais->getListVisiteur();
          $Visiteur = self::$frais->getLeVisiteur($_POST['utilisateur']);
          $this->render('Frais/validation.php', array('pageName' => 'Valider les fiches de frais du mois écoulé',
            'lesVisiteurs' => $lesVisiteurs,
            'lesMois' => $lesMois,
            'infos'  => false,
            'infol' => true,
            'monthr' => $_POST['MoisSelect'],
            'txtMois' => $txtMois,
            'numDate' => $numDate,
            'libEtat' => $libEtat,
            'lesFrais' => self::$lesFraisForfait,
            'lesFraisHorsForfait' => self::$lesFraisHorsForfait,
            'lesInfosFicheFrais' => self::$lesInfosFicheFrais,
            'Visiteur' => $Visiteur));
        }
        else
        {
          $lesVisiteurs = self::$frais->getListVisiteur();
          $numdate = $this->couperDate($this->moisSelect(self::$lesMois));
          $lesMois = self::$frais->getLesMoisAValider('CL');
          $this->render('Frais/validation.php', array('pageName' => 'Valider les fiches de frais du mois écoulé',
            'lesVisiteurs' => $lesVisiteurs,
            'lesMois' => $lesMois,
            'infos'  => true,
            'infol' => false));
        }
    }

    public function validationFraisSelection()
    {
        if($this->getUser()->is('ROLE_USER'))
            {
               $this->redirect('?action=error&id=2');
        }
        $lesVisiteurs = self::$frais->getListVisiteur();
        $numdate = $this->couperDate($this->moisSelect(self::$lesMois));
        $lesMois = self::$frais->getLesMoisAValider('CL');
        $this->render('Frais/validation.php', array('pageName' => 'Valider les fiches de frais du mois écoulé',
          'lesVisiteurs' => $lesVisiteurs,
          'lesMois' => $lesMois,
          'infos' => false,
          'infol' => false));
    }

    public function fraism()
    {
        $numdate = $this->couperDate($this->moisSelect(self::$lesMois));
        $this->render('Frais/month.php', array('pageName'          => 'Mes fiches frais',
                                                      'moisASelectionner' => $this->moisSelect(self::$lesMois),
                                                      'lesMois'           => self::$lesMois,
                                                      'fraishf'           => self::$lesFraisHorsForfait,
                                                      'lesFrais'          => self::$lesFraisForfait,
                                                      'infoFiche'         => self::$lesInfosFicheFrais,
                                                      'dateModif'         => self::$frais->dateAnglaisVersFrancais(self::$lesInfosFicheFrais['dateModif'], false),
                                                      'numDate'           => $numdate,
                                                      'txtMois'           => $this->retournerMoisLettre($numdate['mois'])));
    }


    public function mesfiches()
    {
        if($this->getUser()->is('ROLE_COMPTABLE'))
            {
               $this->redirect('?action=error&id=2');
            }
        $this->render('Frais/fiches_frais.php', array('pageName'          => 'Mes fiches frais',
                                                      'moisASelectionner' => $this->moisSelect(self::$lesMois),
                                                      'lesMois'           => self::$lesMois));
    }

    public function voirEtatFrais()
    {
        if($this->getUser()->is('ROLE_COMPTABLE'))
            {
               $this->redirect('?action=error&id=2');
        }
        self::$date = $this->returnDateInfol(substr($_POST['listMois'], 0, 4), substr($_POST['listMois'], 4, 6));
        $numdate = $this->couperDate($this->moisSelect(self::$lesMois));
        self::$lesFraisForfait     = self::$frais->getLesFraisForfait(self::$user->getId(), $_POST['listMois']);
        self::$lesFraisHorsForfait = self::$frais->getLesFraisHorsForfait(self::$user->getId(),  $_POST['listMois']);
        self::$lesInfosFicheFrais  = self::$frais->getLesInfosFicheFrais(self::$user->getId(),  $_POST['listMois']);
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

    public function validerForfaitF($id = NULL)
    {
      $lesFrais = $_REQUEST['lesFrais'];
      self::$frais->majFraisForfait($id, $_POST['date'], $lesFrais);
      self::$frais->majEtatHorsFrais($id, 'VA');
      $lesVisiteurs = self::$frais->getListVisiteur();
      $numdate = $this->couperDate($this->moisSelect(self::$lesMois));
      $lesMois = self::$frais->getLesMoisAValider('CL');
      $this->render('Frais/validation.php', array('pageName' => 'Valider les fiches de frais du mois écoulé',
            'lesVisiteurs' => $lesVisiteurs,
            'lesMois' => $lesMois,
            'infos'  => true,
            'infol' => false,
            'validateForfait' => true));
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

    public function deleteFraisHfF($id = NULL)
    {
            self::$frais->supprimerFraisHorsForfait($id);
            $this->redirect('?page=frais&action=validationFraisSelection');
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

    private function returnDateInfol($month, $year)
    {
        $mois     = $this->getMois(date("d/".$month."/".$year.""));
        $numDate  = $this->couperDate($mois);
        return (array('mois'     => $mois,
                      'numDate'  => $numDate,
                      'numAnnee' => substr($mois,0,4),
                      'numMois'  => $this->retournerMoisLettre($numDate)));
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

    /**
     * Vérifie la validité du format d'une date française jj/mm/aaaa
     * @param string $date valeur de date à vérifier
     * @return boolean $dateOK vrai ou faux
     */
    function estDateValide($date) {
        $tabDate = explode('/', $date);
        $dateOK = true;
        if (count($tabDate) != 3) {
            $dateOK = false;
        } else {
            if (!$this->estTableauEntiers($tabDate)) {
                $dateOK = false;
            } else {
                if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
                    $dateOK = false;
                }
            }
        }
        return $dateOK;
    }

    /**
     * Vérifie si une date est inférieure d'un an à la date actuelle
     * @param string $dateTestee valeur de la date à comparer
     * @return boolean false ou true
     */
    function estDateDepassee($dateTestee) {
        $dateActuelle = date("d/m/Y");
        @list($jour, $mois, $annee) = explode('/', $dateActuelle);
        $annee--;
        $AnPasse = $annee . $mois . $jour;
        @list($jourTeste, $moisTeste, $anneeTeste) = explode('/', $dateTestee);
        return ($anneeTeste . $moisTeste . $jourTeste < $AnPasse);
    }

    /**
     * Vérifie si une quote existe dans la chaine passée en paramètre
     * et insère un caractère d'échapement devant s'il y en a.
     * @param string $chaine à vérifier
     * @return string nouvelle chaine
     */
    function replacequote($chaine){
        // Vérifie si un caractère
        $pos = strpos($chaine, "'");
        if($pos === false){
            $result = $chaine;
        }else{
            $result = str_replace("'", "\'", $chaine);
        }
        return $result;
    }
}