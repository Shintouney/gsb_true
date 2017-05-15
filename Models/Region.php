<?php

class Region
{
    private $code;
    private $nom;

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

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

    //=================Active record methods============================//

    public  static function findOneByCode($code)
    {
        $db = Database::getInstance();
        $data = $db->findOneBy(array('code' => $code), 'region');
        if (!$data) {
            return null;
        }
        $model = new Region();
        $model->setData($data);

        return $model;
    }
} 