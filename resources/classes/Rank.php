<?php
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
            $this->setName($data['nomrang']);
    }

    public function setId($id)
    {
        $id = (int)$id;
        $this->_idRank = $id;
    }

    public function setName($name)
    {
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

    public static function getAllRanks(){
        $rankList = array();
        $db = connectToDb();
        $query = $db->prepare("SELECT * FROM rang");
        try{
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
        foreach ($data as $value) {
            $rang = new Rank();
            $rang->hydrate($value);
            array_push($rankList, $rang);
        }

        $query->closeCursor();
        return $rankList;
    }
}