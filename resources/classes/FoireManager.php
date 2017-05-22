<?php

/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 05/04/2017
 * Time: 11:59
 */
class FoireManager
{
    private $_foires;


    public function foires()
    {
        return $this->_foires;
    }

    public function setFoires(array $foires)
    {
        if (is_array($foires)) $this->_foires = $foires;
    }

    public function loadFoiresFromDb()
    {
        $foireList = array();
        $db = connectToDb();
        $query = $db->prepare("SELECT * FROM foire");
        try{
            $query->execute();
            $data = $query->fetchAll();
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }

        foreach ($data as $value) {
            $foire = new Foire();
            $foire->hydrate($value);
            array_push($foireList, $foire);
        }
        $this->setFoires($foireList);
        $query->closeCursor();
    }

    public function getLastItem(){
        if (is_array($this->_foires))
            return end($this->_foires)->idFoire();
    }

    public function loadFoiresFromFoireAdmin($user)
    {
        if(is_a($user, "User"))
        {
            $foireList = array();
            $db = connectToDb();
            $query = $db->prepare("SELECT * FROM foire WHERE idadmin=:id");
            $query->bindValue(':id', $user->id(), PDO::PARAM_INT);
            try{
                $query->execute();
                $data = $query->fetchAll();
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }

            foreach ($data as $value) {
                $foire = new Foire();
                $foire->hydrate($value);
                array_push($foireList, $foire);
            }
            $this->setFoires($foireList);
            $query->closeCursor();
        }
    }

    public function loadFromDbParticipant($idpart)
    {
        $foireList = array();
        $db = connectToDb();
        $query = $db->prepare("SELECT F.idfoire, F.nomfoire, F.idassociation, F.idadmin, F.datedebutfoire, F.datefinfoire, F.datedebutsaisie, F.datefinsaisie FROM foire F, participant P WHERE F.idfoire=P.idfoire AND P.idutilisateur=:iduser && P.valide=TRUE");
        $query->bindValue(':iduser', $idpart, PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetchAll();
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }

        foreach ($data as $value) {
            $foire = new Foire();
            $foire->hydrate($value);
            array_push($foireList, $foire);
        }
        $this->setFoires($foireList);
        $query->closeCursor();
    }

    public function loadFromDbNonParticipant($idpart)
    {
        $foireList = array();
        $db = connectToDb();
        $query = $db->prepare("SELECT DISTINCT F.idfoire, F.nomfoire, F.idassociation, F.idadmin, F.datedebutfoire, F.datefinfoire, F.datedebutsaisie, F.datefinsaisie FROM foire F, participant P WHERE F.idfoire=P.idfoire AND :iduser NOT IN (SELECT idutilisateur FROM participant H WHERE H.idfoire=F.idfoire)");
        $query->bindValue(':iduser', $idpart, PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
        foreach ($data as $value) {
            $foire = new Foire();
            $foire->hydrate($value);
            array_push($foireList, $foire);
        }
        $this->setFoires($foireList);
        $query->closeCursor();
    }
}