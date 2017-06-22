<?php

class Transaction
{
    private $_idTransaction;
    private $_idFoire;
    private $_idUser;
    private $_montant;
    private $_idPaiement;
    private $_idBanque;
    private $_dateTransaction;
    private $_nomClient;

    /**
     * @return mixed
     */
    public function getIdTransaction()
    {
        return $this->_idTransaction;
    }

    /**
     * @param mixed $idTransaction
     */
    public function setIdTransaction($idTransaction)
    {
        $this->_idTransaction = $idTransaction;
    }

    /**
     * @return mixed
     */
    public function getIdFoire()
    {
        return $this->_idFoire;
    }

    /**
     * @param mixed $idFoire
     */
    public function setIdFoire($idFoire)
    {
        $this->_idFoire = $idFoire;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->_idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser)
    {
        $this->_idUser = $idUser;
    }

    /**
     * @return mixed
     */
    public function getMontant()
    {
        return $this->_montant;
    }

    /**
     * @param mixed $montant
     */
    public function setMontant($montant)
    {
        $this->_montant = $montant;
    }

    /**
     * @return mixed
     */
    public function getIdPaiement()
    {
        return $this->_idPaiement;
    }

    /**
     * @param mixed $idPaiement
     */
    public function setIdPaiement($idPaiement)
    {
        $this->_idPaiement = $idPaiement;
    }

    /**
     * @return mixed
     */
    public function getIdBanque()
    {
        return $this->_idBanque;
    }

    /**
     * @param mixed $idBanque
     */
    public function setIdBanque($idBanque)
    {
        $this->_idBanque = $idBanque;
    }

    /**
     * @return mixed
     */
    public function getDateTransaction()
    {
        return $this->_dateTransaction;
    }

    /**
     * @param mixed $dateTransaction
     */
    public function setDateTransaction($dateTransaction)
    {
        if (is_a($dateTransaction, "DateTime"))
            $this->_dateTransaction = $dateTransaction;
        else
            $this->_dateTransaction = $dateTransaction;
    }

    /**
     * @return mixed
     */
    public function getNomClient()
    {
        return $this->_nomClient;
    }

    /**
     * @param mixed $nomClient
     */
    public function setNomClient($nomClient)
    {
        $this->_nomClient = $nomClient;
    }

    public function hydrate(array $data)
    {
        if (isset($data['idtransaction']))
            $this->setIdTransaction($data['idtransaction']);
        if (isset($data['idfoire']))
            $this->setIdFoire($data['idfoire']);
        if (isset($data['iduser']))
            $this->setIdUser($data['iduser']);
        if (isset($data['montant']))
            $this->setMontant($data['montant']);
        if (isset($data['idpaiement']))
            $this->setIdPaiement($data['idpaiement']);
        if (isset($data['idbanque']))
            $this->setIdBanque($data['idbanque']);
        if (isset($data['datetransaction']))
            $this->setDateTransaction($data['datetransaction']);
        if (isset($data['nomclient']))
            $this->setNomClient($data['nomclient']);
    }


    public static function createTransaction($idFoire, $idUser, $nomClient, $montant, $idPaiement, $idBanque, $dateTranscation)
    {
        $tran = new Self();
        $tran->setIdFoire($idFoire);
        $tran->setIdUser($idUser);
        $tran->setNomClient($nomClient);
        $tran->setMontant($montant);
        $tran->setIdPaiement($idPaiement);
        $tran->setIdBanque($idBanque);
        $tran->setDateTransaction($dateTranscation);

        return $tran;
    }

    public function insertIntoDb(){
        $db = connectToDb();
        $query = $db->prepare("INSERT INTO transaction(idfoire, idutilisateur, nomclient, montant, idpaiement, idbanque, datetransaction) VALUES (:idfoire, :iduser, :nomclient, :montant, :idpaiement, :idbanque, :datetr);");
        $query->bindValue(':idfoire', $this->getIdFoire(), PDO::PARAM_INT);
        $query->bindValue(':iduser', $this->getIdUser(), PDO::PARAM_INT);
        $query->bindValue(':nomclient', $this->getNomClient());
        $query->bindValue(':montant', $this->getMontant());
        $query->bindValue(':idpaiement', $this->getIdPaiement(), PDO::PARAM_INT);
        $query->bindValue(':idbanque', $this->getIdBanque(), PDO::PARAM_INT);
        $query->bindValue(':datetr', convertDateToSql($this->getDateTransaction()));

        try{
            $query->execute();
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }

}