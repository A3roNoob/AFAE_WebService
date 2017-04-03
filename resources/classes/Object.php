<?php

/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 10:18
 */
require_once('User.php');

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

    public static function createObject($user, $idfoire, $desc, $baisse, $prix, $vendu, $taille, $nbItems)
    {
        $obj = new self();
        $obj->setUtilisateur($user);
        $obj->createNumItem();
        $obj->setIdFoire($idfoire);
        $obj->setDesc($desc);
        $obj->setBaisse($baisse);
        $obj->setPrix($prix);
        $obj->setVendu($vendu);
        $obj->setTaille($taille);
        $obj->setNbItems($nbItems);
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
        return $this->_vendu;
    }

    public function taille()
    {
        return $this->_taille;
    }

    public function nbItems()
    {
        return $this->_nbItems;
    }

    public function hydrate(array $data)
    {
        if (isset($data['idobjet']))
            $this->setIdObjet($data['idobjet']);
        if (isset($data['numitem']))
            $this->setNumItem($data['numitem']);
        if (isset($data['idfoire']))
            $this->setIdFoire($data['idfoire']);
        if (isset($data['idutilisateur']))
            $this->setUtilisateur($data['idutilisateur']);
        if (isset($data['description']))
            $this->setDesc($data['description']);
        if (isset($data['baisse']))
            $this->setBaisse($data['baisse']);
        if (isset($data['prix']))
            $this->setPrix($data['prix']);
        if (isset($data['vendu']))
            $this->setVendu(['vendu']);
        if (isset($data['taille']))
            $this->setTaille($data['taille']);
        if (isset($data['nbitem']))
            $this->setNbItems($data['nbitem']);
    }

    public function setIdObjet($idObjet)
    {
        $idObjet = (int)$idObjet;
        $this->_idObjet = $idObjet;
    }

    public function setUtilisateur($user)
    {
        $user = (int)$user;
        $this->_utilisateur = User::loadUserWithId($user);
    }

    public function setDesc($desc)
    {
        if (is_string($desc))
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
        if (is_string($t))
            $this->_taille = $t;
    }

    public function setIdFoire($i)
    {
        $i = (int)$i;
        $this->_idFoire = $i;
    }

    public function setNbItems($nbitems)
    {
        $nbitems = (int)$nbitems;
        $this->_nbItems = $nbitems;
    }

    public function setNumItem($num)
    {
        $num = (int) $num;
        $this->_numItem = $num;
    }

    public function createNumItem()
    {
        $objMan = new ObjectManager($this->user());

        $this->_numItem = $objMan->getLastItem() + 1;
    }

    public static function loadObjectFromId($idObjet)
    {

        $db = connectToDb();
        $query = $db->query("SELECT idobjet, numitem, idfoire, idutilisateur, description, baisse, prix, vendu, taille, nbitem FROM objet WHERE idobjet=" . $idObjet);
        $data = $query->fetch(PDO::FETCH_ASSOC);
        if (is_bool($data))
            throw new Exception("Erreur lors de l'execution de la requ&egrave;te sur la table objet. <br> Function name : loadObjectFromId");

        $objet = new self();
        $objet->hydrate($data);
        return $objet;

    }

    public function deleteObject()
    {
        $db = connectToDb();
        $obj = Object::loadObjectFromId($this->idObjet());
        $query = $db->prepare("DELETE FROM objet WHERE idobjet=" . $this->idObjet());
        $query->execute();
        $obj->goUpInTable($db);
        $query->closeCursor();
    }

    public function goUpInTable($db)
    {
        try {
            do {
                $query = $db->prepare("UPDATE objet SET numitem=:numitem WHERE numitem=:numitemnext AND idutilisateur=:iduser");
                $query->bindValue(':numitem', $this->_numItem++, PDO::PARAM_INT);
                $query->bindValue(':numitemnext', $this->_numItem, PDO::PARAM_INT);
                $query->bindValue(':iduser', $this->user()->id(), PDO::PARAM_INT);
                $query->execute();

            } while ($query->rowCount() > 0);
        } catch (PDOException $e) {

            echo "<br>" . $e->getMessage();
        }
    }


    public function insertObjectIntoDb()
    {

        $db = connectToDb();
        $query = $db->prepare('INSERT INTO objet(idutilisateur, numitem, idfoire, description, baisse, prix, vendu, taille) VALUES (:iduser, :numitem, :idfoire, :descr, :baisse, :prix, :vendu, :taille)');
        $query->bindValue(':iduser', $this->user()->id(), PDO::PARAM_INT);
        $query->bindValue(':numitem', $this->numItem(), PDO::PARAM_INT);
        $query->bindValue(':idfoire', $this->idFoire(), PDO::PARAM_INT);
        $query->bindValue(':descr', $this->desc());
        $query->bindValue(':baisse', $this->baisse(), PDO::PARAM_BOOL);
        $query->bindValue(':prix', $this->prix());
        $query->bindValue(':vendu', $this->vendu(), PDO::PARAM_BOOL);
        $query->bindValue(':taille', $this->taille());
        $query->execute();

    }
}