<?php

/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 04/04/2017
 * Time: 00:44
 */
class Association
{
    private $_idAssoc;
    private $_nomAssoc;
    private $_sigle;
    private $_ville;
    private $_idAdmin;

    public function idAssoc()
    {
        return $this->_idAssoc;
    }

    public function nomAssoc()
    {
        return $this->_nomAssoc;
    }

    public function sigle()
    {
        return $this->_sigle;
    }

    public function ville()
    {
        return $this->_ville;
    }

    public function idAdmin()
    {
        return $this->_idAdmin;
    }

    public function setIdAssoc($idAssoc)
    {
        $idAssoc = (int)$idAssoc;
        $this->_idAssoc = $idAssoc;
    }

    public function setNomAssoc($nom)
    {
        if (is_string($nom))
            $this->_nomAssoc = $nom;
    }

    public function setSigle($s)
    {
        if (is_string($s))
            $this->_sigle = $s;
    }

    public function setVille($v)
    {
        if (is_string($v))
            $this->_ville = $v;
    }

    public function setIdAdmin($idAdmin)
    {
        $idAdmin = (int)$idAdmin;
        $this->_idAdmin = $idAdmin;
    }

    public function hydrate(array $data)
    {
        if (isset($data['idassociation']))
            $this->setIdAssoc($data['idassociation']);
        if (isset($data['nomassociation']))
            $this->setNomAssoc($data['nomassociation']);
        if (isset($data['sigleassociation']))
            $this->setSigle($data['sigleassociation']);
        if (isset($data['villeassociation']))
            $this->setVille($data['villeassociation']);
        if (isset($data['idadministrateur']))
            $this->setIdAdmin($data['idadministrateur']);
    }

    public static function loadFromDb($id)
    {
        $db = connectToDb();
        $query = $db->prepare("SELECT * FROM association WHERE idassociation=:id");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $query->closeCursor();
        $obj = new self();
        $obj->hydrate($data);
        return $obj;
    }

    public static function loadFromAdmin($idAdmin)
    {
        $db = connectToDb();
        $query = $db->prepare("SELECT * FROM association WHERE idadministrateur=:id");
        $query->bindValue(':id', $idAdmin, PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        if(is_bool($data))
            return false;

        $obj = new self();
        $obj->hydrate($data);
        $query->closeCursor();

        return $obj;
    }
    public function insertIntoDb()
    {
        $db = connectToDb();
        $query = $db->prepare("INSERT INTO association(nomassociation, idassociation, sigleassociation, villeassociation, idadministrateur) VALUES (:nomassoc, :idassoc, :sigle, :ville, :idadmin)");
        $query->bindValue(':nomassoc', $this->nomAssoc());
        $query->bindValue(':idassoc', $this->idAssoc(), PDO::PARAM_INT);
        $query->bindValue(':sigle', $this->sigle());
        $query->bindValue(':ville', $this->ville());
        $query->bindValue(':idadmin', $this->idAdmin(), PDO::PARAM_INT);
        try {
            $query->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $query->closeCursor();
    }
}