<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 03/04/2017
 * Time: 15:59
 */
require_once("resources/config.php");
require_once("resources/functions.php");
require(CLASS_PATH . "/User.php");
session_start();

$pagetitle = 'Saisie des objets';
$level = Rank::loadFromName("Vendeur");

include_once(TEMPLATES_PATH . '/header.php');
if(isset($_SESSION['userobject']) && $_SESSION['userobject']->checkRank(Rank::loadFromName('Vendeur'))) {
    ?>


    <?php
} else {
    accessForbidden();
}
include_once(TEMPLATES_PATH . '/footer.php');
?>