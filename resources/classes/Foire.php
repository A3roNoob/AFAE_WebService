<?php

/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 21:00
 */
class Foire
{
    private $_idFoire;
    private $_nomFoire;
    private $_idAssociation;
    private $_idAdmin;
    private $_dateDebutSaisie;
    private $_dateFinSaisie;
    private $_dateDebutFoire;
    private $_dateFinFoire;
    private $_prixBaisse;
    private $_maxObj;
    private $_maxObjAssoc;

    public function idFoire()
    {
        return $this->_idFoire;
    }

    public function idAdmin()
    {
        return $this->_idAdmin;
    }

    public function nomFoire()
    {
        return $this->_nomFoire;
    }

    public function idAssoc()
    {
        return $this->_idAssociation;
    }

    public function dateDebutSaisie()
    {
        return $this->_dateDebutSaisie;
    }

    public function dateFinSaisie()
    {
        return $this->_dateFinSaisie;
    }

    public function dateDebutFoire()
    {
        return $this->_dateDebutFoire;
    }

    public function dateFinFoire()
    {
        return $this->_dateFinFoire;
    }

    public function prixBaisse()
    {
        return $this->_prixBaisse;
    }

    public function maxObj(){
        return $this->_maxObj;
    }

    public function maxObjAssoc(){
        return $this->_maxObjAssoc;
    }

    public function setIdFoire($id)
    {
        $id = (int)$id;
        $this->_idFoire = $id;
    }

    public function setIdAssoc($id)
    {
        $id = (int)$id;
        $this->_idAssociation = $id;
    }

    public function setNomFoire($nom)
    {
        if (is_string($nom))
            $this->_nomFoire = $nom;
    }

    public function setIdAdmin($id)
    {
        $this->_idAdmin = (int)$id;
    }

    public function setDateDebutFoire($date)
    {
        $this->_dateDebutFoire = convertDateFromSql($date);
    }

    public function setDateFinFoire($date)
    {
        $this->_dateFinFoire = convertDateFromSql($date);
    }

    public function setDateDebutSaisie($date)
    {
        $this->_dateDebutSaisie = convertDateFromSql($date);
    }

    public function setDateFinSaisie($date)
    {
        $this->_dateFinSaisie = convertDateFromSql($date);
    }

    public function setPrixBaise($prix){
        $this->_prixBaisse = $prix;
    }

    public function setMaxObj($nb){
        $this->_maxObj = $nb;
    }

    public function setMaxObjAssoc($nb){
        $this->_maxObjAssoc = $nb;
    }

    public function hydrate(array $data)
    {
        if (!isset($data['result'])) {
            if (isset($data['idfoire']))
                $this->setIdFoire($data['idfoire']);
            if (isset($data['idassociation']))
                $this->setIdAssoc($data['idassociation']);
            if (isset($data['nomfoire']))
                $this->setNomFoire($data['nomfoire']);
            if (isset($data['idadmin']))
                $this->setIdAdmin($data['idadmin']);
            if (isset($data['datedebutfoire']))
                $this->setDateDebutFoire($data['datedebutfoire']);
            if (isset($data['datefinfoire']))
                $this->setDateFinFoire($data['datefinfoire']);
            if (isset($data['datedebutsaisie']))
                $this->setDateDebutSaisie($data['datedebutsaisie']);
            if (isset($data['datefinsaisie']))
                $this->setDateFinSaisie($data['datefinsaisie']);
            if(isset($data['prixbaisse']))
                $this->setPrixBaise($data['prixbaisse']);
            if(isset($data['maxobj']))
                $this->setMaxObj($data['maxobj']);
            if(isset($data['maxobjassoc']))
                $this->setMaxObjAssoc($data['maxobjassoc']);
        }
    }

    public static function createFoire($nomFoire, $idAssoc, $idAdmin, $dateDebutFoire, $dateFinFoire, $dataDebutSaisie, $dateFinSaisie, $prixBaisse, $maxObj, $maxObjAssoc)
    {
        $obj = new self();
        $obj->setNomFoire($nomFoire);
        $obj->setIdAssoc($idAssoc);
        $obj->setIdAdmin($idAdmin);
        $obj->setDateDebutFoire($dateDebutFoire);
        $obj->setDateFinFoire($dateFinFoire);
        $obj->setDateDebutSaisie($dataDebutSaisie);
        $obj->setDateFinSaisie($dateFinSaisie);
        $obj->setPrixBaise($prixBaisse);
        $obj->setMaxObj($maxObj);
        $obj->setMaxObjAssoc($maxObjAssoc);

        return $obj;
    }

    public static function loadFromDb($id)
    {
        $db = connectToDb();
        $query = $db->prepare("SELECT * FROM foire WHERE idfoire=:idfoire");
        $query->bindValue(':idfoire', $id, PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        } finally {
            $query->closeCursor();
            if (is_bool($data)) {
                return false;
            }

            $objFoire = new self();
            $objFoire->hydrate($data);
            return $objFoire;
        }
    }

    public function insertIntoDb()
    {
        $db = connectToDb();
        $query = $db->prepare("INSERT INTO foire(nomfoire, idadmin, idassociation, datedebutfoire, datefinfoire, datedebutsaisie, datefinsaisie, prixbaisse, maxobj, maxobjassoc) VALUES (:nomfoire, :idadmin, :idassoc, :ddf, :dff, :dds, :dfs, :prix, :maxobj, :maxobjassoc);");
        $query->bindValue(':nomfoire', $this->nomFoire());
        $query->bindValue(':idadmin', $this->idAdmin(), PDO::PARAM_INT);
        $query->bindValue(':idassoc', $this->idAssoc(), PDO::PARAM_INT);
        $query->bindValue(':ddf', $this->dateDebutFoire());
        $query->bindValue(':dff', $this->dateFinFoire());
        $query->bindValue(':dds', $this->dateDebutSaisie());
        $query->bindValue(':dfs', $this->dateFinSaisie());
        $query->bindValue(':prix', $this->prixBaisse());
        $query->bindValue(':maxobj', $this->maxObj(), PDO::PARAM_INT);
        $query->bindValue(':maxobjassoc', $this->maxObjAssoc(), PDO::PARAM_INT);
        try {
            $query->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $query = $db->prepare("SELECT idFoire FROM foire WHERE nomfoire=:nomfoire AND datedebutfoire=:ddf AND datefinfoire=:dff AND idadmin=:idadmin");
        $query->bindValue(':nomfoire', $this->nomFoire());
        $query->bindValue(':idadmin', $this->idAdmin(), PDO::PARAM_INT);
        $query->bindValue(':ddf', $this->dateDebutFoire());
        $query->bindValue(':dff', $this->dateFinFoire());

        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $query = $db->prepare('INSERT INTO participant(idutilisateur, idfoire, valide) VALUES (:idadmin, :idfoire, TRUE);');
        $query->bindValue(':idadmin', $this->idAdmin(), PDO::PARAM_INT);
        $query->bindValue(':idfoire', $data['idFoire'], PDO::PARAM_INT);
        try {
            $query->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $query->closeCursor();
    }

    public function __toString()
    {
        return $this->nomFoire();
    }

}