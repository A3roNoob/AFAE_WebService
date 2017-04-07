<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 05/04/2017
 * Time: 14:21
 */

include("../config.php");
include("../functions.php");
function tryCatch($query){
    try{
        $query->execute();
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}

$db = connectToDb();

$query = $db->prepare("UPDATE utilisateur SET rang=3 WHERE idutilisateur=2");

tryCatch($query);
$query->closeCursor();