<?php

require_once 'Core'.D_S.'Database.php';

class Frais
{
    private static $db = NULL;

    /*
    * Constructeur qui init la base donnée
    */
    public function __construct()
    {
        Frais::$db = Database::getInstance();
    }

    // recupere ligne sql et genere/ retourne un objet a partir de l'id
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
        $req = "INSERT INTO ficheFrais(idutilisateur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
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
}