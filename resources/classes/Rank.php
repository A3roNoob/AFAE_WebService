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
        $db = connectToDb();

        $query = $db->prepare("SELECT idrang, nomrang FROM rang WHERE nomrang=:nom");
        $query->bindValue(':nom', $name);
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $rank = new self();
        $rank->hydrate($data);
        $query->closeCursor();

        return $rank;
    }

    //Get a rank from db with its name
    public static function loadFromId($id)
    {

        $db = connectToDb();
        $query = $db->prepare("SELECT idrang, nomrang FROM rang WHERE idrang=:id");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $rank = new self();
        $rank->hydrate($data);
        $query->closeCursor();

        return $rank;
    }
}