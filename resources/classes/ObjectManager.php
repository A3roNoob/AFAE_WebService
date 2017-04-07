<?php

/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 10:19
 */
require_once(dirname(__FILE__) . "/../config.php");
require_once('User.php');

class ObjectManager
{
    private $_objets;

    public function objets()
    {
        return $this->_objets;
    }

    public function setObjets(array $obj)
    {
        if (is_array($obj))
            $this->_objets = $obj;
    }

    /*
     * Return : Object array
     *
     *
     */
    public function loadObjectsFromUser($user)
    {
        $db = connectToDb();
        $query = $db->prepare('SELECT * FROM objet WHERE idutilisateur=:iduser');
        $query->bindValue(':iduser', $user->id(), PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $query->closeCursor();

        if (is_bool($data))
            return false;

        global $arrObject;
        $arrObject = array();
        foreach ($data as $value) {
            $obj = new Object();
            $obj->hydrate($value);
            array_push($arrObject, $obj);
        }
        $this->setObjets($arrObject);
    }

    public function loadObjectsFromUserFoire($user, $foire)
    {
        $db = connectToDb();
        $query = $db->prepare('SELECT * FROM objet WHERE idutilisateur=:iduser AND idfoire=:idfoire');
        $query->bindValue(':iduser', $user->id(), PDO::PARAM_INT);
        $query->bindValue(':idfoire', $foire->idFoire(), PDO::PARAM_INT);

        try {
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        if (is_bool($data))
            throw new Exception("Erreur lors de la selection sur la table objet pour l'utilisateur num $user->id() <br> Function Name: loadObjectsFromUser");
        $query->closeCursor();

        $arrObject = array();
        foreach ($data as $value) {
            $obj = new Object();
            $obj->hydrate($value);
            array_push($arrObject, $obj);
        }

        $this->setObjets($arrObject);
    }

    public function getLastItem()
    {
        if (is_array($this->_objets) && !is_bool(end($this->_objets)))
            return end($this->_objets)->numItem();
    }

    public function deleteObject($object)
    {
        $db = connectToDb();
        $obj = Object::loadObjectFromId($object->idObjet());
        $query = $db->query("SELECT verrou FROM objet WHERE idobjet=" . $object->idObjet());
        $query = $query->fetch(PDO::FETCH_ASSOC);

        if (isset($query['verrou']) && !((bool)$query['verrou'])) {
            $query = $db->prepare("DELETE FROM objet WHERE idobjet=" . $object->idObjet());
            $query->execute();
            $obj->goUpInTable($db);
            $query->closeCursor();
        } else {
            throw new Exception("Objet verrouillé, impossible de le supprimer");
        }
    }
}