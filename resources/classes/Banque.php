<?php
class Banque
{
    private $_idBanque;
    private $_nomBanque;
    private $_codeBanque;

    public function idBanque()
    {
        return $this->_idBanque;
    }

    public function nomBanque()
    {
        return $this->_nomBanque;
    }

    public function codeBanque()
    {
        return $this->_codeBanque;
    }

    public function hydrate(array $data)
    {
        if (isset($data['idbanque']))
            $this->setId($data['idbanque']);
        if (isset($data['nombanque']))
            $this->setNomBanque($data['nombanque']);
        if (isset($data['codebanque']))
            $this->setCodeBanque($data['codebanque']);
    }

    public function setId($id)
    {
        $id = (int)$id;
        $this->_idBanque = $id;
    }

    public function setNomBanque($ban)
    {
        if (is_string($ban))
            $this->_nomBanque = $ban;
    }

    public function setCodeBanque($cb)
    {
        if (is_string($cb))
            $this->_codeBanque = $cb;
    }

    public static function loadFromBd($id)
    {
        $db = connectToDb();
        $query = $db->prepare("SELECT * FROM banque WHERE idbanque=:id");
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        try{
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
        $query->closeCursor();
        $obj = new self();
        $obj->hydrate($data);
        return $obj;
    }

    public static function getAllBanque(){
        $banqueList = array();
        $db = connectToDb();
        $query = $db->prepare("SELECT * FROM banque");
        try{
            $query->execute();
            $data = $query->fetchAll();
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }

        foreach ($data as $value) {
            $banque = new Banque();
            $banque->hydrate($value);
            array_push($banqueList, $banque);
        }

        $query->closeCursor();
        return $banqueList;
    }

}