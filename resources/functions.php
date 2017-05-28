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

function accessForbidden()
{
    echo "<div class='alert alert-danger'>Acc&egrave;s interdit</div>";
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function convertDateToSql($date)
{
    $date = DateTime::createFromFormat('d/m/Y', $date);
    return $date->format('Y-m-d');
}

function convertDateFromSql($date)
{
    $date = DateTime::createFromFormat('Y-m-d', $date);
    return $date->format('d-m-Y');
}

function today()
{
    return date("d-m-Y");
}

function compareDate($date1, $date2)
{
    $d1ex = explode("-", $date1);
    $d2ex = explode("-", $date2);

    if ($d2ex[2] > $d1ex[2]) {
        return true;
    } else {
        if ($d2ex[1] >= $d1ex[1]) {
            return true;
        } else {
            if ($d2ex[0] >= $d1ex[0])
                return true;
        }
    }

    return false;
}