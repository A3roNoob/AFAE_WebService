<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 26/03/2017
 * Time: 20:37
 * passwordhash : P2tuteurafae_oui
 */
require_once("resources/config.php");

require(CLASS_PATH."/User.php");
session_start();


$pagetitle = 'Accueil';
include_once(TEMPLATES_PATH.'/header.php');

if(isset($_GET['code']))
{
    include(ERROR_PATH . '/' . $_GET['code'] .'.php');
}

include_once(TEMPLATES_PATH.'/footer.php');

?>