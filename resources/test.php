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
for($i = 1; $i< 10; $i++)
{
    $objet = Object::createObject($_SESSION['userobject']->id(), 1, "petite robe noire $i", false, $i+ 0.20, false, $i." ans", 1);
    $objet->insertObjectIntoDb();
}*/


//$objet = Object::loadObjectFromId(50);
//$objet->deleteObject();

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