<?php

class Commune
{
    private $id;
    private $nom;
    private $codePostal;

    public function getId()
    {
        return $this->id;
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

    /**
     * @param string $codePostal
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;
    }

    /**
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    // hydrate un objet a partir d'une table de hachage
    public function setData($data)
    {
        if (is_array($data)) {
            foreach ($data as $field => $value) {
                if (!preg_match( '/_id$/', $field)) {
                    $field = $this->camelize($field);
                    $this->$field = $value;
                }
            }
        }
    }

    // transforme camelCase en snake_case
    public function decamelize($string)
    {
        $string = strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $string));

        return $string;
    }

    // transforme snake_case et kebab-case en camelCase
    public function camelize($string, $upper = false)
    {
        $delimiter = strpos($string, '_')? '_' : (strpos($string, '-')? '-' : null);
        if($delimiter) {
            $string = explode('_', $string);
            $string = array_map('ucfirst', $string);
            $string[0] = $upper === false ? lcfirst($string[0]) : $string[0];
            $string = implode($string);
        }

        return $string;
    }

    //--------------------------- Active record methods ----------//

    public function getDepartement()
    {
         return Departement::findbyCodePostal($this->codePostal);
    }

    public static function find($id)
    {
        $db = Database::getInstance();
        $data = $db->find($id, 'commune');
        if (!$data) {
            return null;
        }
        $model = new Commune();
        $model->setData($data);

        return $model;
    }

    // recupere ligne sql et genere/ retourne un objet champs de recherche a specifier
    public static function findOneBy($filter)
    {
        $db = Database::getInstance();
        $data = $db->findOneBy($filter, 'commune');
        if (!$data) {
            return null;
        }
        $model = new Commune();
        $model->setData($data);

        return $model;
    }

    public static function all()
    {
        $db = Database::getInstance();
        $list = $db->all( 'commune');
        foreach ($list as &$model) {
            $data = $model;
            $model = new Commune();
            $model->setData($data);
        }

        return $list;
    }

    public static function findIdFromData($cp, $commune)
    {
        $db = Database::getInstance();
        $row = $db->pluck(array('id'), 'commune', array('code_postal' => $cp, 'nom' => $commune));

        return $row ? array_shift($row) : '';
    }

    public static function options($code)
    {
        $db = Database::getInstance();
        $list = $db->pluck(array('value' => 'id', 'label' => 'nom'), 'commune', array('code_postal' => $code));

        return $list;
    }
}