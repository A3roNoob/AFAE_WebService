<?php

/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 04/04/2017
 * Time: 22:18
 */
class UserManager
{
    private $_users;

    public function users()
    {
        return $this->_users;
    }

    public function setUsers(array $users)
    {
        $this->_users = $users;
    }

    public function loadUsersFromDb()
    {
        $userList = array();
        $data = array();
        $db = connectToDb();
        $query = $db->prepare("SELECT * FROM utilisateur");
        try {
            $query->execute();
            $data = $query->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        foreach ($data as $value) {
            $user = new User();
            $user->hydrate($value);
            array_push($userList, $user);
        }

        $this->setUsers($userList);
        $query->closeCursor();
    }

    public function loadUsersFromFoire($idFoire)
    {
        $userList = array();
        $data = array();
        $db = connectToDb();
        $query = $db->prepare("SELECT * FROM utilisateur U, participant P WHERE U.idutilisateur=P.idutilisateur AND P.idfoire=:idfoire");
        $query->bindValue(':idfoire', $idFoire, PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        foreach ($data as $value) {
            $user = new User();
            $user->hydrate($value);
            array_push($userList, $user);
        }

        $this->setUsers($userList);
        $query->closeCursor();
    }

    public function loadFoireAdminUser()
    {
        $userList = array();
        $data = array();
        $db = connectToDb();
        $query = $db->prepare("SELECT * FROM utilisateur U, association A WHERE U.idutilisateur=A.idadministrateur");
        try {
            $query->execute();
            $data = $query->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        foreach ($data as $value) {
            $user = new User();
            $user->hydrate($value);
            array_push($userList, $user);
        }

        $this->setUsers($userList);
        $query->closeCursor();
    }

    public function loadSuperAdmins()
    {
        $userList = array();
        $data = array();
        $db = connectToDb();
        $query = $db->prepare("SELECT * FROM utilisateur WHERE rang=:rang");
        $query->bindValue(':rang', Rank::loadFromName("Super Administrateur")->id(), PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        foreach ($data as $value) {
            $user = new User();
            $user->hydrate($value);
            array_push($userList, $user);
        }

        $this->setUsers($userList);
        $query->closeCursor();
    }

    public function deleteUserWID($id)
    {
        $db = connectToDb();
        $query = $db->prepare('DELETE FROM utilisateur WHERE idutilisateur=:id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);

        try {
            $query->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $this->loadUsersFromDb();
        $query->closeCursor();
    }

    public function deleteUser($user)
    {
        if (is_a($user, "User"))
            $this->deleteUserWID($user->id());
    }
}