<?php
///TODO transactions
require_once(dirname(__FILE__) . "/../config.php");

class Object
{
    private $_idObjet;
    private $_numItem;
    private $_idFoire;
    private $_utilisateur;
    private $_description;
    private $_baisse;
    private $_prix;
    private $_vendu;
    private $_taille;
    private $_nbItems;
    private $_verrou;

    public static function createObject($user, $idfoire, $desc, $baisse, $prix, $vendu, $taille, $nbItems, $verrou)
    {
        $obj = new self();
        $obj->setUtilisateur($user);
        $obj->setIdFoire($idfoire);
        $obj->createNumItem();
        $obj->setDesc($desc);
        $obj->setBaisse($baisse);
        $obj->setPrix($prix);
        $obj->setVendu($vendu);
        $obj->setTaille($taille);
        $obj->setNbItems($nbItems);
        $obj->setVerrou($verrou);
        return $obj;
    }

    public function idObjet()
    {
        return $this->_idObjet;
    }

    public function numItem()
    {
        return $this->_numItem;
    }

    public function idFoire()
    {
        return $this->_idFoire;
    }

    public function user()
    {
        return $this->_utilisateur;
    }

    public function desc()
    {
        return $this->_description;
    }

    public function baisse()
    {
        return $this->_baisse;
    }

    public function prix()
    {
        return $this->_prix;
    }

    public function vendu()
    {
        return (bool)$this->_vendu;
    }

    public function taille()
    {
        return $this->_taille;
    }

    public function nbItems()
    {
        return $this->_nbItems;
    }

    public function verrou()
    {
        return $this->_verrou;
    }

    public function hydrate(array $data)
    {
        if (isset($data['idobjet']))
            $this->setIdObjet($data['idobjet']);
        if (isset($data['idfoire']))
            $this->setIdFoire($data['idfoire']);
        if (isset($data['numitem']))
            $this->setNumItem($data['numitem']);
        if (isset($data['idutilisateur']))
            $this->setUtilisateur($data['idutilisateur']);
        if (isset($data['description']))
            $this->setDesc($data['description']);
        if (isset($data['baisse']))
            $this->setBaisse($data['baisse']);
        if (isset($data['prix']))
            $this->setPrix($data['prix']);
        if (isset($data['vendu']))
            $this->setVendu($data['vendu']);
        if (isset($data['taille']))
            $this->setTaille($data['taille']);
        if (isset($data['nbitem']))
            $this->setNbItems($data['nbitem']);
        if (isset($data['verrou']))
            $this->setVerrou($data['verrou']);
    }

    public function setIdObjet($idObjet)
    {
        $idObjet = (int)$idObjet;
        $this->_idObjet = $idObjet;
    }

    public function setUtilisateur($user)
    {
        if (is_a($user, "User"))
            $this->_utilisateur = $user;
        else {
            $user = (int)$user;
            $this->_utilisateur = User::loadUserWithId($user);
        }
    }

    public function setDesc($desc)
    {
        $this->_description = $desc;
    }

    public function setBaisse($b)
    {
        $b = (bool)$b;
        $this->_baisse = $b;
    }

    public function setPrix($p)
    {
        $p = (double)$p;
        $this->_prix = $p;
    }

    public function setVendu($v)
    {
        $v = (bool)$v;
        $this->_vendu = $v;
    }

    public function setTaille($t)
    {
        $this->_taille = $t;
    }

    public function setIdFoire($i)
    {
        $i = (int)$i;
        $this->_idFoire = $i;
    }

    public function setNbItems($nbitems)
    {
        $this->_nbItems = (int)$nbitems;
    }

    public function setNumItem($num)
    {
        $this->_numItem = (int)$num;
    }

    public function createNumItem()
    {
        $objMan = new ObjectManager();
        $objMan->loadObjectsFromUserFoire($this->user(), $this->idFoire());
        $this->_numItem = $objMan->getLastItem() + 1;
    }

    public function setVerrou($v)
    {
        $this->_verrou = (bool)$v;
    }

    public static function loadObjectFromId($idObjet)
    {

        $db = connectToDb();
        $query = $db->prepare("SELECT idobjet, numitem, idfoire, idutilisateur, description, baisse, prix, vendu, taille, nbitem, verrou FROM objet WHERE idobjet=:idobj");
        $query->bindValue(':idobj', $idObjet, PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $query->closeCursor();

        $objet = new self();
        $objet->hydrate($data);
        return $objet;

    }

    public static function loadObjectFromFoire($numItem, $idUser, $idFoire)
    {

        $db = connectToDb();
        $query = $db->prepare("SELECT idobjet, numitem, idfoire, idutilisateur, description, baisse, prix, vendu, taille, nbitem, verrou FROM objet WHERE numitem=:numitem AND idfoire=:idfoire AND idutilisateur=:iduser;");
        $query->bindValue(':numitem', $numItem, PDO::PARAM_INT);
        $query->bindValue(':iduser', $idUser, PDO::PARAM_INT);
        $query->bindValue(':idfoire', $idFoire, PDO::PARAM_INT);

        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
        $query->closeCursor();

        $objet = new self();
        $objet->hydrate($data);
        return $objet;

    }

    public function updateObject($desc, $baisse, $prix, $taille, $nbItem)
    {
        $db = connectToDb();
        $query = $db->prepare("UPDATE objet SET description=:descr, baisse=:baisse, prix=:prix, taille=:taille, nbitem=:nbitem WHERE idutilisateur=:iduser AND idfoire=:idfoire AND numitem=:numitem;");
        $query->bindValue(':iduser', $this->user()->id(), PDO::PARAM_INT);
        $query->bindValue(':idfoire', $this->idFoire(), PDO::PARAM_INT);
        $query->bindValue(':numitem', $this->numItem(), PDO::PARAM_INT);
        $query->bindValue(':descr', $desc);
        $query->bindValue(':baisse', $baisse, PDO::PARAM_BOOL);
        $query->bindValue(':prix', $prix);
        $query->bindValue(':taille', $taille);
        $query->bindValue(':nbitem', $nbItem, PDO::PARAM_INT);

        $this->setDesc($desc);
        $this->setBaisse($baisse);
        $this->setPrix($prix);
        $this->setTaille($taille);
        $this->setNbItems($nbItem);

        try {
            $query->execute();
        } catch (PDOException $e) {
            return false;
        }

        return true;
    }

    public function goUpInTable($db)
    {
        if ($this->numItem() != 1) {
            try {
                do {
                    $query = $db->prepare("UPDATE objet SET numitem=:numitem WHERE numitem=:numitemnext AND idutilisateur=:iduser");
                    $query->bindValue(':numitem', $this->_numItem++, PDO::PARAM_INT);
                    $query->bindValue(':numitemnext', $this->_numItem, PDO::PARAM_INT);
                    $query->bindValue(':iduser', $this->user()->id(), PDO::PARAM_INT);
                    $query->execute();

                } while ($query->rowCount() > 0);
            } catch (PDOException $e) {

                return false;
            }
        }

        return true;

    }


    public function insertObjectIntoDb()
    {
        $db = connectToDb();

        $query = $db->prepare("SELECT COUNT(numitem) AS NbItems FROM objet WHERE idfoire=:idfoire AND idutilisateur=:iduser");
        $query->bindValue(':idfoire', $this->idFoire(), PDO::PARAM_INT);
        $query->bindValue(':iduser', $this->user()->id(), PDO::PARAM_INT);
        $data = null;
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e->getMessage();
        }

        $assoc = Association::loadFromAdmin($this->user()->id());
        $foire = Foire::loadFromDb($this->idFoire());
        if (!is_bool($assoc))
            $max = $foire->maxObjAssoc();
        else
            $max = $foire->maxObj();

        if ((int)$data['NbItems'] < $max) {

            $query = $db->prepare('INSERT INTO objet(idutilisateur, numitem, idfoire, description, baisse, prix, nbitem, vendu, taille, verrou) VALUES (:iduser, :numitem, :idfoire, :descr, :baisse, :prix, :nbitem, :vendu, :taille, :verrou)');
            $query->bindValue(':iduser', $this->user()->id(), PDO::PARAM_INT);
            $query->bindValue(':numitem', $this->numItem(), PDO::PARAM_INT);
            $query->bindValue(':idfoire', $this->idFoire(), PDO::PARAM_INT);
            $query->bindValue(':descr', $this->desc());
            $query->bindValue(':baisse', $this->baisse(), PDO::PARAM_BOOL);
            $query->bindValue(':prix', $this->prix());
            $query->bindValue(':nbitem', $this->nbItems(), PDO::PARAM_INT);
            $query->bindValue(':vendu', $this->vendu(), PDO::PARAM_BOOL);
            $query->bindValue(':taille', $this->taille());
            $query->bindValue(':verrou', $this->verrou());
            try {
                $query->execute();
            } catch (PDOException $e) {
                return $e->getMessage();
            }
        } else {
            return '<div class="alert alert-warning">' . $this . ' non ins&eacute;r&eacute;. Vous avez atteint le quota pour cette foire.';
        }
        return true;
    }

    public function __toString()
    {
        return "Objet&nbsp;:&nbsp;" . $this->desc() . " Prix&nbsp;:&nbsp;" . $this->prix();
    }

    public static function appartient($numItem, $idUser, $idFoire)
    {
        $db = connectToDb();
        $query = $db->prepare("SELECT idutilisateur FROM objet WHERE numitem=:numitem AND idfoire=:idfoire AND idutilisateur=:iduser;");
        $query->bindValue(':numitem', $numItem, PDO::PARAM_INT);
        $query->bindValue(':idfoire', $idFoire, PDO::PARAM_INT);
        $query->bindValue(':iduser', $idUser, PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
        if (is_bool($data))
            return false;
        return true;
    }

    public function vendre(){
        $this->setVendu(true);
        $db = connectToDb();
        $query = $db->prepare("UPDATE objet SET vendu=TRUE WHERE idobjet=:idobjet;");
        $query->bindValue(':idobjet', $this->idObjet(), PDO::PARAM_INT);
        try{
            $query->execute();
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

}