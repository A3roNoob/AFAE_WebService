<?php
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
    include_once(TEMPLATES_PATH . '/footer.php');
    exit(403);
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
    Try {
        if(!is_a($date, "DateTime")){
            if(strpos($date, '/'))
                $date = DateTime::createFromFormat("d/m/Y", $date);
            else if(strpos($date, '-'))
                $date = DateTime::createFromFormat("d-m-Y", $date);
        }
        $date = $date->format("Y-m-d");

    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }

    return $date;
}

function convertDateFromSql($date)
{
    Try{
        if(!is_a($date, "DateTime")){
            if(strpos($date, '/'))
                $date = DateTime::createFromFormat("d/m/Y", $date);
            else if(strpos($date, '-'))
                $date = DateTime::createFromFormat("Y-m-d", $date);
        }
        $date = $date->format("d-m-Y");
    }
    catch ( Exception $e)
    {
        echo $e->getMessage();
    }
    return $date;
}

function today()
{
    return date("d-m-Y");
}

function compareDate($date1, $date2)
{
    echo "<br />";
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