<?php

/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 10:39
 */
require_once(dirname(__FILE__) . "/../config.php");
require_once(dirname(__FILE__) . "/../functions.php");

class Rank
{
    private $_idRank;
    private $_rankName;

    public function id()
    {
        return $this->_idRank;
    }

    public function name()
    {
        return $this->_rankName;
    }

    private function hydrate(array $data)
    {
        if (isset($data['idrang']))
            $this->setId($data['idrang']);
        if (isset($data['nomrang']))
            $this->setName(['idrang']);
    }

    public function setId($id)
    {
        $id = (int)$id;
        $this->_idRank = $id;
    }

    public function setName($name)
    {
        if (is_string($name))
            $this->_rankName = $name;
    }

    //Get a rank from db with its name
    public static function loadFromName($name)
    {
        $rank = new self();
        global $config;
        $db = connectToDb($config);
        $query = $db->query("SELECT idrang, nomrang FROM rang WHERE nomrang='" . $name . "'");
        $data = $query->fetch(PDO::FETCH_ASSOC);
        if (is_bool($data)) {
            $query->closeCursor();
            throw new Exception("Erreur lors de la requête sur la table rang.");
        } else
            $rank->hydrate($data);

        $query->closeCursor();

        return $rank;
    }

    //Get a rank from db with its name
    public static function loadFromId($id)
    {
        $rank = new self();
        global $config;
        $db = connectToDb($config);
        $query = $db->query("SELECT idrang, nomrang FROM rang WHERE idrang=" . $id);
        $data = $query->fetch(PDO::FETCH_ASSOC);
        if (is_bool($data)) {
            $query->closeCursor();
            throw new Exception("Erreur lors de la requête sur la table rang.");
        } else
            $rank->hydrate($data);
        $query->closeCursor();

        return $rank;
    }
}