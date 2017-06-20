<?php
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
        $query->bindValue(':idfoire', $foire, PDO::PARAM_INT);

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

    /**
     * @param $foire
     * @throws Exception
     */
    public function loadObjectsFromFoire($foire)
    {
        $db = connectToDb();
        $query = $db->prepare('SELECT * FROM objet WHERE idfoire=:idfoire');
        $query->bindValue(':idfoire', $foire, PDO::PARAM_INT);

        $data = null;
        try {
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        if (is_bool($data))
            throw new Exception("Erreur lors de la selection sur la table objet pour la foire $foire <br> Function Name: loadObjectsFromFoire");
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

    public static function deleteItem($iduser, $idfoire, $numItem)
    {
        $db = connectToDb();

        $query = $db->prepare("SELECT idobjet FROM objet WHERE idutilisateur=:iduser AND idfoire=:idfoire AND numitem=:numitem");
        $query->bindValue(':iduser', $iduser, PDO::PARAM_INT);
        $query->bindValue(':idfoire', $idfoire, PDO::PARAM_INT);
        $query->bindValue(':numitem', $numItem, PDO::PARAM_INT);
        $data = null;
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
            ObjectManager::deleteObject($data['idobjet']);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public static function deleteObject($idObject)
    {
        $db = connectToDb();
        $obj = Object::loadObjectFromId($idObject);
        $query = $db->prepare("SELECT verrou FROM objet WHERE idobjet=:idobjet;");
        $query->bindValue(':idobjet', $idObject, PDO::PARAM_INT);
        try {
            $query->execute();
            $query = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
        if (isset($query['verrou']) && !((bool)$query['verrou'])) {
            $query = $db->prepare("DELETE FROM objet WHERE idobjet=:idobj");
            $query->bindValue(':idobj', $idObject, PDO::PARAM_INT);
            try {
                $query->execute();
                $obj->goUpInTable($db);
                $query->closeCursor();
            } Catch (PDOException $e) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    public function searchObjects($desc){
        if(!empty($desc)) {
            $desc = strtoupper($desc);
            $obj = array();
            foreach ($this->objets() as $objet) {
                if (strpos(strtoupper($objet->desc()), $desc) || strtoupper($objet->desc()) == $desc)
                    array_push($obj, $objet);
            }
            $this->setObjets($obj);
        }
    }
}