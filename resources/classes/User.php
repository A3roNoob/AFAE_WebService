<?php

/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 02/04/2017
 * Time: 23:26
 */
require_once(dirname(__FILE__) . "/../config.php");
require_once(dirname(__FILE__) . "/../functions.php");

//TODO gestion des opérateurs

class User
{
    private $_idUser;
    private $_name;
    private $_firstName;
    private $_address;
    private $_codePostal;
    private $_city;
    private $_phone;
    private $_drop;
    private $_rank;
    private $_email;
    private $_login;
    private $_password;

    public function hydrate(array $data)
    {
        if (isset($data['idutilisateur']))
            $this->setId($data['idutilisateur']);
        if (isset($data['nomutilisateur']))
            $this->setName($data['nomutilisateur']);
        if (isset($data['prenomutilisateur']))
            $this->setFName($data['prenomutilisateur']);
        if (isset($data['adresse']))
            $this->setAddress($data['adresse']);
        if (isset($data['codepostal']))
            $this->setCodePostal($data['codepostal']);
        if (isset($data['ville']))
            $this->setCity($data['ville']);
        if (isset($data['telephone']))
            $this->setPhone($data['telephone']);
        if (isset($data['baisse']))
            $this->setDrop($data['baisse']);
        if (isset($data['rang']))
            $this->setRank($data['rang']);
        if (isset($data['email']))
            $this->setEmail($data['email']);
        if (isset($data['motdepasse']))
            $this->_password = $data['motdepasse'];
        if(isset($date['login']))
            $this->setLogin($data['login']);
    }

    public static function loadFromBd($login, $password)
    {
        $db = connectTodb();
        $query = $db->prepare("SELECT idutilisateur, nomutilisateur, prenomutilisateur, adresse, codepostal, email, ville, telephone, baisse, motdepasse, rang FROM utilisateur WHERE login=:login");
        $query->bindValue(':login', $login);
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $user = new self();
        $user->setLogin($login);
        if(md5($password)==$data['motdepasse'])
            $user->hydrate($data);
        $query->closeCursor();

        return $user;
    }

    public static function loadUserWithId($id)
    {
        $db = connectTodb();
        $query = $db->prepare("SELECT idutilisateur, nomutilisateur, prenomutilisateur, adresse, codepostal, ville, telephone, baisse, motdepasse, rang FROM utilisateur WHERE idutilisateur=:id");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $query->closeCursor();
        $user = new self();
        $user->hydrate($data);
        return $user;
    }

    public function insertIntoDb($login, $password)
    {
        if (is_a($this, 'User')) {
            $db = connectToDb();
            $query = $db->prepare("INSERT INTO utilisateur(nomutilisateur, prenomutilisateur, adresse, codepostal, ville, telephone, baisse, login, motdepasse, email, rang) VALUES (:nameuser, :fname, :address, :cp, :city, :phone, :baisse, :login, :password, :email, :rank)");
            $query->bindValue(':nameuser', $this->name());
            $query->bindValue(':fname', $this->fname());
            $query->bindValue(':address', $this->address());
            $query->bindValue(':cp', $this->codePostal(), PDO::PARAM_INT);
            $query->bindValue(':city', $this->city());
            $query->bindValue(':phone', $this->phone(), PDO::PARAM_INT);
            $query->bindValue(':baisse', $this->drop(), PDO::PARAM_BOOL);
            $query->bindValue(':login', $login);
            $query->bindValue(':password', md5($password));
            $query->bindValue(':email', $this->email());
            $query->bindValue(':rank', $this->rank()->id(), PDO::PARAM_INT);
            $query->execute();
            if ($db->errorCode() > 0)
                throw new Exception($db->errorCode());
        }

    }

    public static function createUser($name, $fname, $address, $cp, $city, $phone, $drop, $rank, $email)
    {
        $user = new self();
        $array = array(
            "idutilisateur" => 0,
            "nomutilisateur" => $name,
            "prenomutilisateur" => $fname,
            "adresse" => $address,
            "codepostal" => $cp,
            "ville" => $city,
            "telephone" => $phone,
            "baisse" => $drop,
            "rang" => $rank,
            "email" => $email
        );
        $user->hydrate($array);
        return $user;
    }

    public function checkUser()
    {
        $db = connectToDb();
        $query = $db->prepare('SELECT idutilisateur, nomutilisateur, email, adresse, motdepasse FROM utilisateur WHERE idutilisateur=:id AND motdepasse=:pwd');
        $query->bindValue(':id', $this->id(), PDO::PARAM_INT);
        $query->bindValue(':pwd', $this->_password);
        $data = null;
        try {
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        if (is_bool($data) || $query->rowCount() == 0) {
            session_destroy();
        }
        $query->closeCursor();

    }

    public function checkRank($rank)
    {
        if (is_a($rank, "Rank"))
            return ($this->rank()->id() >= $rank->id());
    }

    public function updatePassword($password){
        $db = connectToDb();
        $query = $db->prepare('UPDATE utilisateur SET motdepasse=:pwd WHERE login=:login;');
        $query->bindValue(':login', $this->login());
        $query->bindValue(':pwd', md5($password));
        try{
            $query->execute();
        }catch(PDOException $e){
            $query->closeCursor();
            return false;
        }
        $query->closeCursor();
        return true;
    }

    public function updateUser($name, $fName, $address, $cp, $city, $phone, $email, $baisse){
        $db = connectToDb();
        $query = $db->prepare('UPDATE utilisateur SET nomutilisateur=:name, prenomutilisateur=:fname, adresse=:address, codepostal=:cp, ville=:city, telephone=:phone, email=:email, baisse=:baisse WHERE login=:login;');
        $query->bindValue(':login', $this->login());
        $query->bindValue(':name', $name);
        $query->bindValue(':fname', $fName);
        $query->bindValue(':address', $address);
        $query->bindValue(':cp', $cp);
        $query->bindValue(':city', $city);
        $query->bindValue(':phone', $phone);
        $query->bindValue(':email', $email);
        $query->bindValue(':baisse', $baisse, PDO::PARAM_BOOL);

        $this->setName($name);
        $this->setFName($fName);
        $this->setAddress($address);
        $this->setCodePostal($cp);
        $this->setCity($city);
        $this->setPhone($phone);
        $this->setEmail($email);
        $this->setDrop($baisse);

        try{
            $query->execute();
        }catch(PDOException $e){
            $query->closeCursor();
            return false;
        }
        $query->closeCursor();
        return true;
    }

    public function id()
    {
        return $this->_idUser;
    }

    public function name()
    {
        return $this->_name;
    }

    public function fname()
    {
        return $this->_firstName;
    }

    public function address()
    {
        return $this->_address;
    }

    public function codePostal()
    {
        return $this->_codePostal;
    }

    public function city()
    {
        return $this->_city;
    }

    public function phone()
    {
        return $this->_phone;
    }

    public function drop()
    {
        return $this->_drop;
    }

    public function login(){
        return $this->_login;
    }

    public function rank()
    {
        if (is_int($this->_rank))
            return Rank::loadFromId($this->_rank);
        else
            return $this->_rank;
    }

    public function email()
    {
        return $this->_email;
    }

    public function setId($id)
    {
        $id = (int)$id;
        $this->_idUser = $id;
    }

    public function setName($name)
    {
        if (is_string($name))
            $this->_name = $name;
    }

    public function setFName($fname)
    {
        if (is_string($fname))
            $this->_firstName = $fname;
    }

    public function setAddress($address)
    {
        if (is_string($address))
            $this->_address = $address;
    }

    public function setCodePostal($cp)
    {
        $cp = (int)$cp;
        $this->_codePostal = $cp;
    }

    public function setCity($city)
    {
        if (is_string($city))
            $this->_city = $city;
    }

    public function setPhone($phone)
    {
        $this->_phone = $phone;
    }

    public function setDrop($dp)
    {
        $dp = (bool)$dp;
        $this->_drop = $dp;
    }

    public function setRank($rank)
    {
        $rank = (int)$rank;
        $rank = Rank::loadFromId($rank);

        $this->_rank = $rank;
    }

    public function setLogin($login){
        $this->_login = $login;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function setPassword($pass){
        $this->_password = $pass;
    }
}