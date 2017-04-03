<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 11:38
 */
require_once(dirname(__FILE__) . "/config.php");
function connectToDb()
{
    global $config;
    try {
        $db = new PDO('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'], $config['db']['username'], $config['db']['password']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo '&Eacute;chec lors de la connexion : ' . $e->getMessage();
        $db = null;
    }
    return $db;
}

?>