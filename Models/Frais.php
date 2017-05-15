<?php

require_once 'Core'.D_S.'Database.php';

class Frais
{
    private static $db;

    /*
    * Constructeur qui init la base donnée
    */
    public function __construct()
    {
        Frais::$db = Database::getInstance();
    }

    // recupere ligne sql et genere retourne un objet a partir de l'id
    public static function find($id)
    {
        $data = Frais::$db->find($id, 'utilisateur');
        if (!$data) {
            return null;
        }
        $model = new Utilisateur();
        $model->setData($data);
        $role = Role::find($data['role_id']);
        $model->setRole($role);

    return $model;
    }

    public function estPremierFraisMois($idUtilisateur,$mois)
    {
        $ok = false;
        $req = "select count(*) as nblignesfrais from fichefrais 
        where fichefrais.mois = '$mois' and fichefrais.idUtilisateur = '$idUtilisateur'";
        $res = Frais::$db->query($req);
        if($res['nblignesfrais'] == 0){
            $ok = true;
        }
        return $ok;
    }

/**
 * Compte le nombre de fiche de frais à valider d'un visiteur pour un mois donné et un etat donné
 * @param string $idVisiteur Identifiant unique du visiteur
 * @param string $mois au format aaaamm
 * @param string $etat Valeur de l'état des fiches à compter
 * @return boolean $ok vrai ou faux
 */
    public function getCountFraisAValider($idUtilisateur,$mois,$etat){
        $req = "SELECT COUNT(fichefrais.idUtilisateur) AS nblignesfrais FROM fichefrais 
                WHERE fichefrais.idEtat='$etat' AND fichefrais.idUtilisateur='$idUtilisateur' AND fichefrais.mois='$mois'";
        $result = Frais::$db->query($req);
        $laLigne = $result;
        $ok = false;
        if($laLigne['nblignesfrais'] != 0){
                $ok = true;
        }
        return $ok;
    }

/**
 * Retourne les mois des fiche de frais à valider
 * @param string $etat Etat des fiches à sélectionner
 * @return array un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
 */
    public function getLesMoisAValider($etat){
        $req = "SELECT DISTINCT fichefrais.mois AS mois FROM fichefrais";
        if($etat!= null){
            $req = $req . " WHERE fichefrais.idEtat='$etat'";
        }
        $req = $req . " ORDER BY fichefrais.mois DESC";
        $res = Frais::$db->query($req, true);
        $lesMois = array();
        $laLigne = $res;
        foreach ($laLigne as $Ligne) {
            $mois = $Ligne['mois'];
            $numAnnee =substr($mois,0,4);
            $numMois =substr($mois,4,2);
            $lesMois["$mois"]=array(
                "mois"      => "$mois",
                "numAnnee"  => "$numAnnee",
                "numMois"   => "$numMois"
            );
        }
        return $lesMois;
    }

    public function getListVisiteur(){
        return Frais::$db->all('utilisateur');
        #$req = "SELECT utilisateur.id AS id, utilisateur.nom AS nom, utilisateur.prenom AS prenom FROM utilisateur
        #WHERE role_id = 1";
        #$res = Frais::$db->query($req);
        #$res->
        #return $res;
    }

    /**
    * Crée une nouvelle fiche de frais et ses lignes de frais
    * 
    * Création d'une nouvelle fiche de frais et les lignes de frait au forfait pour un visiteur
    * et un mois donnés. Récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat.
    * Ensuite, création d'une nouvelle fiche de frais avec un idEtat à 'CR' et création des lignes
    * de frais au forfait avec des quantités nulles 
    * @param string $idUtilisateur Identifiant unique du visiteur
    * @param sring $mois sous la forme aaaamm
    */
    public function creeNouvellesLignesFrais($idUtilisateur, $mois){
        $dernierMois     = $this->dernierMoisSaisi($idUtilisateur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idUtilisateur,$dernierMois);
        if($laDerniereFiche['idEtat']=='CR'){
            $this->majEtatFicheFrais($idUtilisateur, $dernierMois,'CL');
        }
        $req = "INSERT INTO fichefrais(idUtilisateur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
        VALUES('$idUtilisateur','$mois',0,0,now(),'CR')";
        Frais::$db->query($req);
        $lesIdFrais = $this->getLesIdFrais();
        foreach($lesIdFrais as $uneLigneIdFrais){
                $unIdFrais = $uneLigneIdFrais['idfrais'];
                $req = "INSERT INTO lignefraisforfait(idUtilisateur,mois,idFraisForfait,quantite) 
                VALUES('$idUtilisateur','$mois','$unIdFrais',0)";
                Frais::$db->query($req);
        }
        $req = "INSERT INTO etatFraisForfait(idUtilisateur,mois,idEtat,dateModif)
               VALUES('$idUtilisateur','$mois','CR',now())";
        Frais::$db->query($req);
    }

 /**
  * Retourne les informations d'un visiteur avec l'id en paramètre
  * @param string $id Identifiant unique du visiteur
  * @return array l'id, le nom, et le prénom sous forme d'un tableau associatif
  */     
    public function getLeVisiteur($id){
        $req = "SELECT utilisateur.id AS id, utilisateur.nom AS nom, utilisateur.prenom AS prenom, utilisateur.login AS login, 
        utilisateur.adresse AS adresse, utilisateur.date_embauche AS dateEmbauche,
        utilisateur.image AS image FROM utilisateur WHERE utilisateur.id='$id'";
        $result = Frais::$db->query($req);
        $laLigne = $result;
        return $laLigne;
    } 

/**
 * Modifie l'état et la date de modification d'un élément de frais hors forfait
 * @param integer $idFrais Identifiant du frais à mettre à jour
 * @param char(2) $etat Valeur d'état à renseigner
 */
    public function majEtatHorsFrais($idFrais, $etat){
        $req="UPDATE lignefraishorsforfait SET idEtat = '$etat', dateModif = now()
        WHERE lignefraishorsforfait.id = '$idFrais'";
        Frais::$db->query($req);
    }

/**
 * Retourne l'état d'une ligne de frais au forfait à un mois précis
 * @param string $idVisiteur Identifiant unique du visiteur
 * @param numeric $mois sous la forme aaaamm
 * @return array le libelle de l'état, l'id de l'état et la date de modif
 */
    public function getInfoEtatFrais($idUtilisateur,$mois){
        $req="SELECT situation.libelle AS etat, situation.id AS id, etatfraisforfait.dateModif AS date  FROM situation INNER JOIN etatFraisForfait
            ON situation.id = etatfraisforfait.idEtat
            WHERE etatfraisforfait.mois = '$mois' AND etatfraisforfait.idUtilisateur = '$idUtilisateur'";
        $res = Frais::$db->query($req);
        $laLigne = $res;
        return $laLigne;
    }

    /**
    * Retourne le dernier mois en cours d'un visiteur
    * @param string $idUtilisateur Identifiant unique du visiteur 
    * @return string $laLigne le mois sous la forme aaaamm
    */  
    public function dernierMoisSaisi($idUtilisateur){
        $req = "SELECT MAX(mois) AS dernierMois FROM fichefrais WHERE fichefrais.idutilisateur = '$idUtilisateur'";
        $res = Frais::$db->query($req);
        return $res['dernierMois'];
    }

    /**
    * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
    * @param string $idUtilisateur Identifiant unique du visiteur
    * @param numeric $mois sous la forme aaaamm
    * @return array un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
    */  
    public function getLesInfosFicheFrais($idUtilisateur, $mois){
        $req = "SELECT fichefrais.idEtat AS idEtat, fichefrais.dateModif AS dateModif, fichefrais.nbJustificatifs AS nbJustificatifs, 
                fichefrais.montantValide AS montantValide, etat.libelle AS libEtat FROM  fichefrais INNER JOIN etat ON fichefrais.idEtat = etat.id 
                WHERE fichefrais.idUtilisateur ='$idUtilisateur' AND fichefrais.mois = '$mois'";
        $res = Frais::$db->query($req);
        return $res;
    }

    /**
    * Modifie l'état et la date de modification d'une fiche de frais
    * Modifie le champ idEtat et met la date de modif à aujourd'hui
    * @param string $idUtilisateur Identifiant unique du visiteur
    * @param char(6) $mois sous la forme aaaamm
    * @param char(2) $etat Valeur d'état à renseigner
    * 
    */
    public function majEtatFicheFrais($idUtilisateur, $mois, $etat){
        $req = "UPDATE fichefrais SET idEtat = '$etat', dateModif = now() 
        WHERE fichefrais.idUtilisateur ='$idUtilisateur' AND fichefrais.mois = '$mois'";
        Frais::$db->query($req);
    }

    /**
    * Retourne tous les id de la table FraisForfait
    * @return array un tableau associatif 
    */
    public function getLesIdFrais(){
        $req = "SELECT fraisforfait.id AS idfrais FROM fraisforfait ORDER BY fraisforfait.id";
        $res = Frais::$db->query($req, true);
        return $res;
    }

    /**
    * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
    * concernées par les deux arguments
    * @param string $idVisiteur Identifiant unique du visiteur
    * @param numeric $mois sous la forme aaaamm
    * @return array l'id, le libelle et la quantité sous la forme d'un tableau associatif 
    */
    public function getLesFraisForfait($idUtilisateur, $mois){
        $req = "SELECT fraisforfait.id AS idfrais, fraisforfait.libelle AS libelle, fraisforfait.montant AS montant,
        lignefraisforfait.quantite AS quantite FROM lignefraisforfait INNER JOIN fraisforfait
        ON fraisforfait.id = lignefraisforfait.idfraisforfait 
        WHERE lignefraisforfait.idUtilisateur ='$idUtilisateur' AND lignefraisforfait.mois='$mois' 
        ORDER BY lignefraisforfait.idfraisforfait"; 
        $res = Frais::$db->query($req, true);
        return $res; 
    }

    /**
    * Met à jour la table lignefraisforfait pour un visiteur et
    * un mois donné en enregistrant les nouveaux montants
    * @param string $idVisiteur Identifiant unique du visiteur
    * @param string $mois sous la forme aaaamm
    * @param aray $lesFrais tableau associatif de clé  idFrais et de valeur la quantité pour ce frais
    */
    public function majFraisForfait($idUtilisateur, $mois, $lesFrais){
        $lesCles = array_keys($lesFrais);
        foreach($lesCles as $unIdFrais){
            $qte = $lesFrais[$unIdFrais];
            $req = "UPDATE lignefraisforfait SET lignefraisforfait.quantite = $qte
            WHERE lignefraisforfait.idUtilisateur = '$idUtilisateur' and lignefraisforfait.mois = '$mois'
            AND lignefraisforfait.idfraisforfait  = '$unIdFrais'";
            Frais::$db->query($req);
        }
    }

    /**
    * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
    * concernées par les deux arguments
    * La boucle foreach ne peut être utilisée ici car on procède
    * à une modification de la structure itérée - transformation du champ date-
    * @param string $idUtilisateur Identifiant unique du visiteur
    * @param numeric $mois sous la forme aaaamm
    * @return array tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
    */
    public function getLesFraisHorsForfait($idUtilisateur, $mois){
        $req = "SELECT situation.libelle As 'etat', lignefraishorsforfait.id, lignefraishorsforfait.date,
            lignefraishorsforfait.libelle, lignefraishorsforfait.montant, lignefraishorsforfait.dateModif, lignefraishorsforfait.idEtat
            FROM lignefraishorsforfait JOIN situation ON situation.id = lignefraishorsforfait.idEtat
            WHERE lignefraishorsforfait.idUtilisateur ='$idUtilisateur' 
            AND lignefraishorsforfait.mois = '$mois' ";
        $res = Frais::$db->query($req, true);
        $nbLignes = count($res);
        for ($i=0; $i<$nbLignes; $i++){
            $date = $res[$i]['date'];
            $res[$i]['date'] =  $this->dateAnglaisVersFrancais($date, false);
        }
        return $res; 
    }

    /**
    * Transforme une date au format format anglais aaaa-mm-jj vers le format français jj/mm/aaaa 
    * @param string $madate au format aaaa-mm-jj
    * @param boolean $hours Indique si true ou false $madate contient également l'heure
    * @return string $date la date au format format français jj/mm/aaaa
    */
    function dateAnglaisVersFrancais($maDate, $hours) {
        if($hours == true){
            @list($maDate, $heure) = explode(' ', $maDate);
        }
        @list($annee, $mois, $jour) = explode('-', $maDate);
        $date = "$jour" . "/" . $mois . "/" . $annee;
        if ($hours == false) {
            return $date;
        } else {
            return $date . " " . $heure ;
        }
    }

    /**
    * Crée un nouveau frais hors forfait pour un visiteur un mois donné
    * à partir des informations fournies en paramètre
    * @param $idUtilisateur
    * @param $mois sous la forme aaaamm
    * @param $libelle : le libelle du frais
    * @param $date : la date du frais au format français jj//mm/aaaa
    * @param $montant : le montant
    */
    public function creeNouveauFraisHorsForfait($idUtilisateur, $mois, $libelle, $date, $montant){
        $dateFr = $this->dateFrancaisVersAnglais($date);
        $req = "INSERT INTO lignefraishorsforfait 
        VALUES('','$idUtilisateur','$mois','$libelle','$dateFr','$montant',now(), 'CR')";
        Frais::$db->query($req);
    }

    /**
    * Transforme une date au format français jj/mm/aaaa vers le format anglais aaaa-mm-jj
    * @param string $madate au format jj/mm/aaaa
    * @return date La date au format anglais aaaa-mm-jj
    */
    function dateFrancaisVersAnglais($maDate) {
        @list($jour, $mois, $annee) = explode('/', $maDate);
        return date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));
    }

    /**
    * Supprime le frais hors forfait dont l'id est passé en paramètre
    * @param integer $idFrais du frais à supprimer
    */
    public function supprimerFraisHorsForfait($idFrais){
        $req = "DELETE FROM lignefraishorsforfait WHERE lignefraishorsforfait.id =$idFrais ";
        Frais::$db->query($req);
    }

    /**
    * Retourne les mois pour lesquel un visiteur a une fiche de frais
    * @param string $idVisiteur Identifiant unique du visiteur
    * @return array un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
    */
    public function getLesMoisDisponibles($idUtilisateur){
        $req = "SELECT fichefrais.mois AS mois FROM fichefrais WHERE fichefrais.idUtilisateur ='$idUtilisateur' 
        ORDER BY fichefrais.mois DESC ";
        $lesLignes = Frais::$db->query($req, true);
        $lesMois   = array();
        foreach($lesLignes as $Ligne) {
            $mois     = $Ligne['mois'];
            $numAnnee = substr( $mois,0,4);
            $numMois  = substr( $mois,4,2);
            $lesMois["$mois"] = array(
                "mois"      => "$mois",
                "numAnnee"  => "$numAnnee",
                "numMois"   => "$numMois"
            );
        }
        return $lesMois;
    }
}