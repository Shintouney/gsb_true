<?php

class Departement
{
    private $id;
    private $nom;
    private $regionCode;

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $regionCode
     */
    public function setRegionCode($regionCode)
    {
        $this->regionCode = $regionCode;
    }

    /**
     * @return string
     */
    public function getRegionCode()
    {
        return $this->regionCode;
    }

    //==================Active record methods===========================//

    public function getRegion()
    {
        return Region::findOneByCode($this->regionCode);
    }

    public static function findByCodePostal($code)
    {
        $db = Database::getInstance();
        $data = $db->find(substr($code, 0, 1), 'departement');
        if (!$data) {
            return null;
        }
        $model = new Departement();
        $model->setData($data);

        return $model;
    }
} 