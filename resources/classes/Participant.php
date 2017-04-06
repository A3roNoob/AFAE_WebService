<?php

/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 06/04/2017
 * Time: 15:44
 */
class Participant
{
    private $_idFoire;
    private $_idUser;
    private $_valide;

    public function idFoire()
    {
        return $this->_idFoire;
    }

    public function idUser()
    {
        return $this->_idUser;
    }

    public function valide()
    {
        return $this->_valide;
    }

    public function setIdFoire($id)
    {
        $this->_idFoire = (int) $id;
    }

    public function setIdUser($id)
    {
        $this->_idUser = (int) $id;
    }

    public function setValide($b)
    {
        $this->_valide = (bool) $b;
    }

    public static function loadFromDb($idfoire, $iduser)
    {

        $query = connectToDb()->prepare("SELECT * FROM participant WHERE idfoire=:idfoire AND idutilisateur=:iduser");
        $query->bindValue(':idfoire', $idfoire, PDO::PARAM_INT);
        $query->bindValue(':iduser', $iduser, PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e)
        {
            echo $e->getMessage();
        }

        if(is_bool($data))
            return "Vous n'avez pas acc&eacute;s &agrave; cette foire.";
        $part = new self();
        $part->hydrate($data);
        return $part;
    }

    public function hydrate(array $data)
    {
        if(isset($data['idfoire']))
            $this->setIdFoire($data['idfoire']);
        if(isset($data['idutilisateur']))
            $this->setIdUser($data['idutilisateur']);
        if(isset($data['valide']))
            $this->setValide($data['valide']);
    }
}