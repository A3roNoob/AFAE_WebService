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
    private $_dateDebut;
    private $_dateFin;

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

    public function dateDebut()
    {
        return $this->_dateDebut;
    }

    public function dateFin()
    {
        return $this->_dateFin;
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
        $this->_idAdmin = (int) $id;
    }

    public function setDateDebut($date)
    {
        $this->_dateDebut = $date;
    }

    public function setDateFin($date)
    {
        $this->_dateFin = $date;
    }

    public function hydrate(array $data)
    {
        if(!isset($data['result']))
        {
            if (isset($data['idfoire']))
                $this->setIdFoire($data['idfoire']);
            if (isset($data['idassociation']))
                $this->setIdAssoc($data['idassociation']);
            if (isset($data['nomfoire']))
                $this->setNomFoire($data['nomfoire']);
            if(isset($data['idadmin']))
                $this->setIdAdmin($data['idadmin']);
            if(isset($data['datedebut']))
                $this->setDateDebut($data['datedebut']);
            if(isset($data['datefin']))
                $this->setDateFin($data['datefin']);
        }
    }

    public static function createFoire($nomFoire, $idAssoc, $idAdmin, $datedebut, $datefin)
    {
        $obj = new self();
        $obj->setNomFoire($nomFoire);
        $obj->setIdAssoc($idAssoc);
        $obj->setIdAdmin($idAdmin);
        $obj->setDateDebut($datedebut);
        $obj->setDateFin($datefin);
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
        }
        $query->closeCursor();
        if(is_bool($data))
        {
            echo "Cette foire n'existe pas.";
            return null;
        }

            $objFoire = new self();
            $objFoire->hydrate($data);
            return $objFoire;
    }

    public function insertIntoDb()
    {
        $f = new FoireManager();
        $f->loadFoiresFromDb();
        $this->setIdFoire($f->getLastItem() + 1);
        $db = connectToDb();
        $query = $db->prepare("INSERT INTO foire(nomfoire, idfoire, idadmin, idassociation, datedebut, datefin) VALUES (:nomfoire, :idfoire, :idadmin, :idassoc, :dd, :df);");
        $query->bindValue(':nomfoire', $this->nomFoire());
        $query->bindValue(':idfoire', $this->idFoire(), PDO::PARAM_INT);
        $query->bindValue(':idadmin', $this->idAdmin(), PDO::PARAM_INT);
        $query->bindValue(':idassoc', $this->idAssoc(), PDO::PARAM_INT);
        $query->bindValue(':dd', $this->dateDebut());
        $query->bindValue(':df', $this->dateFin());
        try {
            $query->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $query = $db->prepare('INSERT INTO participant(idutilisateur, idfoire, valide) VALUES (:idadmin, :idfoire, TRUE);');
        $query->bindValue(':idadmin', $this->idAdmin(), PDO::PARAM_INT);
        $query->bindValue(':idfoire', $this->idFoire(), PDO::PARAM_INT);
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