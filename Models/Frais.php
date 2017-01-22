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
        $req = "INSERT INTO ficheFrais(idUtilisateur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
        VALUES('$idUtilisateur','$mois',0,0,now(),'CR')";
        Frais::$db->query($req);
        $lesIdFrais = $this->getLesIdFrais();
        foreach($lesIdFrais as $uneLigneIdFrais){
                $unIdFrais = $uneLigneIdFrais['idfrais'];
                $req = "INSERT INTO ligneFraisForfait(idUtilisateur,mois,idFraisForfait,quantite) 
                VALUES('$idUtilisateur','$mois','$unIdFrais',0)";
                Frais::$db->query($req);
        }
        $req = "INSERT INTO etatFraisForfait(idUtilisateur,mois,idEtat,dateModif)
               VALUES('$idUtilisateur','$mois','CR',now())";
        Frais::$db->query($req);
    }

    /**
    * Retourne le dernier mois en cours d'un visiteur
    * @param string $idUtilisateur Identifiant unique du visiteur 
    * @return string $laLigne le mois sous la forme aaaamm
    */  
    public function dernierMoisSaisi($idUtilisateur){
        $req = "SELECT MAX(mois) AS dernierMois FROM ficheFrais WHERE ficheFrais.idutilisateur = '$idUtilisateur'";
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
        $req = "SELECT ficheFrais.idEtat AS idEtat, ficheFrais.dateModif AS dateModif, ficheFrais.nbJustificatifs AS nbJustificatifs, 
                ficheFrais.montantValide AS montantValide, etat.libelle AS libEtat FROM  ficheFrais INNER JOIN etat ON ficheFrais.idEtat = etat.id 
                WHERE ficheFrais.idUtilisateur ='$idUtilisateur' AND ficheFrais.mois = '$mois'";
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
        $req = "UPDATE ficheFrais SET idEtat = '$etat', dateModif = now() 
        WHERE ficheFrais.idUtilisateur ='$idUtilisateur' AND ficheFrais.mois = '$mois'";
        Frais::$db->query($req);
    }

    /**
    * Retourne tous les id de la table FraisForfait
    * @return array un tableau associatif 
    */
    public function getLesIdFrais(){
        $req = "SELECT fraisForfait.id AS idfrais FROM fraisForfait ORDER BY fraisForfait.id";
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
        $req = "SELECT fraisForfait.id AS idfrais, fraisForfait.libelle AS libelle, fraisForfait.montant AS montant,
        ligneFraisForfait.quantite AS quantite FROM ligneFraisForfait INNER JOIN fraisForfait
        ON fraisForfait.id = ligneFraisForfait.idfraisforfait 
        WHERE ligneFraisForfait.idUtilisateur ='$idUtilisateur' AND ligneFraisForfait.mois='$mois' 
        ORDER BY ligneFraisForfait.idfraisforfait"; 
        $res = Frais::$db->query($req, true);
        return $res; 
    }

    /**
    * Met à jour la table ligneFraisForfait pour un visiteur et
    * un mois donné en enregistrant les nouveaux montants
    * @param string $idVisiteur Identifiant unique du visiteur
    * @param string $mois sous la forme aaaamm
    * @param aray $lesFrais tableau associatif de clé  idFrais et de valeur la quantité pour ce frais
    */
    public function majFraisForfait($idUtilisateur, $mois, $lesFrais){
        $lesCles = array_keys($lesFrais);
        foreach($lesCles as $unIdFrais){
            $qte = $lesFrais[$unIdFrais];
            $req = "UPDATE ligneFraisForfait SET ligneFraisForfait.quantite = $qte
            WHERE ligneFraisForfait.idUtilisateur = '$idUtilisateur' and ligneFraisForfait.mois = '$mois'
            AND ligneFraisForfait.idfraisforfait  = '$unIdFrais'";
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
        $req = "SELECT situation.libelle As 'etat', ligneFraisHorsForfait.id, ligneFraisHorsForfait.date,
            ligneFraisHorsForfait.libelle, ligneFraisHorsForfait.montant, ligneFraisHorsForfait.dateModif, ligneFraisHorsForfait.idEtat
            FROM ligneFraisHorsForfait JOIN situation ON situation.id = ligneFraisHorsForfait.idEtat
            WHERE ligneFraisHorsForfait.idUtilisateur ='$idUtilisateur' 
            AND ligneFraisHorsForfait.mois = '$mois' ";
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
        $req = "INSERT INTO ligneFraisHorsForfait 
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
        $req = "DELETE FROM ligneFraisHorsForfait WHERE ligneFraisHorsForfait.id =$idFrais ";
        Frais::$db->query($req);
    }
}