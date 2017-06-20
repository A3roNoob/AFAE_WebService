<?php
class AssociationManager
{
    private $_assoc;


    public function assoc()
    {
        return $this->_assoc;
    }

    public function setAssoc(array $assoc)
    {
        if (is_array($assoc)) $this->_assoc = $assoc;
    }

    public function loadAssocFromDb()
    {
        $data = array();
        $assocList = array();
        $db = connectToDb();
        $query = $db->prepare("SELECT * FROM association");
        try{
            $query->execute();
            $data = $query->fetchAll();
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }

        foreach ($data as $value) {
            $assoc = new Association();
            $assoc->hydrate($value);
            array_push($assocList, $assoc);
        }
        $this->setAssoc($assocList);
        $query->closeCursor();
    }

}