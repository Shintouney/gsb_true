<?php
# -
# Classe d'accès aux données.
# Utilise les services de la classe PDO
# MobyDick Project
# ~ L'appetit viens en mangeant
class Database
{
    # Instance de Database
    private static $_instance = null;

    // object pdo
    private $_gpdo;

    private function __construct()
    {
        # Constructeur db - Pattern singleton
        try
        {
            $options =
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_ERRMODE 			 => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
            $params = require 'Core'.D_S.'config.php';
            $this->_gpdo = new PDO('mysql:host='.$params['host'].';dbname='.$params['db_name'], $params['user'], $params['password'], $options);

        }
        catch (PDOException $e)
        {
            exit('Erreur : ' . $e->getMessage());
        }
    }

    # Retourne une instance de Database (existante ou nouvelle)
    public static function getInstance()
    {
        if (is_null(self::$_instance))
            self::$_instance = new Database();

        return self::$_instance;
    }

    // wrapper pour requete preparee sans specifier fields = false devrait etre une pdo::query
    public function query($sql, $multiple = false)
    {
        return $this->prepare($sql, false, $multiple);
    }

    // requete preparee
    public function prepare($sql, $fields = false, $multiple = false)
    {
        try
        {
            $statement = $this->_gpdo->prepare($sql);
            if ($fields)
            {
                foreach ($fields as $key => $value)
                {
                    if (is_int($value))
                        $dataType = PDO::PARAM_INT;
                    elseif (is_bool($value))
                        $dataType = PDO::PARAM_BOOL;
                    elseif (is_null($value))
                        $dataType = PDO::PARAM_NULL;
                    else
                        $dataType = PDO::PARAM_STR;

                    $statement->bindValue(':'.$key, $value, $dataType);
                }
            }
            $statement->execute();
            if($multiple)
                $result = $statement->fetchAll(PDO::FETCH_NAMED);
            else
                $result = $statement->fetch(PDO::FETCH_NAMED);

            $statement->closeCursor();
            return $result;
        }
        catch (Exception $e)
        {
            exit($e->getMessage());
        }
    }

    // select where id = valeur specifiee
    public function find($id, $table)
    {
        $id = array('id' => $id);
        $sql = 'SELECT * FROM '.$table.' WHERE id = :id';

        return $this->prepare($sql, $id);
    }

    // select where avec champs de filtre a specifier, multiple champs possible renvoit un seul modele
    public function findOneBy(array $filters, $table)
    {
        $sql = 'SELECT * FROM '.$table;
        $sql .= $this->addWhere($filters);

        return $this->prepare($sql, $filters);
    }

    // select where avec champs de filtre a specifier, multiple champs possible peut renvoit un tableau de modeles
    public function findBy(array $filters, $table, $order = null)
    {
        $sql = 'SELECT * FROM '.$table;
        $order = $order ? ' ORDER BY '.$order : '';
        $sql .= $this->addWhere($filters).$order;

        return $this->prepare($sql, $filters, true);
    }

    // requete insert
    public function create($table, $fields)
    {
        $sql = 'INSERT INTO '.$table.' SET ';

        // on recupere le nom des champs dans un tableau
        $keys = array_keys($fields);
        // on modifie les champs pour generer les colonnes de la requete de type nom = :nom
        $keys = array_map(function ($field) {return $field.' = :'.$field;}, $keys);
        // on cree la chaine de caractere avec le separateur  a partir du tableau keys
        $keys = implode( ', ', $keys);
        $sql = $sql.$keys;

        return $this->prepare($sql, $fields);
    }

    public function update($id, $table, $fields)
    {
        $keys = array_keys($fields);
        $keys = array_map(function ($field) {return $field.' = :'.$field;}, $keys);
        $keys = implode( ', ', $keys);
        $fields['id'] = $id;
        $sql = 'UPDATE '.$table.' SET '.$keys.' WHERE id = :id';

        return $this->prepare($sql, $fields);
    }

    // requete pour recuperer toutes les lignes d'une table
    public function all($table, $order = null)
    {
        $order = $order ? ' ORDER BY '.$order : '';
        $sql = 'SELECT * FROM '.$table.$order;

        return $this->query($sql, true);
    }

    public function pluck($fields, $table, $filters = null, $order = null)
    {
        $value = isset($fields['value']) ? $fields['value'].' AS value' : '';
        $label = isset($fields['label']) ? $fields['label'].' AS label' : '';
        $fields = empty($value) ? implode(', ', $fields) : $value.', '.$label;

        $order = $order ? ' ORDER BY '.$order : '';
        $sql = 'SELECT '.$fields.' FROM '.$table.$order;
        $where = $filters ? $this->addWhere($filters) : '';
        $sql .= $where.$order;

        $results =  $this->prepare($sql, $filters, true);
        if (!$results) {
            return false;
        }

        return $results;
    }

    // add SQL where clause
    public function addWhere($filters)
    {
        $where = array_keys($filters);
        $where = array_map(function ($filter) {return $filter.' = :'.$filter;}, $where);

        return ' WHERE '.implode( ' AND ', $where);
    }

    public function delete($id, $table)
    {
        return $this->prepare('DELETE FROM '.$table.' WHERE id = :id',
            array('id' => $id));
    }
}