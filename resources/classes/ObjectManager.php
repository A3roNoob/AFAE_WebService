<?php

/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 10:19
 */
require_once(dirname(__FILE__)."/../config.php");
require_once('User.php');
class ObjectManager
{
    private $_objets;

    public function objets(){ return $this->_objets; }

    public function __construct($user)
    {
        if(is_a($user, "User"))
        {
            $this->_objets = $this->loadObjectsFromUser($user);
        }
    }

    public function loadObjectsFromUser($user)
    {
        $db = connectToDb();
        $query = $db->query('SELECT * FROM objet WHERE idutilisateur='.$user->id());
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        if(is_bool($data))
            throw new Exception("Erreur lors de la selection sur la table objet pour l'utilisateur num $user->id() <br> Function Name: loadObjectsFromUser");
        $query->closeCursor();
        return $data;
    }

    public function getLastItem()
    {
        if(is_array($this->_objets))
            return end($this->_objets)['numitem'];
    }
}