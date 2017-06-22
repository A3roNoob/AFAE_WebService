<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 12:34
 */
require_once('config.php');
require(CLASS_PATH."/User.php");
require(CLASS_PATH . "/Rank.php");
require(CLASS_PATH."/Object.php");
session_start();

/*
for($i = 0; $i< 30; $i++)
{
    $objet = Object::createObject($_SESSION['userobject']->id(), 2, "Petite robe $i", false, $i+ 0.20, false, $i." ans", 1, false);
    echo $objet->insertObjectIntoDb();
}

/*
try
{
    ObjectManager::deleteObject(48);

}
catch (PDOException $e)
{
    echo $e->getMessage();
}
catch (Exception $e)
{
    echo $e->getMessage();
}
*/
//for($i = 0; $i < 3; $i++){
    /*$objet = Object::createObject(3, 1, "Puma Air Jordans", false, 70, false, 43, 2, false);
    $objet->insertObjectIntoDb();
//}
//$objMan = new ObjectManager($_SESSION['userobject']);
/*
$_SESSION['userobject'] = User::createUser("KELHOME", "oui", "NOJFHD90S", "91650", "OUIIUIUI", "0787166117", false, false, 1, "boutryguillaume1@gmail.com");

try
{
    $_SESSION['userobject']->insertIntoDb("wellan", "oui");
}
catch (PDOException $e) {
    echo "<div class='messagebox'>".$e->getMessage()."</div>";
}*/

/*$oui = convertDateToSql("27/03/1998") > convertDateToSql("26/03/1998");
var_dump($oui);*/
/*
$d = "20/06/2017";

$date = DateTime::createFromFormat("d/m/Y", $d);
$date = $date->format("Y-m-d");
var_dump($date);*/

echo today();
$today = DateTime::createFromFormat("d-m-Y", today());
$today = $today->format("d/m/Y");
echo $today;